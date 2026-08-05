<?php
require_once 'config.php';
require_once 'functions.php';

$sql = "DELETE FROM articles  WHERE id = ?";
$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([$_GET['id']]);
    $message = "Article supprimé avec succès.";
} catch (PDOException $e) {
    die('Erreur lors de la suppression de l\'article : ' . $e->getMessage());
}

?>

<h1><?= $message ?></h1>