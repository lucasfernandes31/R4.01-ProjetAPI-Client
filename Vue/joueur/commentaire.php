<?php

use R301\Vue\Component\Formulaire;

$urlAPIJoueur = 'http://localhost:8081/joueur';
$urlAPICommentaire = 'http://localhost:8081/commentaire';

if (!isset($_GET['id'])) {
    header('Location: /joueur');
    die();
}

// Inclusion de l'ID dans l'URL
$urlAPIJoueur = $urlAPIJoueur . "?id=" . $_GET['id'];

// Envoi de la requête GET (pour récupérer le joueur)
$response = file_get_contents($urlAPIJoueur);
$responseTab = json_decode($response, true);

if($responseTab['status_code'] === 200){

    // Récupération du joueur
    $joueur = $responseTab['data'];

} else {
    echo "Erreur lors de la récupération du joueur";
    error_log("Erreur lors de la récupération du joueur");
}


?>

<h1>Commentaires de <?php echo $joueur['nom'] . " " . $joueur['prenom']; ?></h1>

<?php
$form = new Formulaire("commentaire/ajouter");
$form->addTextArea("contenu");
$form->addHiddenInput("joueurId", $_GET['id']);
$form->addButton("submit", "create", "Publier le commentaire", "Publier le commentaire");
echo $form;

// Construction URL pour récupérer les commentaires
$urlAPICommentaire = $urlAPICommentaire . "?joueurId=" . $joueur['joueur_id'];

// Requete GET
$response = file_get_contents($urlAPICommentaire);
$responseTab = json_decode($response, true);

$commentaires = $responseTab['data'];

usort($commentaires, function ($a, $b) { return $b['date'] <=> $a['date']; });

?>
<div class="container">
    <table>
        <tr>
            <th style="min-width: 100px; width: 1%">Date</th>
            <th style="width: 80%">Commentaire</th>
            <th style="width: 1%"></th>
        </tr>
        <?php foreach ($commentaires as $commentaire): ?>
        <form action="/joueur/commentaire/supprimer" method="post">
            <input type="hidden" name="commentaireId" value="<?php echo $commentaire['commentaire_id']; ?>" />
            <input type="hidden" name="joueurId" value="<?php echo $_GET['id']; ?>" />
            <tr>
                <td><?php echo $commentaire['date']; ?></td>
                <td><?php echo $commentaire['contenu']; ?></td>
                <td class="actions">
                    <button class="delete" type="submit">Supprimer</button>
                </td>
            </tr>
        </form>
        <?php endforeach; ?>
    </table>
</div>
