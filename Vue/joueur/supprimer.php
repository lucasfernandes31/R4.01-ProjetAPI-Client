<?php

session_start();
$urlAPI = "http://localhost:8081/joueur";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        
        // Inclusion de l'ID dans l'URL
        $urlAPI = $urlAPI . "/" . $_POST['id'];

        // Création du contexte (méthode DELETE)
        $context = stream_context_create([
            'http' => [
                'method' => 'DELETE',
                'header' => 'Content-Type: application/json',
                'ignore_errors' => true
            ]
        ]);

        $response = file_get_contents($urlAPI, false, $context);
        $responseTab = json_decode($response, true);

        if ($responseTab['status_code'] !== 200) {
            // Stocker l'erreur en session
            $_SESSION['error'] = "Impossible de supprimer le joueur, car il possède un commentaire, ou va ou a déjà participé à un match.";
        } else {
            $_SESSION['success'] = "Joueur supprimé avec succès";
        }
    }
}
header('Location: /joueur');
exit;