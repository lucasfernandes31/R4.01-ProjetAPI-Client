<?php

$urlAPI = 'http://localhost:8081/commentaire';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['commentaireId'])) {

        // Transformation de l'URL pour cibler le commentaire sélectionné
        $urlAPI = $urlAPI . "?commentaireId=" . $_POST['commentaireId'];

        // Création du contexte (méthode DELETE)
        $context = stream_context_create([
            'http' => [
                'method' => 'DELETE',
                'header' => 'Content-Type: application/json',
                'ignore_errors' => true
            ]
        ]);

        // Envoi de la requete et récupération de la réponse
        $response = file_get_contents($urlAPI, false, $context);
        $responseTab = json_decode($response, true);

        if (!$responseTab['status_code'] === 200) {
            error_log("Erreur lors de la suppression du commentaire");
        }
    }
}

if (isset($_POST['joueurId'])) {
    header('Location: /joueur/commentaire?id='.$_POST['joueurId']);
} else {
    header('Location: /joueur');
}