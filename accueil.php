<?php
include 'config.php'; // Inclure le fichier de configuration de la base de données

// Récupérer les réclamations depuis la base de données
$stmt = $conn->query("SELECT * FROM reclamations");
$reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


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
      <!-- Bouton pour la recherche de client -->
      <button onclick="toggleSearchClient()">Recherche un Client</button>

<!-- Bouton pour ajouter une réclamation -->
<button onclick="toggleAddClaim()">Ajouter une Réclamation</button>

<!-- Tableau pour afficher les réclamations -->
<!-- Tableau pour afficher les réclamations -->
<div class="section">
    <h2>Réclamations</h2>
    <table>
        <thead>
            <tr>
                <th>Nom et Prénom du Client</th>
                <th>Date de Réclamation</th>
                <th>Adresse</th>
                <th>Description</th>
                <th>Équipe d'Intervention</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Trier les réclamations par date de réclamation
            usort($reclamations, function($a, $b) {
                return strtotime($b['date_reclamation']) - strtotime($a['date_reclamation']);
            });

            // Afficher les réclamations triées
            foreach ($reclamations as $reclamation) {
            ?>
                <tr>
                    <td><?php echo $reclamation['nom_prenom_client']; ?></td>
                    <td><?php echo $reclamation['date_reclamation']; ?></td>
                    <td><?php echo $reclamation['adresse']; ?></td>
                    <td><?php echo $reclamation['description']; ?></td>
                    <td><?php echo $reclamation['equipe_intervention']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



<!-- Interface pour la recherche de client -->
<div id="searchClient" class="overlay">
<div class="popup">
    <h2>Recherche de Client</h2>
    <form action="recherche_client.php" method="post">
        <label for="client_ref">Référence un Client:</label>
        <input type="text" id="client_ref" name="client_ref" required>
        <button type="submit">Rechercher</button>
    </form>
    <button onclick="toggleSearchClient()">Fermer</button>
</div>
</div>

<!-- Interface pour ajouter une réclamation -->
<div id="addClaim" class="overlay">
    <div class="popup">
        <h2>Ajouter une Réclamation</h2>
        <form action="ajouter_reclamation.php" method="post">
            <div class="form-group">
                <label for="client_name">Nom et Prénom du Client:</label>
                <input type="text" id="client_name" name="client_name" required>
            </div>
            <div class="form-group">
                <label for="reclamation_date">Date de Réclamation:</label>
                <input type="date" id="reclamation_date" name="reclamation_date" required>
            </div>
            <div class="form-group">
                <label for="client_address">Adresse:</label>
                <input type="text" id="client_address" name="client_address" required>
            </div>
            <div class="form-group">
                <label for="equipe_intervention">Équipe d'Intervention:</label>
                <input type="text" id="equipe_intervention" name="equipe_intervention" required>
            </div>
            <div class="form-group">
                <label for="reclamation_description">Description de la Réclamation:</label>
                <textarea id="reclamation_description" name="reclamation_description" rows="4" required></textarea>
            </div>
            <button type="submit">Soumettre Réclamation</button>
        </form>
        <button onclick="toggleAddClaim()">Fermer</button>
    </div>
</div>


<script>
// Fonction pour afficher ou masquer l'interface de recherche de client
function toggleSearchClient() {
    var overlay = document.getElementById('searchClient');
    overlay.style.display = overlay.style.display === 'none' ? 'block' : 'none';
}

// Fonction pour afficher ou masquer l'interface d'ajout de réclamation
function toggleAddClaim() {
    var overlay = document.getElementById('addClaim');
    overlay.style.display = overlay.style.display === 'none' ? 'block' : 'none';
}
</script>


</body>
</html>
