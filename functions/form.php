<?php


/** Fonction ayant pour but de mettre en forme un tableau d'image
 *  Forme du tableau en entrée [ name => ['pic1', 'pic2'], ext => ['jpg', 'png']]
 *  Forme du tableau en entrée $in[$key][$index] = $value;
 *  Forme du tableau en sortie [ ['name' => 'pic1', 'ext' => 'jpg'], ['name' => 'pic2', 'ext' => 'png']]
 *  Forme du tableau en sortie $out[$index][$key] = $value;
 * @return array
 */
function normalizeFiles(): array
{
    $out = [];
        if (isset($_FILES['pic']) && is_array($_FILES['pic'])) {
            foreach ($_FILES['pic'] as $key => $values) {
                foreach ($values as $index => $value) {
                    $out[$index][$key] = $value;
                }
            }
        }
    return $out;
}
