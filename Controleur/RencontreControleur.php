<?php

require_once(__DIR__ . '/../Modele/Rencontre/Rencontre.php');
require_once(__DIR__ . '/../Modele/Rencontre/RencontreDAO.php');
require_once(__DIR__ . '/../Modele/Rencontre/RencontreLieu.php');
require_once(__DIR__ . '/../Modele/Rencontre/RencontreResultat.php');

class RencontreControleur {
    private static ?RencontreControleur $instance = null;
    private readonly RencontreDAO $rencontres;

    private function __construct() {
        $this->rencontres = RencontreDAO::getInstance();
    }

    public static function getInstance(): RencontreControleur {
        if (self::$instance == null) {
            self::$instance = new RencontreControleur();
        }
        return self::$instance;
    }

    public function ajouterRencontre(
        DateTime $dateHeure,
        string $equipeAdverse,
        string $adresse,
        RencontreLieu $lieu
    ) : bool {

        if ($dateHeure < date("Y-m-d H:i:s")) {
            return false;
        } else {
            $rencontreAAjouter = new Rencontre(
                $dateHeure,
                $equipeAdverse,
                $adresse,
                $lieu
            );

            return $this->rencontres->insertRencontre($rencontreAAjouter);
        }
    }

    public function enregistrerResultat(
        int $rencontreId,
        string $resultat
    ) : bool {
        $rencontreAModifier = $this->rencontres->selectRencontreById($rencontreId);

        if (!$rencontreAModifier->estPassee()) {
            return false;
        } else {
            $rencontreAModifier->setResultat(RencontreResultat::fromName($resultat));

            return $this->rencontres->enregistrerResultat($rencontreAModifier);
        }
    }

    public function getRenconterById(int $rencontreId) : Rencontre {
        return $this->rencontres->selectRencontreById($rencontreId);
    }

    public function listerToutesLesRencontres() : array {
        return $this->rencontres->selectAllRencontres();
    }

    public function modifierRencontre(
        int $rencontreId,
        DateTime $dateHeure,
        string $equipeAdverse,
        string $adresse,
        RencontreLieu $lieu
    ) : bool {

        $rencontreAModifier = $this->rencontres->selectRencontreById($rencontreId);

        if (
            $rencontreAModifier->estPassee()
            || $dateHeure < new DateTime()
        ) {
            return false;
        } else {
            $rencontreAModifier->setDateEtHeure($dateHeure);
            $rencontreAModifier->setEquipeAdverse($equipeAdverse);
            $rencontreAModifier->setAdresse($adresse);
            $rencontreAModifier->setLieu($lieu);

            return $this->rencontres->updateRencontre($rencontreAModifier);
        }
    }

    public function supprimerRencontre(int $rencontreId) : bool {
        $rencontreASupprimer = $this->rencontres->selectRencontreById($rencontreId);

        if($rencontreASupprimer->getResultat() != null) {
            return false;
        } else {
            return $this->rencontres->supprimerRencontre($rencontreId);
        }
    }
}