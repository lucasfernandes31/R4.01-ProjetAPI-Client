<?php

$urlAPIJoueur = 'http://localhost:8081/joueur';
$urlAPIStatistiques = 'http://localhost:8081/statistiques';


//////////
// Récupération de la liste des statistiques de l'équipe
$response = file_get_contents($urlAPIStatistiques."?equipe=1");
$responseTab = json_decode($response, true);

if($responseTab['status_code'] !== 200){
    echo "Erreur lors de la récupération des statistiques de l'équipe";
    error_log("Erreur lors de la récupération des statistiques de l'équipe");
    exit();
}

// Stockage des infos de l'équipe
$statistiquesEquipe = $responseTab['data'];


//////////
// Récupération de la liste des statistiques de l'équipe
$response = file_get_contents($urlAPIStatistiques."?joueurs=1");
$responseTab = json_decode($response, true);

if($responseTab['status_code'] !== 200){
    echo "Erreur lors de la récupération des statistiques des joueurs";
    error_log("Erreur lors de la récupération des statistiques des joueurs");
    exit();
}

// Stockage des infos de l'équipe
$statistiquesJoueurs = $responseTab['data'];


//////////
// Récupération de la liste de tous les joueurs (GET)
$response = file_get_contents($urlAPIJoueur);
$responseTab = json_decode($response, true);

if($responseTab['status_code'] !== 200){
    echo "Erreur lors de la récupération des joueurs";
    error_log("Erreur lors de la récupération des joueurs");
    exit();
}

// Stockage des infos des joueurs
$joueurs = $responseTab['data'];


?>

<div class="TripleGrid">
    <div>
        <h1><?php echo $statistiquesEquipe['nbVictoires']; ?></h1>
        <p> matchs gagnés</p>
    </div>
    <div>
        <h1><?php echo $statistiquesEquipe['nbNuls']; ?></h1>
        <p> matchs nuls</p>
    </div>
    <div>
        <h1><?php echo $statistiquesEquipe['nbDefaites']; ?></h1>
        <p> matchs perdus</p>
    </div>
    <div>
        <h1><?php echo $statistiquesEquipe['pourcentageDeVictoires']; ?>%</h1>
        <p> de matchs gagnés</p>
    </div>
    <div>
        <h1><?php echo $statistiquesEquipe['pourcentageDeNuls']; ?>%</h1>
        <p> de matchs nuls</p>
    </div>
    <div>
        <h1><?php echo $statistiquesEquipe['pourcentageDeDefaites']; ?>%</h1>
        <p> de matchs perdus</p>
    </div>
</div>
<div class="overflow">
    <table >
        <tr>
            <th style="width:15%;">Joueur</th>
            <th style="width:7%;">Statut</th>
            <th style="width:7%;">Poste le plus performant</th>
            <th style="width:7%;">Nombre de matchs consécutifs</th>
            <th style="width:7%;">Nombre titularisations</th>
            <th style="width:7%;">Nombre remplaçants</th>
            <th style="width:7%;">Moyenne évaluations</th>
            <th style="width:7%;">Pourcentage gagnés</th>
        </tr>
        <?php foreach ($joueurs as $joueur): 
        
                    // Boucle pour récupérer les statistiques du joueur
                    foreach ($statistiquesJoueurs as $statsJoueur):
                        if($statsJoueur['joueur_id'] === $joueur['joueur_id']){
                            $statsJoueurSelect = $statsJoueur;
                        }
                    endforeach;

        ?>
        <tr>
            <td><?php echo $joueur['numero_licence'] . " - " . $joueur['nom'] . " " . $joueur['prenom']; ?></td>
            <td><?php echo $joueur['statut']; ?></td>
            <td><?php echo $statsJoueurSelect['posteLePlusPerformant']; ?></td>
            <td><?php echo $statsJoueurSelect['nbRencontresConsecutives']; ?></td>
            <td><?php echo $statsJoueurSelect['nbTitularisations']; ?></td>
            <td><?php echo $statsJoueurSelect['nbRemplacant']; ?></td>
            <td><?php echo $statsJoueurSelect['moyenneDesEvaluations']; ?></td>
            <td><?php echo $statsJoueurSelect['pourcentageDeMatchsGagnes']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
