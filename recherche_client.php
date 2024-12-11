<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
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

<button onclick="location.href='accueil.php'">Retour à l'accueil</button>



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
            echo "<p><strong>Réference:</strong> " . htmlspecialchars($row["reference"]). "</p>";
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
                echo '<table>';
                echo '<thead><tr><th>Numéro de Facture</th><th>Montant</th><th>Date de facture</th><th>État</th></tr></thead>';
                echo '<tbody>';
                foreach ($invoices as $invoice) {
                    // Déterminer la couleur en fonction de l'état de la facture
                    $color = ($invoice['etat_facture'] == 'payée') ? 'green' : 'red';
                    echo "<tr>";
                    echo "<td>" . $invoice['numero'] . "</td>";
                    echo "<td>" . $invoice['montant'] . "</td>";
                    echo "<td>" . $invoice['date_facture'] . "</td>";
                    echo "<td style='color: $color;'>" . ucfirst($invoice['etat_facture']) . "</td>";
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo "Aucune facture trouvée pour ce client.";
            }
            
            echo '</div>';
        }
    } else {
        echo '<script>alert("Aucun client trouvé avec cette référence.")</script>';
    }
} else {
    echo "Veuillez fournir une référence client valide.";
}
?>

</body>
</html>