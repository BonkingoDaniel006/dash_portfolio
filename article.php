<?php
require_once 'config.php';
require_once 'functions.php';

// 1. Validation de l'ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

// 2. Requête préparée
$stmt = $pdo->prepare("SELECT a.*, u.auteur_name, c.nom AS categorie_nom
                        FROM articles a
                        LEFT JOIN utilisateur u ON a.auteur_id = u.id
                        LEFT JOIN categories c ON a.categorie_id = c.id
                        WHERE a.id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: index.php');
    exit;
}
?>

<h1><?= htmlspecialchars($article['titre']) ?></h1>
<p><em>Publié le <?= formaterDate($article['date_publication']) ?> par <?= htmlspecialchars($article['auteur_name']) ?> dans la catégorie <?= htmlspecialchars($article['categorie_nom']) ?></em></p>
<hr>
<p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>