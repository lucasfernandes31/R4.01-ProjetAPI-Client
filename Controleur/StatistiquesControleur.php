<?php

require_once(__DIR__ . '/../Modele/Joueur/Commentaire/Commentaire.php');
require_once(__DIR__ . '/../Modele/Joueur/Commentaire/CommentaireDAO.php');
require_once(__DIR__ . '/../Modele/Joueur/Joueur.php');
require_once(__DIR__ . '/../Modele/Joueur/JoueurDAO.php');
require_once(__DIR__ . '/../Modele/Joueur/JoueurStatut.php');
require_once(__DIR__ . '/../Modele/Statistiques/StatistiquesEquipe.php');
require_once(__DIR__ . '/../Modele/Statistiques/StatistiquesJoueurs.php');
require_once(__DIR__ . '/RencontreControleur.php');
require_once(__DIR__ . '/ParticipationControleur.php');

class StatistiquesControleur {
    private static ?StatistiquesControleur $instance = null;
    private readonly RencontreControleur $rencontres;
    private readonly ParticipationControleur $participations;

    private function __construct() {
        $this->rencontres = RencontreControleur::getInstance();
        $this->participations = ParticipationControleur::getInstance();
    }

    public static function getInstance(): StatistiquesControleur {
        if (self::$instance == null) {
            self::$instance = new StatistiquesControleur();
        }
        return self::$instance;
    }

    public function getStatistiquesEquipe() : StatistiquesEquipe {
        return new StatistiquesEquipe($this->rencontres->listerToutesLesRencontres());
    }

    public function getStatistiquesJoueurs() : StatistiquesJoueurs {
        return new StatistiquesJoueurs($this->participations->listerToutesLesParticipations(), $this->rencontres->listerToutesLesRencontres());
    }
}