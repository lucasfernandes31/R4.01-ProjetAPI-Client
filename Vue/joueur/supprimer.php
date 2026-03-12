<?php

$urlAPI = "http://localhost:8081/joueur";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        
        $urlAPI = $urlAPI . "?id=" . $_POST['id'];

        $response = file_get_contents($urlAPI);

        $responseTab = json_decode($response, true);

        if (!$responseTab['status_code'] == 200) {
            echo "Erreur lors de la suppression du joueur";
            error_log("Erreur lors de la suppression du joueur");
        }
    }
}

header('Location: /joueur');