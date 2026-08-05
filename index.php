<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'functions.php';


$sql = "SELECT a.id, a.titre, a.contenu, a.date_publication, 
               u.auteur_name, c.nom
        FROM articles a
        LEFT JOIN utilisateur u ON a.auteur_id = u.id
        LEFT JOIN categories c ON a.categorie_id = c.id ORDER BY a.date_publication DESC";
$articles = $pdo->query($sql)->fetchAll();

?>


<div>
    <a href="/ajouter.php">Ajouter un article</a>
    <?php foreach ($articles as $a) : ?>
        <article>
            <h2><?= htmlspecialchars($a['titre']) ?></h2>
            <p>Publié le <?= formaterDate($a['date_publication']) ?> par <?= htmlspecialchars($a['auteur_name']) ?> dans la catégorie <?= htmlspecialchars($a['nom']) ?></p>
            <div>
                <?= genererExtrait(nl2br(htmlspecialchars($a['contenu'])), 200) ?>
            </div>
            <a href="/article.php?id=<?= $a['id'] ?>"
                               class="btn btn-outline-primary btn-sm">Lire la suite</a>
        </article> <hr>
        
    <?php endforeach; ?>
</div>