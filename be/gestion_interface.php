<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idEquipement']) && isset($_POST['adresseIP']) && isset($_POST['masqueSubnet'])) {
    $idEquipement = $_POST['idEquipement'];
    $adresseIP = trim($_POST['adresseIP']);
    $masqueSubnet = trim($_POST['masqueSubnet']);
    $adresseComplète = $adresseIP . '/' . $masqueSubnet; // Combine l'adresse IP et le masque
    $passerelle = isset($_POST['passerelle']) ? trim($_POST['passerelle']) : null;

    
    try {
        $sql = "INSERT INTO Interface (idEquipementInterface, adresseIPInterface, passerelleInterface) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idEquipement, $adresseComplète, $passerelle]);

        // Redirection vers la page de profil ou de liste des équipements avec un message de succès
        $_SESSION['message'] = "Interface ajoutée avec succès.";
        header("Location: liste_equipements.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout de l'interface : " . $e->getMessage();
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
    <h2>Erreur lors de l'ajout de l'interface</h2>
    <?php if (!empty($error)) { echo '<p style="color:red;">' . $error . '</p>'; } ?>
    <a href="liste_equipements.php">Retour à la liste des équipements</a>
</body>
</html>
