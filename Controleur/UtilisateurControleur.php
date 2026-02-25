<?php

require_once(__DIR__ . '/../Modele/Joueur/Commentaire/Commentaire.php');
require_once(__DIR__ . '/../Modele/Joueur/Commentaire/CommentaireDAO.php');
require_once(__DIR__ . '/../Modele/Joueur/Joueur.php');
require_once(__DIR__ . '/../Modele/Joueur/JoueurDAO.php');
require_once(__DIR__ . '/../Modele/Joueur/JoueurStatut.php');
require_once(__DIR__ . '/../Modele/Statistiques/StatistiquesEquipe.php');
require_once(__DIR__ . '/../Modele/Statistiques/StatistiquesJoueurs.php');
require_once(__DIR__ . '/../Modele/Utilisateur/UtilisateurDAO.php');


class UtilisateurControleur {
    private static ?UtilisateurControleur $instance = null;
    private readonly UtilisateurDAO $utilisateurs;

    private function __construct() {
        $this->utilisateurs = UtilisateurDAO::getInstance();
    }

    public static function getInstance(): UtilisateurControleur {
        if (self::$instance == null) {
            self::$instance = new UtilisateurControleur();
        }
        return self::$instance;
    }

    public function seConnecter(string $username, string $password): bool {
        $utilisateurEssayantDeSeConnecter = $this->utilisateurs->getUtilisateur($username);

        if ($utilisateurEssayantDeSeConnecter->getMotDePasse() == $password) {
            session_set_cookie_params(1800);
            ini_set('session.gc_maxlifetime', 1800);

            // Store username in session
            $_SESSION['username'] = $username;
            return true;
        } else {
            return false;
        }
    }
}