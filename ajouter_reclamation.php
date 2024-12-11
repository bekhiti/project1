<?php
include 'config.php'; // Inclure le fichier de configuration de la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $client_name = $_POST['client_name'];
    $reclamation_date = $_POST['reclamation_date'];
    $client_address = $_POST['client_address'];
    $equipe_intervention = $_POST['equipe_intervention'];
    $reclamation_description = $_POST['reclamation_description'];

    try {
        // Préparer la requête SQL d'insertion
        $sql = "INSERT INTO reclamations (nom_prenom_client, date_reclamation, adresse, equipe_intervention, description) 
                VALUES (:nom_prenom_client, :date_reclamation, :adresse, :equipe_intervention, :description)";
        $stmt = $conn->prepare($sql);

        // Liaison des paramètres
        $stmt->bindParam(':nom_prenom_client', $client_name);
        $stmt->bindParam(':date_reclamation', $reclamation_date);
        $stmt->bindParam(':adresse', $client_address);
        $stmt->bindParam(':equipe_intervention', $equipe_intervention);
        $stmt->bindParam(':description', $reclamation_description);

        // Exécution de la requête
        $stmt->execute();

        // Redirection vers la page d'accueil avec un message de succès
        header("Location: accueil.php?success=1");
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, afficher un message d'erreur et rediriger vers la page d'accueil
        echo "Erreur : " . $e->getMessage();
        header("Location: accueil.php?error=1");
        exit();
    }
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page d'accueil
    header("Location: accueil.php");
    exit();
}
?>
