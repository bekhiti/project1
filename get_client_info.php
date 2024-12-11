<?php
include 'config.php'; // Inclure le fichier de configuration de la base de données

// Vérifier si la référence client est fournie et est non vide
if(isset($_POST['client_ref']) && !empty($_POST['client_ref'])) {
    $client_ref = $_POST['client_ref'];
    
    // Utilisation d'une requête préparée pour éviter l'injection SQL
    $sql = "SELECT * FROM clients WHERE reference = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $client_ref);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Afficher les informations du client dans une interface utilisateur
        while($row = $result->fetch_assoc()) {
            echo "Nom: " . htmlspecialchars($row["nom"]). "<br>";
            echo "Prénom: " . htmlspecialchars($row["prenom"]). "<br>";
            echo "Adresse: " . htmlspecialchars($row["adresse"]). "<br>";
            echo "Numéro de compteur: " . htmlspecialchars($row["num_compteur"]). "<br>";
            // Afficher d'autres informations du client selon les besoins
        }
    } else {
        echo "<script>alert("Aucun client trouvé avec cette référence.")</script>";
    }

    // Fermer la requête préparée
    $stmt->close();
} else {
    echo '<script>alert("Aucun client trouvé avec cette référence.")</script>';
}
?>
