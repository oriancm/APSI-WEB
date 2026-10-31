"""Regression checks against disposable project data; never writes to production."""
import importlib.util
import os
from pathlib import Path
import subprocess

ROOT = Path(__file__).resolve().parents[1]
spec = importlib.util.spec_from_file_location('admin_fixture', ROOT / 'tests/admin/run.py')
module = importlib.util.module_from_spec(spec)
spec.loader.exec_module(module)
fixture = module.Fixture()
try:
    fixture.connection.execute('UPDATE reference SET moa=NULL, archi=NULL, eMoe=NULL, anneeD=NULL, anneeF=NULL, statut=NULL, montant=NULL, nbPhase=NULL, nbE=NULL, duree_travaux_mois=NULL, nombre_lots=NULL WHERE id=1')
    fixture.connection.execute('UPDATE reference SET moa=?, archi=?, eMoe=?, nbPhase=4, nbE=12, nombre_lots=16, duree_travaux_mois=18 WHERE id=2', ('Maîtrise d’ouvrage ' * 5, 'Cabinet d’architecture et partenaires ' * 2, 'Équipe de maîtrise d’œuvre ' * 3))
    fixture.connection.commit()
    env = dict(os.environ, APSI_TEST_URL=fixture.base)
    raise SystemExit(subprocess.run(['node', str(ROOT / 'tests/reference-layout.cjs')], env=env, cwd=ROOT).returncode)
finally:
    fixture.close()
