<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

try {
    $sql = "SELECT idEquipement, nomEq, typeEquipement FROM Equipement WHERE nomUtilisateurEquipement = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user']]);
    $equipements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des équipements : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des équipements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f9;
            color: #333;
        }
        h2 {
            color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        a {
            text-decoration: none;
            color: #0056b3;
        }
        a:hover {
            color: #002766;
        }
        p {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Liste des équipements</h2>
    <?php if (!empty($error)) { echo '<p>' . $error . '</p>'; } ?>
    <ul>
        <?php foreach ($equipements as $equipement): ?>
            <li>
                <a href="config_equipement.php?id=<?= $equipement['idequipement'] ?>">
                    <?= htmlspecialchars($equipement['nomeq']) ?> (<?= htmlspecialchars($equipement['typeequipement']) ?>)
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
