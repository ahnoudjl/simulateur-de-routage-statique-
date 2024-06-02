<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idEquipement']) && isset($_POST['destination']) && isset($_POST['nextHop'])) {
    $idEquipement = $_POST['idEquipement'];
    $destination = trim($_POST['destination']);
    $nextHop = trim($_POST['nextHop']);

    try {
        $sql = "INSERT INTO Route (idEquipementRoute, reseauDestRoute, prochainSautRoute) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idEquipement, $destination, $nextHop]);

        // Redirection vers la page de profil ou de liste des équipements avec un message de succès
        $_SESSION['message'] = "Route ajoutée avec succès.";
        header("Location: liste_equipements.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout de la route : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur</title>
</head>
<body>
    <h2>Erreur lors de l'ajout de la route</h2>
    <?php if (!empty($error)) { echo '<p style="color:red;">' . $error . '</p>'; } ?>
    <a href="liste_equipements.php">Retour à la liste des équipements</a>
</body>
</html>
