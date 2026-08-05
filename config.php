<?php

// ce fichier contient les infos de connexion à la base de données
define ('DB_HOST', 'localhost');
define ('DB_NAME', 'web_blog');
define ('DB_USER', 'root');
define ('DB_PASSWORD', 'Daniel12349');

try {
    $pdo = new PDO (
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>