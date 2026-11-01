"""Test real PHPMailer delivery using a local SMTP sink; no external email."""
import email
import email.policy
import os
from pathlib import Path
import shutil
import socket
import socketserver
import subprocess
import threading
import time
import unittest
import urllib.error
import urllib.parse
import urllib.request

ROOT = Path(__file__).resolve().parents[1]


class SMTPHandler(socketserver.StreamRequestHandler):
    def handle(self):
        self.wfile.write(b'220 localhost test SMTP\r\n')
        while line := self.rfile.readline():
            command = line.decode().strip().upper()
            if command.startswith(('EHLO', 'HELO')):
                self.wfile.write(b'250 localhost\r\n')
            elif command.startswith('MAIL FROM:'):
                self.wfile.write(b'250 Sender accepted\r\n')
            elif command.startswith('RCPT TO:'):
                self.server.recipients.append(line.decode().strip())
                self.wfile.write(b'550 Rejected for test\r\n' if self.server.reject else b'250 Recipient accepted\r\n')
            elif command == 'DATA':
                self.wfile.write(b'354 End with dot\r\n')
                body = []
                while (part := self.rfile.readline()) not in (b'.\r\n', b''):
                    body.append(part[1:] if part.startswith(b'..') else part)
                self.server.messages.append(b''.join(body))
                self.wfile.write(b'250 Queued\r\n')
            elif command == 'QUIT':
                self.wfile.write(b'221 Bye\r\n')
                return
            else:
                self.wfile.write(b'250 OK\r\n')


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs):
        return None


class ContactDelivery(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.smtp = socketserver.ThreadingTCPServer(('127.0.0.1', 0), SMTPHandler)
        cls.smtp.messages, cls.smtp.recipients, cls.smtp.reject = [], [], False
        threading.Thread(target=cls.smtp.serve_forever, daemon=True).start()
        with socket.socket() as probe:
            probe.bind(('127.0.0.1', 0))
            port = probe.getsockname()[1]
        env = dict(os.environ, SMTP_HOST='127.0.0.1', SMTP_PORT=str(cls.smtp.server_address[1]),
                   SMTP_USERNAME='', SMTP_PASSWORD='', SMTP_FROM='info@apsi-btp.fr',
                   SMTP_ENCRYPTION='none', SMTP_TO='obsolete@example.test', APSI_ALLOW_DEMO_DATA='1')
        cls.php = subprocess.Popen([shutil.which('php'), '-S', f'127.0.0.1:{port}', 'router.php'],
                                   cwd=ROOT, env=env, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        cls.base = f'http://127.0.0.1:{port}'
        cls.opener = urllib.request.build_opener(NoRedirect)
        for _ in range(100):
            try:
                urllib.request.urlopen(cls.base + '/contact', timeout=1).close()
                break
            except OSError:
                time.sleep(.05)
        else:
            cls.php.terminate()
            raise RuntimeError('Local PHP server failed to start')

    @classmethod
    def tearDownClass(cls):
        cls.php.terminate()
        cls.php.wait(timeout=5)
        cls.smtp.shutdown()
        cls.smtp.server_close()

    def post(self, overrides=None, path='/contact'):
        fields = dict(name='Élodie Test', email='elodie@example.test', objet='Projet à Caseneuve', message='Bonjour,\nUn projet à étudier.', website='')
        fields.update(overrides or {})
        try:
            response = self.opener.open(self.base + path, urllib.parse.urlencode(fields).encode(), timeout=5)
        except urllib.error.HTTPError as response:
            return response.code, response.headers, response.read().decode()
        with response:
            return response.status, response.headers, response.read().decode()

    def test_delivery_and_reply_address(self):
        status, headers, _ = self.post()
        self.assertEqual(status, 303)
        self.assertEqual(headers['Location'], '/mailSent')
        self.assertEqual(self.smtp.recipients[-1], 'RCPT TO:<info@apsi-btp.fr>')
        message = email.message_from_bytes(self.smtp.messages[-1], policy=email.policy.default)
        self.assertIn('elodie@example.test', message['Reply-To'])
        self.assertIn('info@apsi-btp.fr', message['From'])
        self.assertIn('Élodie Test', message['Subject'])
        self.assertIn('Un projet à étudier.', message.get_content())
        self.assertIn('elodie@example.test', message.get_content())

    def test_invalid_requests_do_not_send(self):
        before = len(self.smtp.messages)
        for invalid in [dict(email=''), dict(email='invalid'), dict(name='x\r\nBcc: injected@example.test'),
                        dict(message=''), dict(message='x' * 20001), dict(website='spam'), dict(**{'email[]': 'x', 'email': ''})]:
            status, _, html = self.post(invalid)
            self.assertEqual(status, 200)
            self.assertIn('role="alert"', html)
        self.assertEqual(len(self.smtp.messages), before)

    def test_delivery_failure_preserves_fields(self):
        type(self).smtp.reject = True
        before = len(self.smtp.messages)
        try:
            status, _, html = self.post()
            self.assertEqual(status, 200)
            self.assertIn('L&#039;envoi a échoué', html)
            self.assertIn('elodie@example.test', html)
            self.assertEqual(len(self.smtp.messages), before)
        finally:
            self.smtp.reject = False

    def test_confirmation_cannot_send_mail(self):
        before = len(self.smtp.messages)
        status, headers, _ = self.post(path='/mailSent')
        self.assertEqual(status, 405)
        self.assertEqual(headers['Allow'], 'GET, HEAD')
        self.assertEqual(len(self.smtp.messages), before)

    def test_original_wide_image(self):
        with urllib.request.urlopen(self.base + '/contact') as response:
            html = response.read().decode()
        self.assertIn('width="2172" height="724"', html)
        self.assertIn('/img/home-crane-original.webp', html)


if __name__ == '__main__':
    unittest.main(verbosity=2)
