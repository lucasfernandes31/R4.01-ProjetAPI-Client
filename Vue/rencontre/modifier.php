<h1>Modifier une rencontre</h1>

<?php

use R301\Modele\Rencontre\RencontreLieu;
use R301\Vue\Component\Formulaire;

$urlAPI = "http://localhost:8081/rencontre";

if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_GET['id'])
        && isset($_POST['dateHeure'])
        && isset($_POST['equipeAdverse'])
        && isset($_POST['adresse'])
        && isset($_POST['lieu'])
) {
    // On crée d'abord le contenu JSON
    $data=json_encode([
        'rencontreId'=>$_GET['id'],
        'dateHeure'=>$_POST['dateHeure'],
        'equipeAdverse'=>$_POST['equipeAdverse'],
        'adresse'=>$_POST['adresse'],
        'lieu'=>$_POST['lieu']]);

    // Ensuite on crée le "stream context", autrement dit les paramètres de la requête et son contenu
    $context=stream_context_create([
        'http'=>[
            'method'=>'PUT',
            'header'=>'Content-Type: application/json',
            'content'=>$data,
            'ignore_errors'=>true//pour que ça plante pas automatiquement
        ]
    ]);

    $response= file_get_contents($urlAPI,false,$context);

    $responseTab=json_decode($response,true);

    if($responseTab['status_code']==200){
        $_SESSION['success'] = "La rencontre a bien été modifiée.";
        header('Location: /rencontre');
    }else{
        $_SESSION['error'] = "La modification a échouée.";
        error_log("Erreur lors de la mise à jour de la rencontre.");
    }
} else {
    if (!isset($_GET['id'])) {
        header("Location: /rencontre");
    } else {
        $urlAPI = $urlAPI . "/" . $_GET['id'];
        $response = file_get_contents($urlAPI);
        $responseTab = json_decode($response, true);
        $rencontre = $responseTab['data'];
        $dateFormatee = DateTime::createFromFormat('d/m/Y H:i', $rencontre['dateEtHeure'])
                         ->format('Y-m-d\TH:i');

        $formulaire = new Formulaire("/rencontre/modifier?id=" . $rencontre['rencontreId']);
        $formulaire->setDateTime("Date", "dateHeure", date("Y-m-d\TH:i"), $dateFormatee);
        $formulaire->setText("Equipe adverse", "equipeAdverse", "", $rencontre['equipeAdverse']);
        $formulaire->setText("Adresse", "adresse", "", $rencontre['adresse']);
        $formulaire->setSelect("Lieu", array_map(function (RencontreLieu $lieu) {
            return $lieu->name;
        }, RencontreLieu::cases()), "lieu", $rencontre['lieu']);
        $formulaire->addButton("Submit", "update", "Valider", "Modifier");
        echo $formulaire;
    }
}