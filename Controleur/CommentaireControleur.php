<?php

use DateTime;
require_once(__DIR__ . '/../Modele/Joueur/Commentaire/Commentaire.php');
require_once(__DIR__ . '/../Modele/Joueur/Commentaire/CommentaireDAO.php');
require_once(__DIR__ . '/../Modele/Joueur/Joueur.php');
require_once(__DIR__ . '/../Modele/Joueur/JoueurDAO.php');
require_once(__DIR__ . '/../Modele/Joueur/JoueurStatut.php');

class CommentaireControleur {
    private static ?CommentaireControleur $instance = null;
    private readonly CommentaireDAO $commentaires;

    private function __construct() {
        $this->commentaires = CommentaireDAO::getInstance();
    }

    public static function getInstance(): CommentaireControleur {
        if (self::$instance == null) {
            self::$instance = new CommentaireControleur();
        }
        return self::$instance;
    }

    public function ajouterCommentaire(
        string $contenu,
        string $joueurId
    ) : bool {

        $commentaireACreer = new Commentaire(
            0,
            $contenu,
            new DateTime()
        );

        return $this->commentaires->insertCommentaire($commentaireACreer, $joueurId);
    }

    public function listerLesCommentairesDuJoueur(Joueur $joueur) : array {
        return $this->commentaires->selectCommentaireByJoueurId($joueur->getJoueurId());
    }

    public function supprimerCommentaire(string $commentaireId) : bool {
        return $this->commentaires->deleteCommentaire($commentaireId);
    }
}