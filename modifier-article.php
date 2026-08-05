<?php
require_once 'config.php';
require_once 'functions.php';

// 1. Valider et récupérer l'ID de l'article depuis l'URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

// 2. Récupérer les données actuelles de l'article pour pré-remplir le formulaire
$stmt = $pdo->prepare("SELECT a.*, c.nom AS categorie_nom FROM articles a JOIN categories c ON a.categorie_id = c.id WHERE a.id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

// Si l'article n'existe pas, rediriger
if (!$article) {
    header("Location: index.php");
    exit;
}

// Initialisation des variables
$erreurs = [];
$titre = $article['titre'];
$categorie = $article['categorie_nom'];
$contenu = $article['contenu'];
$tags = $article['tags'];

// 3. Traiter le formulaire lorsqu'il est soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nettoyer et récupérer les données du formulaire
    $titre     = trim($_POST["titre"] ?? '');
    $categorie = trim($_POST["categorie"] ?? '');
    $contenu   = trim($_POST["contenu"] ?? '');
    $tags      = trim($_POST["tags"] ?? '');

    // Validation (similaire à ajouter-article.php)
    if (strlen($titre) < 5) {
        $erreurs["titre"] = "Le titre doit faire au moins 5 caractères.";
    }
    if (empty($categorie)) {
        $erreurs["categorie"] = "Veuillez choisir une catégorie.";
    }
    if (strlen($contenu) < 50) {
        $erreurs["contenu"] = "Le contenu doit faire au moins 50 caractères.";
    }

    // S'il n'y a pas d'erreurs, procéder à la mise à jour
    if (count($erreurs) === 0) {
        // Récupérer l'ID de la catégorie à partir de son nom
        $stmtCat = $pdo->prepare("SELECT id FROM categories WHERE nom = ?");
        $stmtCat->execute([$categorie]);
        $categorieId = $stmtCat->fetchColumn();

        // Requête de mise à jour
        $sql = "UPDATE articles SET titre = ?, contenu = ?, categorie_id = ?, tags = ? WHERE id = ?";
        $stmtUpdate = $pdo->prepare($sql);
        $stmtUpdate->execute([$titre, $contenu, $categorieId, $tags, $id]);

        // Rediriger vers la page d'accueil après la mise à jour
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>WebBlog — Modifier un article</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un article</h1>

        <form method="POST" action="modifier-article.php?id=<?= $id ?>" class="mt-4">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($titre) ?>">
                <?php if (isset($erreurs["titre"])) : ?>
                    <div class="text-danger mt-1"><?= $erreurs["titre"] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select name="categorie" class="form-select">
                    <option value="" <?= $categorie === "" ? "selected" : "" ?>>-- Choisir --</option>
                    <option value="Technologie" <?= $categorie === "Technologie" ? "selected" : "" ?>>Technologie</option>
                    <option value="Voyage" <?= $categorie === "Voyage" ? "selected" : "" ?>>Voyage</option>
                    <option value="Cuisine" <?= $categorie === "Cuisine" ? "selected" : "" ?>>Cuisine</option>
                    <option value="Lifestyle" <?= $categorie === "Lifestyle" ? "selected" : "" ?>>Lifestyle</option>
                </select>
                <?php if (isset($erreurs["categorie"])) : ?>
                    <div class="text-danger mt-1"><?= $erreurs["categorie"] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Contenu</label>
                <textarea name="contenu" class="form-control" rows="6"><?= htmlspecialchars($contenu) ?></textarea>
                <?php if (isset($erreurs["contenu"])) : ?>
                    <div class="text-danger mt-1"><?= $erreurs["contenu"] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Tags (séparés par des virgules)</label>
                <input type="text" name="tags" class="form-control" value="<?= htmlspecialchars($tags) ?>">
                <?php if (isset($erreurs["tags"])) : ?>
                    <div class="text-danger mt-1"><?= $erreurs["tags"] ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn">Mettre à jour</button>
            <a href="/" style="margin-left: 10px;">Annuler</a>
        </form>
    </div>

</body>
</html>