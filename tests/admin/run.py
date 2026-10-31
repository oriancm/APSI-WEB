"""Real HTTP tests against a disposable copy and SQLite DB. Never connects to production.

Usage: python tests/admin/run.py [--browser] [--serve]
Requires PHP (gd, pdo_sqlite), Python requests; browser tests also use npm dev dependencies.
"""
import argparse
import base64
from html.parser import HTMLParser
import json
import os
from pathlib import Path
import shutil
import socket
import sqlite3
import struct
import subprocess
import tempfile
import time
import unittest
import zlib

import requests

REPO = Path(__file__).resolve().parents[2]


def png(width=240, height=120):
    def chunk(kind, data):
        return struct.pack('!I', len(data)) + kind + data + struct.pack('!I', zlib.crc32(kind + data))
    data = (b'\0' + bytes([65, 105, 80]) * width) * height
    return b'\x89PNG\r\n\x1a\n' + chunk(b'IHDR', struct.pack('!2I5B', width, height, 8, 2, 0, 0, 0)) + chunk(b'IDAT', zlib.compress(data)) + chunk(b'IEND', b'')


class Inputs(HTMLParser):
    def __init__(self, html):
        super().__init__(); self.values = {}; self.feed(html)
    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == 'input' and 'name' in attrs:
            self.values[attrs['name']] = attrs.get('value', '')


