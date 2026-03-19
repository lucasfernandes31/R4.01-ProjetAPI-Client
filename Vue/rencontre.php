<?php

use R301\Vue\Component\SelectResultat;

$urlAPI = "http://localhost:8081/rencontre";

// Préparation de l'url pour appel à l'API
if (isset($_GET['recherche']) || isset($_GET['statut'])) {
    $url = $urlAPI . "/recherche/" . urlencode($_GET['recherche']) . "/" . urlencode($_GET['statut']);
} else {
    $url = $urlAPI;
}

// Message en fonction de si l'ajout du commentaire a marché ou non.
if (!empty($_SESSION['success'])) {
    echo '<script>alert("' . htmlspecialchars($_SESSION['success']) . '");</script>';
    unset($_SESSION['success']);
}

if (!empty($_SESSION['error'])) {
    echo '<script>alert("' . htmlspecialchars($_SESSION['error']) . '");</script>';
    unset($_SESSION['error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['action'])
        && isset($_POST['rencontreId'])
) {
    switch($_POST['action']) {
        case "ouvrirFeuilleDeMatch":
            header('Location: /feuilleDeMatch/feuilleDeMatch?id='.$_POST['rencontreId']);
            die();
        case "ouvrirEvaluations":
            header('Location: /feuilleDeMatch/evaluation?id='.$_POST['rencontreId']);
            die();
        case "modifier":
            header('Location: /rencontre/modifier?id='.$_POST['rencontreId']);
            die();
        case "enregistrerResultat":
            if (isset($_POST['resultat'])) {
                $data=json_encode([
                    'rencontreId'=>$_POST['rencontreId'],
                    'resultat'=>$_POST['resultat']
                ]);
                $context=stream_context_create([
                        'http'=>[
                            'method'=>'PUT',
                            'header'=>'Content-Type: application/json',
                            'content'=>$data,
                            'ignore_errors'=>true//pour que ça plante pas automatiquement
                        ]
                    ]);

                $response= file_get_contents($url,false,$context);

                $responseTab=json_decode($response,true);

                if($responseTab['status_code']==201){
                    $_SESSION['success'] = "Le résultat a bien été rempli.";
                    header('Location: /rencontre');
                }else{
                    $_SESSION['error'] = "L'ajout du résultat a échoué.";
                    error_log("Erreur lors de l'ajout du résultat.");                    
                }
                header('Location: /rencontre');
                die();
            }
            break;
        case "supprimer":
            $urlDelete = $urlAPI . "/" . $_POST['rencontreId']; // il faut changer l'url car pour modifier on met l'id dans le body donc on peut le récupérer par POST mais pour le supprimer c'est dans l'url donc on a pas le choix
            // Création du contexte (méthode DELETE)
            $context = stream_context_create([
                'http' => [
                    'method' => 'DELETE',
                    'ignore_errors' => true
                ]
            ]);
            $response = file_get_contents($urlDelete, false, $context);
            $responseTab = json_decode($response, true);

            if ($responseTab['status_code'] !== 200) {
                // Stocker l'erreur en session
                $_SESSION['error'] = "Impossible de supprimer la rencontre, car la date est dépassée.";
                error_log("Erreur lors de la suppression de la rencontre");
            } else {
                $_SESSION['success'] = "Rencontre supprimée avec succès";
            }
            header('Location: /rencontre');
            die();
    }
} else {

$response = file_get_contents($url);
$rencontres = json_decode($response, true)['data'];


?>
<h1>Rencontres</h1>
<div class="overflow container">
    <table>
        <tr>
            <th style="width:10%">Date</th>
            <th style="width:10%">Equipe Adverse</th>
            <th style="width:20%">Adresse</th>
            <th style="width:8%">Lieu</th>
            <th style="width:8%">Résultat</th>
            <th style="width:20%; min-width: 200px;">Actions</th>
        </tr>
        <?php foreach ($rencontres as $rencontre):

            $selectResultat = new SelectResultat(
                    null,
                    $rencontre['resultat']
            );
        ?>
        <form action="rencontre" method="post">
            <tr>
                <input type="hidden" name="rencontreId" value="<?php echo $rencontre['rencontreId'] ?>" />
                <td><?php echo $rencontre['dateEtHeure']?></td>
                <td><?php echo $rencontre['equipeAdverse'] ?></td>
                <td><?php echo $rencontre['adresse'] ?></td>
                <td><?php echo $rencontre['lieu'] ?></td>
                <?php if ((DateTime::createFromFormat('d/m/Y H:i', $rencontre['dateEtHeure'])<new DateTime()) && $rencontre['resultat'] ===null): ?>
                    <td><?php $selectResultat->toHTML(); ?></td>
                <?php else: ?>
                    <td><?php echo $rencontre['resultat'] ?></td>
                <?php endif; ?>
                <td class="actions">
                    <?php if (!(DateTime::createFromFormat('d/m/Y H:i', $rencontre['dateEtHeure'])<new DateTime())): ?>
                    <button name="action" value="ouvrirFeuilleDeMatch" class="info">Feuilles de match</button>
                    <button name="action" value="modifier" class="update">Modifier</button>
                    <button name="action" value="supprimer" class="delete">Supprimer</button>
                    <?php else: ?>
                    <button name="action" value="ouvrirEvaluations" class="info">Évaluations</button>
                    <?php if ((DateTime::createFromFormat('d/m/Y H:i', $rencontre['dateEtHeure'])<new DateTime()) && $rencontre['resultat'] ===null): ?>
                    <button class="create" name="action" value="enregistrerResultat">Enregistrer résultat</button>
                    <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </form>
        <?php endforeach; ?>
    </table>
</div>
<?php } ?>