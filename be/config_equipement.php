<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location: connexion.php");
    exit();
}

$idEquipement = $_GET['id'];

try {
    $equipementSql = "SELECT * FROM Equipement WHERE idEquipement = ? AND nomUtilisateurEquipement = ?";
    $equipementStmt = $pdo->prepare($equipementSql);
    $equipementStmt->execute([$idEquipement, $_SESSION['user']]);
    $equipement = $equipementStmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipement) {
        header("Location: liste_equipements.php");
        exit();
    }

    $interfacesSql = "SELECT * FROM Interface WHERE idEquipementInterface = ?";
    $interfacesStmt = $pdo->prepare($interfacesSql);
    $interfacesStmt->execute([$idEquipement]);
    $interfaces = $interfacesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Configuration de l'équipement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f9;
            color: #333;
        }
        h2, h3 {
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
        p, form {
            margin: 20px 0;
        }
        input, button {
            margin-top: 8px;
            padding: 10px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        form br {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Configuration de l'équipement : <?= htmlspecialchars($equipement['nomeq'] ?? '') ?></h2>
    <?php if (!empty($error)) { echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>'; } ?>

    <h3>Interfaces existantes</h3>
    <?php if (!empty($interfaces)): ?>
        <ul>
            <?php foreach ($interfaces as $interface): ?>
                <li>
                    <?= htmlspecialchars($interface['adresseipinterface'] ?? 'Non spécifiée') ?>, 
                    <?= htmlspecialchars($interface['passerelleinterface'] ?? 'Non spécifiée') ?>, 
                    MAC: <?= htmlspecialchars($interface['macinterface'] ?? 'Non spécifiée') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune interface trouvée pour cet équipement.</p>
    <?php endif; ?>

    <h3>Configuration de l'interface réseau</h3>
    <form method="post" action="gestion_interface.php">
        <input type="hidden" name="idEquipement" value="<?= htmlspecialchars($equipement['idequipement'] ?? '') ?>">
        Adresse IP: <input type="text" name="adresseIP" required placeholder="192.168.1.5"><br>
        Masque de sous-réseau: <input type="text" name="masqueSubnet" required placeholder="255.255.255.0"><br>
        <?php if ($equipement['typeequipement'] === 'Ordinateur'): ?>
            Passerelle par défaut: <input type="text" name="passerelle" placeholder="192.168.1.1"><br>
        <?php endif; ?>
        <button type="submit">Configurer l'interface</button>
    </form>

    <?php if ($equipement['typeequipement'] === 'Routeur'): ?>
        <h3>Configuration de la table de routage</h3>
        <form method="post" action="gestion_route.php">
            <input type="hidden" name="idEquipement" value="<?= htmlspecialchars($equipement['idequipement'] ?? '') ?>">
            Destination (CIDR): <input type="text" name="destination" required placeholder="192.168.2.0/24"><br>
            Prochain saut (IP de la passerelle): <input type="text" name="nextHop" required placeholder="192.168.1.1"><br>
            <button type="submit">Ajouter une route</button>
        </form>
    <?php endif; ?>
</body>
</html>
