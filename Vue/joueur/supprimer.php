<?php

$urlAPI = "http://localhost:8081/joueur";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        
        // Inclusion de l'ID dans l'URL
        $urlAPI = $urlAPI . "?id=" . $_POST['id'];

        // Création du contexte (méthode DELETE)
        $context = stream_context_create([
            'http' => [
                'method' => 'DELETE',
                'header' => 'Content-Type: application/json',
                'ignore_errors' => true
            ]
        ]);

        // Envoi de la requête et récupération de la réponse
        $response = file_get_contents($urlAPI, false, $context);
        var_dump($response);      // voir la réponse brute
        $responseTab = json_decode($response, true);

        // Vérification du status_code retourné
        if ($responseTab['status_code'] !== 200) {
            echo "Erreur lors de la suppression du joueur";
            error_log("Erreur lors de la suppression du joueur");
        }
    }
}

header('Location: /joueur');