class Fixture:
    def __init__(self):
        self.temp = tempfile.TemporaryDirectory(prefix='apsi-admin-tests-')
        self.root = Path(self.temp.name)
        for directory in ['admin', 'functions', 'partials', 'css', 'js', 'font/web']:
            shutil.copytree(REPO / directory, self.root / directory, ignore=shutil.ignore_patterns('.env', '__pycache__'))
        for path in REPO.glob('*.php'):
            shutil.copy2(path, self.root / path.name)
        (self.root / 'pic').mkdir()
        (self.root / 'img').mkdir()
        for name in ['logo-nav.webp', 'logo-nav-white.webp']:
            shutil.copy2(REPO / 'img' / name, self.root / 'img' / name)
        self.path = self.root / 'test.sqlite'
        self.password = 'Fixture-only-password-2026!'
        php = shutil.which('php')
        ini = subprocess.check_output([php, '-r', 'echo php_ini_loaded_file();'], text=True).strip()
        hash_ = subprocess.check_output([php, '-r', 'echo password_hash($argv[1], PASSWORD_DEFAULT);', self.password], text=True)
        self.connection = sqlite3.connect(self.path)
        self.connection.executescript('''
            PRAGMA foreign_keys=ON;
            CREATE TABLE admin (login TEXT PRIMARY KEY, pass TEXT NOT NULL);
            CREATE TABLE reference (id INTEGER PRIMARY KEY AUTOINCREMENT, titre TEXT NOT NULL, commune TEXT, description TEXT, domaine INTEGER, anneeD INTEGER, anneeF INTEGER, statut INTEGER, moa TEXT, archi TEXT, eMoe TEXT, nbPhase INTEGER, montant DECIMAL, nbE INTEGER, duree_travaux_mois INTEGER, nombre_lots INTEGER);
            CREATE TABLE photo (id INTEGER PRIMARY KEY AUTOINCREMENT,titre TEXT NOT NULL, dir TEXT NOT NULL,idR INTEGER REFERENCES reference(id), orderPic INTEGER NOT NULL);
        ''')
        self.connection.execute('INSERT INTO admin VALUES (?,?)', ('admin', hash_))
        refs = [
            ('Aménagement de la Place Jean Jaurès', 'APT (84)', 5, 1, 'amenagement_de_la_place_jean_jaures', 4),
            ('Centre Culturel Simone Signoret', 'CHATEAU-ARNOUX (04)', 3, 1, 'centre_culturel_simone_signoret', 7),
            ('L’hôtel des Monnaies-Niel', 'AVIGNON (84)', 12, 2, 'l_hotel_des_monnaies_niel', 9),
            ("Résidence l’Aygues", 'ORANGE (84)', 10, 2, 'residence_l_aygues', 1),
            ('Réfectoire de Coudoux', 'COUDOUX (13)', 4, 1, 'refectoire_de_coudoux', 7),
            ('Réhabilitation du collège Paul Cézanne', 'BRIGNOLES (83)', 4, 2, 'rehabilitation_du_college_paul_cezanne', 6),
            ('Résidence Les Angevines', 'LE THOR (84)', 10, 2, 'residence_les_angevines', 8),
        ]
        for index, (title, city, domain, status, prefix, count) in enumerate(refs, 1):
            self.connection.execute('INSERT INTO reference (id,titre,commune,domaine,statut,description,anneeD,anneeF,montant) VALUES (?,?,?,?,?,?,?,?,?)', (index, title, city, domain, status, 'Présentation du projet APSI.', 2024, 2027, 2500000))
            for number in range(1, count + 1):
                name = f'{prefix}_{number}.webp'
                shutil.copy2(REPO / 'pic' / name, self.root / 'pic' / name)
                self.connection.execute('INSERT INTO photo (titre,dir,idR,orderPic) VALUES (?,?,?,?)', (name, 'pic/' + name, index, number))
        self.connection.commit()
        # This replacement exists only inside the disposable fixture, never in application code.
        encoded = base64.b64encode(str(self.path).replace('\\', '/').encode()).decode()
        (self.root / 'admin/db.php').write_text('<?php $db = new PDO("sqlite:" . base64_decode("' + encoded + '")); $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); $db->exec("PRAGMA foreign_keys=ON");', encoding='utf-8')
        with socket.socket() as sock:
            sock.bind(('127.0.0.1', 0)); self.port = sock.getsockname()[1]
        self.base = f'http://127.0.0.1:{self.port}'
        self.log = open(self.root / 'server.log', 'w+')
        self.process = subprocess.Popen([php, *(['-c', ini] if ini else []), '-d', 'upload_max_filesize=15M', '-d', 'post_max_size=65M', '-d', 'memory_limit=256M', '-S', f'127.0.0.1:{self.port}', 'router.php'], cwd=self.root, stdout=self.log, stderr=self.log)
        for _ in range(40):
            try:
                requests.get(self.base + '/admin/login.php', timeout=1); break
            except requests.ConnectionError:
                time.sleep(.1)
        else:
            raise RuntimeError('Fixture server did not start')

    def close(self):
        self.process.terminate(); self.process.wait(timeout=10); self.connection.close(); self.log.close(); self.temp.cleanup()


