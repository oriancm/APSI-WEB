#!/bin/sh
set -eu

if [ -z "${ADMIN_LOGIN:-}" ]; then
  echo "ADMIN_LOGIN must be set to create the admin account." >&2
  exit 1
fi

if [ -n "${ADMIN_PASSWORD_HASH_FILE:-}" ] && [ -f "${ADMIN_PASSWORD_HASH_FILE}" ]; then
  ADMIN_PASSWORD_HASH="$(cat "${ADMIN_PASSWORD_HASH_FILE}")"
fi

if [ -z "${ADMIN_PASSWORD_HASH:-}" ]; then
  echo "ADMIN_PASSWORD_HASH or ADMIN_PASSWORD_HASH_FILE must be set to create the admin account." >&2
  exit 1
fi

mariadb --protocol=socket -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" <<SQL
INSERT INTO admin (login, pass)
VALUES ('${ADMIN_LOGIN}', '${ADMIN_PASSWORD_HASH}')
ON DUPLICATE KEY UPDATE pass = VALUES(pass);
SQL
