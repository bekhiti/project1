<?php
$servername = "localhost"; // ou l'adresse de votre serveur MySQL
$username = "root"; // Votre nom d'utilisateur MySQL
$password = ""; // Votre mot de passe MySQL
$dbname = "sonelgaz"; // Le nom de votre base de données

// Créer une connexion
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// Définir le mode d'erreur PDO sur Exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
