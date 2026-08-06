<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// indiquer au navigateur que le contenu renvoyé est du JSON
header("Content-Type: application/json; charset=UTF-8");
//autoriser les requetes depuis le front-end(gestion du CORS)
header("Access-Control-Allow-Origin: *");



require_once 'config.php';
require_once 'functions.php';

try {
    //on recupère la liste des articles avec les informations de l'auteur et de la catégorie
    $sql = "SELECT a.id, a.titre, a.contenu, a.date_publication, 
               u.auteur_name, c.nom
        FROM articles a
        LEFT JOIN utilisateur u ON a.auteur_id = u.id
        LEFT JOIN categories c ON a.categorie_id = c.id ORDER BY a.date_publication DESC";
    $articles = $pdo->query($sql)->fetchAll();
    //reponse http 200(succès) + envoie du json
    http_response_code(200);
    echo json_encode($articles);

} catch (PDOException $e) {
    //en cas d'erreur BDD
    http_response_code(500);
    echo json_encode(["message"=> "Erreur de base de données"]);
}

