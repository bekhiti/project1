<?php
session_start(); // Démarrer la session
include 'config.php'; // Inclure le fichier de configuration de la base de données

// Vérification des identifiants
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête SQL pour vérifier les identifiants
    $sql = "SELECT * FROM utilisateurs WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si l'utilisateur existe et si le mot de passe correspond
    if ($user) {
        // Authentification réussie, enregistrer le nom d'utilisateur dans la session
        $_SESSION['username'] = $username;
        // Rediriger vers la page d'accueil
        header("Location: accueil.php");
        exit();
    } else {
        // Authentification échouée, afficher un message d'erreur
        echo "Identifiants invalides. Veuillez réessayer.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-box">
        <img src="images/Sonlgaz.png" class="logo" alt="Logo de l'entreprise">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <div class="textbox">
                <input type="text" placeholder="Nom d'utilisateur" name="username" required>
            </div>
            <div class="textbox">
                <input type="password" placeholder="Mot de passe" name="password" required>
            </div>
            <input type="submit" class="btn" value="Login">
        </form>
        <?php if (isset($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
