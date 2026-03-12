<?php

$urlAPI = 'http://localhost:8081/commentaire';

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['joueurId'])
    && isset($_POST['contenu'])
) {
    
    // Encodage en JSON des données à envoyer
    $data = json_encode([
        'joueurId' => $_POST['joueurId'],
        'contenu' => $_POST['contenu']
    ]);

    // Préparation du contexte (méthode POST + données)
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $data,
            'ignore_errors' => true
        ]
    ]);

    // Envoi de la requête et récupération du résultat
    $response = file_get_contents($urlAPI, false, $context);
    $responseTab = json_decode($response, true);

    if (!$responseTab['status_code'] === 200) {
        error_log("Erreur lors de la création du commentaire");
    }
}

if (isset($_POST['joueurId'])) {
    header('Location: /joueur/commentaire?id='.$_POST['joueurId']);
} else {
    header('Location: /joueur');
}