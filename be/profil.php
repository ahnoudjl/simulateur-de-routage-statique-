<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 40px;
            text-align: center;
            background-image: linear-gradient(to bottom right, #e0eafc, #cfdef3);
        }
        .container {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            display: inline-block;
            margin-top: 50px;
        }
        a, button {
            font-size: 16px;
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        a:hover, button:hover {
            background-color: #45a049;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .welcome-message {
            margin-bottom: 20px;
            font-size: 22px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-message">
            Bienvenue, <?= htmlspecialchars($_SESSION['user']); ?>
        </div>
        <a href="deconnexion.php">Déconnexion</a><br><br>
        <button onclick="window.location.href='index.php';">Retour à l'accueil</button>
        <!-- Ajoutez ici plus de gestion du profil -->
    </div>
</body>
</html>
