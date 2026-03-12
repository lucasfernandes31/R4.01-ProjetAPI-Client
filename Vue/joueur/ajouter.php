<h1>Ajouter un joueur</h1>
<?php


use R301\Modele\Joueur\JoueurStatut;
use R301\Vue\Component\Formulaire;


$urlAPI = "http://localhost:8081/joueur";



if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['nom'])
    && isset($_POST['prenom'])
    && isset($_POST['numeroDeLicence'])
    && isset($_POST['dateDeNaissance'])
    && isset($_POST['tailleEnCm'])
    && isset($_POST['poidsEnKg'])
    && isset($_POST['statut'])
) {
    
    // On crée d'abord le contenu JSON
    $data = json_encode([
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'numeroDeLicence' => $_POST['numeroDeLicence'],
        'dateDeNaissance' => $_POST['dateDeNaissance'],
        'tailleEnCm' => $_POST['tailleEnCm'],
        'poidsEnKg' => $_POST['poidsEnKg'],
        'statut' => $_POST['statut']
    ]);

    // Ensuite on crée le "stream context", autrement dit les parametres de la requete et son contenu
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $data,
            'ignore_errors' => true  // ON MET IGNORE_ERRORS pour pouvoir gérer nous même l'erreur et que ça plante pas automatiquement
        ]
    ]);

    // Enfin on lance avec file_gets_contents (le deuxieme parametre : use include path, on veut pas donc on met non pour les TPs et exos)
    $response = file_get_contents($urlAPI, false, $context);

    // On convertit en tab pour avoir accès au status_code et aux autres infos si besoin
    $responseTab = json_decode($response, true);

    if ($responseTab['status_code'] == 201) {
        header('Location: /joueur');
    } else {
        echo "Erreur lors de la création du joueur.";
        error_log("Erreur lors de la création du joueur");
    }
} else {
    $formulaire = new Formulaire("/joueur/ajouter");
    $formulaire->setText("Nom", "nom");
    $formulaire->setText("Prenom", "prenom");
    $formulaire->setText("Numéro de license", "numeroDeLicence", "00042");
    $formulaire->setDate("Date de naissance", "dateDeNaissance");
    $formulaire->setText("Taille (en cm)", "tailleEnCm");
    $formulaire->setText("Poids (en kg)", "poidsEnKg");
    $formulaire->setSelect("Statut", array_map(function($statut) { return $statut->name; } ,JoueurStatut::cases()), "statut");
    $formulaire->addButton("Submit", "create", "valider", "Valider");
    echo $formulaire;
}