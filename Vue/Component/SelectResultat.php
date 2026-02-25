<?php

require_once(__DIR__ . '/Select.php');
require_once(__DIR__ . '/../../Modele/Rencontre/RencontreResultat.php');

class SelectResultat extends Select {
    public function __construct(
        ?string $description,
        ?string $selectedValue = null
    ) {
        $values = [];
        foreach (RencontreResultat::cases() as $resultat) {
            $values[$resultat->name] = $resultat->name;
        }

        parent::__construct($values, "resultat", $description, $selectedValue);
    }
}