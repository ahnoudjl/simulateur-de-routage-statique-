<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nomEq']) && !empty($_POST['typeEquipement'])) {
    $nomEq = trim($_POST['nomEq']);
    $typeEquipement = trim($_POST['typeEquipement']);
    $nomUtilisateur = $_SESSION['user'];  // Nom d'utilisateur depuis la session

    try {
        $sql = "INSERT INTO Equipement (nomEq, typeEquipement, nomUtilisateurEquipement) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nomEq, $typeEquipement, $nomUtilisateur]);

        // Rediriger à la page de gestion des équipements ou au profil
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout de l'équipement : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un équipement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
            text-align: center;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        p.error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter un équipement</h2>
        <?php if (!empty($error)) { echo '<p class="error">' . $error . '</p>'; } ?>
        <form action="ajout_equipement.php" method="post">
            <label for="nomEq">Nom de l'équipement:</label>
            <input type="text" id="nomEq" name="nomEq" required><br>
            <label for="typeEquipement">Type d'équipement:</label>
            <select id="typeEquipement" name="typeEquipement" required>
                <option value="Routeur">Routeur</option>
                <option value="Ordinateur">Ordinateur</option>
            </select><br>
            <input type="submit" value="Ajouter l'équipement">
        </form>
    </div>
</body>
</html>
