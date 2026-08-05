<?php
require_once 'config.php';

// Définir le header pour une réponse JSON
header('Content-Type: application/json');

// S'assurer que la méthode est POST pour plus de sécurité
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID invalide ou manquant.']);
    exit;
}

$sql = "DELETE FROM articles WHERE id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de l\'article.']);
}