class AdminHTTPTests(unittest.TestCase):
    fixture = None

    def setUp(self):
        self.session = requests.Session()
        page = self.session.get(self.fixture.base + '/admin/login.php')
        old = self.session.cookies.get('APSI_ADMIN')
        response = self.session.post(self.fixture.base + '/admin/login.php', data={**Inputs(page.text).values, 'login': 'admin', 'pass': self.fixture.password}, allow_redirects=False)
        self.assertEqual(response.status_code, 303)
        self.assertNotEqual(old, self.session.cookies.get('APSI_ADMIN'))

    def get(self, path):
        return self.session.get(self.fixture.base + path)

    def form(self, id_=None):
        return Inputs(self.get('/admin/mod.php?id=' + str(id_) if id_ else '/admin/add.php').text).values

    def data(self, **changes):
        return dict(self.form(), titre='Projet de test', city='Avignon', postal_code='84000', domaine='4', statut='2', **changes)

    def photo_count(self):
        return self.fixture.connection.execute('SELECT COUNT(*) FROM photo').fetchone()[0]

    def test_01_authentication_headers_and_private_modules(self):
        self.assertEqual(requests.get(self.fixture.base + '/admin/session.php').status_code, 401)
        for path in ['/admin/admin.php', '/admin/add.php', '/admin/mod.php?id=1', '/admin/change_password.php']:
            response = requests.get(self.fixture.base + path, allow_redirects=False)
            self.assertEqual(response.status_code, 303, path)
            self.assertEqual(response.headers['Location'], '/admin/login.php')
        for path in ['/admin/db.php', '/admin/db.php/extra', '/admin/editor.php/extra', '/admin/editor.php', '/admin/bootstrap.php', '/admin/reference_service.php', '/.env', '/admin/.env/extra', '/secrets/private-key', '/backups/db.serialized', '/tests/test.php', '/pic/payload.php']:
            self.assertEqual(self.get(path).status_code, 403, path)
        response = self.get('/admin/admin.php')
        self.assertEqual(response.headers['X-Frame-Options'], 'DENY')
        self.assertIn("frame-ancestors 'none'", response.headers['Content-Security-Policy'])
        self.assertIn('no-store', response.headers['Cache-Control'])
        guest = requests.get(self.fixture.base + '/admin/login.php')
        self.assertIn('HttpOnly', guest.headers['Set-Cookie'])
        self.assertIn('SameSite=Strict', guest.headers['Set-Cookie'])

    def test_02_csrf_and_logout(self):
        before = self.photo_count()
        for path in ['/admin/admin.php', '/admin/add.php', '/admin/mod.php?id=1', '/admin/change_password.php', '/admin/logout.php']:
            self.assertEqual(self.session.post(self.fixture.base + path, data={'csrf': 'bad', 'delete_id': '1'}).status_code, 403, path)
        self.assertEqual(self.photo_count(), before)
        self.assertEqual(self.get('/admin/logout.php').status_code, 405)
        self.assertIn('Vos références', self.get('/admin/admin.php').text)

    def test_03_conversion_duplicates_order_and_idempotency(self):
        data = self.data(); data['photo_order'] = '["new:1","new:0"]'
        files = [('images[]', ('same-name.png', png(2400, 1200), 'image/png')), ('images[]', ('same-name.png', png(), 'image/png'))]
        response = self.session.post(self.fixture.base + '/admin/add.php', data=data, files=files, allow_redirects=False)
        self.assertEqual(response.status_code, 303, response.text)
        self.assertRegex(response.headers['Location'], r'/admin/mod.php\?id=\d+')
        id_ = int(response.headers['Location'].split('=')[-1])
        rows = self.fixture.connection.execute('SELECT titre,orderPic FROM photo WHERE idR=? ORDER BY orderPic', (id_,)).fetchall()
        self.assertEqual(len(rows), 2); self.assertNotEqual(rows[0][0], rows[1][0])
        for name, order in rows:
            for folder in ['', 'mobile/', 'cards/']:
                content = (self.fixture.root / 'pic' / (folder + name)).read_bytes()
                self.assertEqual(content[:4], b'RIFF'); self.assertEqual(content[8:12], b'WEBP')
        self.assertEqual(self.fixture.connection.execute('SELECT commune FROM reference WHERE id=?', (id_,)).fetchone()[0], 'Avignon (84000)')
        repeat = self.session.post(self.fixture.base + '/admin/add.php', data=data, allow_redirects=False)
        self.assertEqual(repeat.headers['Location'], response.headers['Location'])
        self.assertEqual(self.fixture.connection.execute('SELECT COUNT(*) FROM reference WHERE titre=?', ('Projet de test',)).fetchone()[0], 1)
        public = self.get('/reference/' + str(id_)); self.assertEqual(public.status_code, 200); self.assertIn(rows[0][0], public.text)

    def test_04_invalid_upload_never_creates_a_reference(self):
        before = self.fixture.connection.execute('SELECT COUNT(*) FROM reference').fetchone()[0]
        response = self.session.post(self.fixture.base + '/admin/add.php', data=self.data(), files={'images[]': ('evil.jpg', b'<?php echo "executed";', 'image/jpeg')})
        self.assertIn('Photo illisible', response.text)
        self.assertEqual(self.fixture.connection.execute('SELECT COUNT(*) FROM reference').fetchone()[0], before)
        response = self.session.post(self.fixture.base + '/admin/add.php', data=self.data(), files={'images[]': ('picture.php', png(), 'image/png')})
        self.assertIn('Utilisez des photos', response.text)
        oversized = bytearray(png())
        oversized[16:24] = struct.pack('!2I', 10000, 3000)
        response = self.session.post(self.fixture.base + '/admin/add.php', data=self.data(), files={'images[]': ('oversized.png', oversized, 'image/png')})
        self.assertIn('20 millions de pixels', response.text)

    def test_05_database_failure_rolls_back_rows_and_files(self):
        before_files = set((self.fixture.root / 'pic').rglob('*.webp'))
        before_rows = self.fixture.connection.execute('SELECT COUNT(*) FROM reference').fetchone()[0]
        self.fixture.connection.execute("CREATE TRIGGER fail_photo BEFORE INSERT ON photo BEGIN SELECT RAISE(ABORT, 'fixture-secret-SQL-error'); END")
        self.fixture.connection.commit()
        try:
            response = self.session.post(self.fixture.base + '/admin/add.php', data=self.data(), files={'images[]': ('test.png', png(), 'image/png')})
            self.assertIn('Aucun changement', response.text)
            self.assertNotIn('fixture-secret-SQL-error', response.text)
            self.assertEqual(self.fixture.connection.execute('SELECT COUNT(*) FROM reference').fetchone()[0], before_rows)
            self.assertEqual(set((self.fixture.root / 'pic').rglob('*.webp')), before_files)
        finally:
            self.fixture.connection.execute('DROP TRIGGER fail_photo'); self.fixture.connection.commit()

    def test_06_edit_preserves_legacy_location_and_prevents_stale_overwrite(self):
        first, stale = self.form(1), self.form(1)
        for data in [first, stale]:
            data.update(city='APT', postal_code='', domaine='5', statut='1')
        first['description'] = 'La modification la plus récente.'
        response = self.session.post(self.fixture.base + '/admin/mod.php?id=1', data=first, allow_redirects=False)
        self.assertEqual(response.status_code, 303, response.text)
        stale['description'] = 'Ancienne version à rejeter'
        response = self.session.post(self.fixture.base + '/admin/mod.php?id=1', data=stale)
        self.assertIn('autre onglet', response.text)
        row = self.fixture.connection.execute('SELECT description,commune FROM reference WHERE id=1').fetchone()
        self.assertEqual(row, ('La modification la plus récente.', 'APT (84)'))

    def test_07_reject_foreign_photo_and_duplicate_order(self):
        data = self.form(1); data.update(city='APT', postal_code='', domaine='5', statut='1')
        foreign = self.fixture.connection.execute('SELECT id FROM photo WHERE idR=2 LIMIT 1').fetchone()[0]
        data['delete_photos[]'] = str(foreign)
        response = self.session.post(self.fixture.base + '/admin/mod.php?id=1', data=data)
        self.assertIn('ne fait pas partie', response.text)
        self.assertTrue(self.fixture.connection.execute('SELECT id FROM photo WHERE id=?', (foreign,)).fetchone())
        data.pop('delete_photos[]')
        data['photo_order'] = '["existing:1","existing:1"]'
        response = self.session.post(self.fixture.base + '/admin/mod.php?id=1', data=data)
        self.assertIn('ordre des photos est invalide', response.text)

    def test_08_numeric_validation_and_xss(self):
        data = self.data(); data.update(anneeD='2026', anneeF='2020', montant='-1', postal_code='84')
        response = self.session.post(self.fixture.base + '/admin/add.php', data=data)
        self.assertIn('5 chiffres', response.text); self.assertIn('précéder', response.text)
        data = self.form(2); data.update(titre='<script>alert(1)</script>', city='CHATEAU-ARNOUX', domaine='3', statut='1')
        response = self.session.post(self.fixture.base + '/admin/mod.php?id=2', data=data)
        self.assertNotIn('<script>alert(1)</script>', response.text)
        self.assertIn('&lt;script&gt;alert(1)&lt;/script&gt;', response.text)
        # Restore the fixture title for visual checks.
        self.fixture.connection.execute('UPDATE reference SET titre=? WHERE id=2', ('Centre Culturel Simone Signoret',)); self.fixture.connection.commit()

    def test_09_delete_requires_confirmation_and_keeps_recoverable_files(self):
        form = self.form(7)
        data = {'csrf': form['csrf'], 'delete_id': '7', 'version': form['version'], 'confirmation': 'wrong'}
        response = self.session.post(self.fixture.base + '/admin/admin.php', data=data)
        self.assertIn('Confirmez la suppression', response.text)
        files = [self.fixture.root / 'pic' / row[0] for row in self.fixture.connection.execute('SELECT titre FROM photo WHERE idR=7')]
        data['confirmation'] = 'SUPPRIMER'
        response = self.session.post(self.fixture.base + '/admin/admin.php', data=data, allow_redirects=False)
        self.assertEqual(response.status_code, 303)
        self.assertIsNone(self.fixture.connection.execute('SELECT id FROM reference WHERE id=7').fetchone())
        self.assertTrue(all(path.exists() for path in files))

    def test_y_password_change_invalidates_other_sessions(self):
        second = requests.Session(); page = second.get(self.fixture.base + '/admin/login.php')
        second.post(self.fixture.base + '/admin/login.php', data={**Inputs(page.text).values, 'login': 'admin', 'pass': self.fixture.password})
        form = Inputs(self.get('/admin/change_password.php').text).values
        data = {'csrf': form['csrf'], 'current_password': self.fixture.password, 'new_password': 'Another-fixture-password-2026!', 'confirm_password': 'Another-fixture-password-2026!'}
        response = self.session.post(self.fixture.base + '/admin/change_password.php', data=data, allow_redirects=False)
        self.assertEqual(response.status_code, 303)
        self.fixture.password = data['new_password']
        self.assertEqual(second.get(self.fixture.base + '/admin/admin.php', allow_redirects=False).headers['Location'], '/admin/login.php')
        self.assertEqual(self.get('/admin/admin.php').status_code, 200)

    def test_z_rate_limit_persists_across_sessions(self):
        for _ in range(9):
            guest = requests.Session(); page = guest.get(self.fixture.base + '/admin/login.php')
            response = guest.post(self.fixture.base + '/admin/login.php', data={**Inputs(page.text).values, 'login': 'admin', 'pass': 'wrong'})
        self.assertEqual(response.status_code, 429)


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--browser', action='store_true')
    parser.add_argument('--serve', action='store_true')
    args = parser.parse_args()
    fixture = Fixture()
    try:
        if args.serve:
            print('Disposable fixture: ' + fixture.base, flush=True)
            print('Login: admin / ' + fixture.password, flush=True)
            while True: time.sleep(1)
        AdminHTTPTests.fixture = fixture
        suite = unittest.defaultTestLoader.loadTestsFromTestCase(AdminHTTPTests)
        result = unittest.TextTestRunner(verbosity=2).run(suite)
        if not result.wasSuccessful(): return 1
    finally: fixture.close()
    if args.browser:
        fixture = Fixture()
        try:
            env = dict(os.environ, APSI_TEST_URL=fixture.base, APSI_TEST_PASSWORD=fixture.password)
            return subprocess.run(['node', str(REPO / 'tests/admin/browser.cjs')], cwd=REPO, env=env).returncode
        finally: fixture.close()
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
