<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="accueil.css">
</head>
<body>
<header>
    <div class="container">
        <img src="images/Sonlgaz.png" class="logo" alt="Logo de l'entreprise">
        <div class="user-info">
            <?php
            session_start();
            if (isset($_SESSION['username'])) {
                echo "<span>Bienvenue, " . $_SESSION['username'] . "</span>";
                // Flèche pour afficher/masquer le bouton de déconnexion
                echo '<span class="arrow" onclick="toggleLogout()">▼</span>';
                // Bouton de déconnexion (initiallement caché)
                echo '<a href="login.php" class="logout-btn" id="logoutBtn">Déconnexion</a>';
            } else {
                echo "<span>Bienvenue, visiteur</span>";
            }
            ?>
        </div>
    </div>
</header>

<!-- JavaScript pour afficher/masquer le bouton de déconnexion -->
<script>
    function toggleLogout() {
        var logoutBtn = document.getElementById('logoutBtn');
        logoutBtn.style.display = logoutBtn.style.display === 'none' ? 'inline-block' : 'none';
    }
</script>

<!-- Bouton pour la recherche de client -->
<button onclick="toggleSearchClient()">Recherche de Client</button>

<!-- Interface pour la recherche de client -->
<div id="searchClient" class="overlay">
    <div class="popup">
        <h2>Recherche de Client</h2>
        <form action="recherche_client.php" method="post">
            <label for="client_ref">Référence du Client:</label>
            <input type="text" id="client_ref" name="client_ref" required>
            <button type="submit">Rechercher</button>
        </form>
        <button onclick="toggleSearchClient()">Fermer</button>
    </div>
</div>

<?php
include 'config.php'; // Inclure le fichier de configuration de la base de données

// Vérifier si la référence client est fournie et est non vide
if(isset($_POST['client_ref']) && !empty($_POST['client_ref'])) {
    $client_ref = $_POST['client_ref'];
    
    // Utilisation d'une requête préparée avec PDO pour récupérer les informations du client
    $sql = "SELECT * FROM clients WHERE reference = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$client_ref]);
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($result) > 0) {
        // Afficher les informations du client dans une interface utilisateur
        foreach($result as $row) {
            echo '<div class="client-info">';
            echo "<h2>Informations du Client</h2>";
            echo "<p><strong>Nom:</strong> " . htmlspecialchars($row["nom"]). "</p>";
            echo "<p><strong>Prénom:</strong> " . htmlspecialchars($row["prenom"]). "</p>";
            echo "<p><strong>Adresse:</strong> " . htmlspecialchars($row["adresse"]). "</p>";
            echo "<p><strong>Numéro de compteur:</strong> " . htmlspecialchars($row["num_compteur"]). "</p>";
            // Afficher d'autres informations du client selon les besoins
            
            // Ajouter une partie pour afficher les factures du client
            echo "<h2>Factures du Client</h2>";
            $stmt = $conn->prepare("SELECT * FROM factures WHERE client_reference = ?");
            $stmt->execute([$client_ref]);
            $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($invoices) > 0) {
                echo "<ul>";
                foreach ($invoices as $invoice) {
                    echo "<li>Facture #" . $invoice['numero'] . " - Montant: " . $invoice['montant'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "Aucune facture trouvée pour ce client.";
            }
            
            echo '</div>';
        }
    } else {
        echo "Aucun client trouvé avec cette référence.";
    }
} else {
    echo "Veuillez fournir une référence client valide.";
}
?>

</body>
</html>
