<h1>Modifier un joueur</h1>
<?php

use R301\Modele\Joueur\JoueurStatut;
use R301\Vue\Component\Formulaire;

$urlAPI = 'http://localhost:8081/joueur';

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_GET['id'])
    && isset($_POST['nom'])
    && isset($_POST['prenom'])
    && isset($_POST['numeroDeLicence'])
    && isset($_POST['dateDeNaissance'])
    && isset($_POST['tailleEnCm'])
    && isset($_POST['poidsEnKg'])
    && isset($_POST['statut'])
) {

    // Inclusion de l'ID dans l'URL
    $urlAPI = $urlAPI . "?id=" . $_GET['id'];

    // Création du contenu envoyé
    $data = json_encode([
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'numeroDeLicence' => $_POST['numeroDeLicence'],
        'dateDeNaissance' => $_POST['dateDeNaissance'],
        'tailleEnCm' => $_POST['tailleEnCm'],
        'poidsEnKg' => $_POST['poidsEnKg'],
        'statut' => $_POST['statut']
    ]);

    // Création du stream_context (methode PUT)
    $context = stream_context_create([
        'http' => [
            'method' => 'PUT',
            'header' => 'Content-type: application/json',
            'content' => $data,
            'ignore_errors' => true
        ]
    ]);


    // Envoi de la requête et récupération de la réponse
    $response = file_get_contents($urlAPI, false, $context);
    $responseTab = json_decode($response, true);

    if ($responseTab['status_code'] === 200) {
        header('Location: /joueur');
    }else{
        echo "Erreur lors de la modification du joueur";
        error_log("Erreur lors de la modification du joueur");
    }
} else {
    if (!isset($_GET['id'])) {
        header("Location: /joueur");
    } else {

        // Inclusion de l'ID dans l'URL
        $urlAPI = $urlAPI . "?id=" . $_GET['id'];

        // Envoi de la requête GET
        $response = file_get_contents($urlAPI);
        $responseTab = json_decode($response, true);

        if($responseTab['status_code'] === 200){

            $joueur = $responseTab['data'];

            $formulaire = new Formulaire("/joueur/modifier?id=".$joueur['joueur_id']);
            $formulaire->setText("Nom", "nom", "", $joueur['nom']);
            $formulaire->setText("Prenom", "prenom", "", $joueur['prenom']);
            $formulaire->setText("Numéro de license", "numeroDeLicence", "00042", $joueur['numero_licence']);
            $formulaire->setDate("Date de naissance", "dateDeNaissance", $joueur['date_naissance']);
            $formulaire->setText("Taille (en cm)", "tailleEnCm", "", $joueur['taille']);
            $formulaire->setText("Poids (en Kg)", "poidsEnKg", "", $joueur['poids']);
            $formulaire->setSelect("Statut", array_map(function($statut) { return $statut->name; }, JoueurStatut::cases()), "statut");
            $formulaire->addButton("Submit", "update", "modifier","Modifier");
            echo $formulaire;
        } else {
            echo "Erreur lors de la récupération du joueur";
            error_log("Erreur lors de la récupération du joueur");
        }
    }
}