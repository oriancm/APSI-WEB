<?php
function getDomaineText($domaine) {
    switch ($domaine) {
        case '1':
            return 'Résidence universitaire';
        case '2':
            return 'Equipements sportifs';
        case '3':
            return 'Equipements culturels';
        case '4':
            return 'Groupes scolaires et collèges';
        case '5':
            return 'Aménagements urbains';
        case '6':
            return 'Bâtiments publics';
        case '7':
            return 'Crèches';
        case '8':
            return 'Centre d\'Incendie et de Secours';
        case '9':
            return 'Hôpitaux publics';
        case '10':
            return 'Logements sociaux';
        case '11':
            return 'Restructuration et réhabilitation en site occupé (phasage)';
        default:
            return '';
    }
}

function getStatutText($statut) {
    switch ($statut) {
        case '1':
            return 'Opération livrée <br>';
        case '2':
            return 'Travaux en cours<br>';
        case '3':
            return 'Conception en cours<br>';
    }
}