<h1>Ajouter une rencontre</h1>

<?php

use R301\Modele\Rencontre\RencontreLieu;
use R301\Vue\Component\Formulaire;

$urlAPI = "http://localhost:8081/rencontre";


if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['dateHeure'])
        && isset($_POST['equipeAdverse'])
        && isset($_POST['adresse'])
        && isset($_POST['lieu'])
) {
    // On crée d'abord le contenu JSON
    $data=json_encode([
        'dateHeure'=>$_POST['dateHeure'],
        'equipeAdverse'=>$_POST['equipeAdverse'],
        'adresse'=>$_POST['adresse'],
        'lieu'=>$_POST['lieu']]);

    // Ensuite on crée le "stream context", autrement dit les paramètres de la requête et son contenu
    $context=stream_context_create([
        'http'=>[
            'method'=>'POST',
            'header'=>'Content-Type: application/json',
            'content'=>$data,
            'ignore_errors'=>true//pour que ça plante pas automatiquement
        ]
    ]);

    $response= file_get_contents($urlAPI,false,$context);
    var_dump($response);
      var_dump(DateTime::createFromFormat('Y-m-d\TH:i', '2026-03-26T14:00'));

    $responseTab=json_decode($response,true);

    if($responseTab['status_code']==201){
        header('Location: /rencontre');
    }else{
        echo($responseTab['status_message']);
        error_log("Erreur lors de la création du rencontre");
    }
} else {
    $formulaire = new Formulaire("/rencontre/ajouter");
    $formulaire->setDateTime("Date", "dateHeure", date("Y-m-d H:i"));
    $formulaire->setText("Equipe adverse", "equipeAdverse");
    $formulaire->setText("Adresse", "adresse");
    $formulaire->setSelect("Lieu", array_map(function(RencontreLieu $lieu) { return $lieu->name; }, RencontreLieu::cases()), "lieu");
    $formulaire->addButton("Submit", "create", "Valider", "Modifier");
    echo $formulaire;
}