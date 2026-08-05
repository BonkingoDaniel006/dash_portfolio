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


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>WebBlog — Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Mon Super Blog</h1>
        <a href="/ajouter-article.php" class="btn">Ajouter un article</a>
        <hr>
        <?php foreach ($articles as $a) : ?>
            <article>
                <h2><?= htmlspecialchars($a['titre']) ?></h2>
                <p>Publié le <?= formaterDate($a['date_publication']) ?> par <?= htmlspecialchars($a['auteur_name']) ?> dans la catégorie <?= htmlspecialchars($a['nom']) ?></p>
                <div><?= genererExtrait(nl2br(htmlspecialchars($a['contenu'])), 200) ?></div>
                <a href="/article.php?id=<?= $a['id'] ?>">Lire la suite</a>
                <a href="#" class="delete-link" data-id="<?= $a['id'] ?>" style="color: #dc3545; margin-left: 10px;">Supprimer</a>
                <a href="/modifier-article.php?id=<?= $a['id'] ?>" style="color: #007bff; margin-left: 10px;">Modifier</a>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Modale de confirmation de suppression -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Confirmer la suppression</h2>
            <p>Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.</p>
            <div class="modal-actions">
                <button id="cancelDelete" class="btn btn-secondary">Annuler</button>
                <button id="confirmDelete" class="btn btn-danger">Confirmer</button>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const closeButton = document.querySelector('.close-button');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDelete = document.getElementById('confirmDelete');
        const deleteLinks = document.querySelectorAll('.delete-link');
        let articleIdToDelete = null;
        let articleElementToDelete = null;

        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                articleIdToDelete = this.dataset.id;
                articleElementToDelete = this.closest('article');
                deleteModal.style.display = 'block';
            });
        });

        const closeModal = () => deleteModal.style.display = 'none';
        closeButton.addEventListener('click', closeModal);
        cancelDelete.addEventListener('click', closeModal);
        window.addEventListener('click', (e) => e.target === deleteModal ? closeModal() : null);

        confirmDelete.addEventListener('click', function() {
            fetch(`/supprimer.php?id=${articleIdToDelete}`, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        articleElementToDelete.remove();
                    } else {
                        alert(data.message || 'Une erreur est survenue.');
                    }
                    closeModal();
                });
        });
    </script>
</body>
</html>