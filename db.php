<?php
// db.php : Connexion PDO sécurisée à la base MySQL
$host = 'localhost';
$db   = 'gestion_tech';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // gestion erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch associatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // native prepares
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Erreur connexion BDD : '.$e->getMessage());
}
