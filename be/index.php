<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'accueil</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        nav {
            background-color: #555;
            padding: 10px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            padding: 8px 16px;
            border-radius: 5px;
            background-color: #666;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #777;
            text-decoration: none;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur notre site</h1>
    </header>
    <nav>
        <?php
        if (isset($_SESSION['user'])) {
            // L'utilisateur est connecté
            echo '<a href="profil.php">Profil</a>';
            echo '<a href="deconnexion.php">Déconnexion</a>';
            echo '<a href="ajout_equipement.php">Ajouter un équipement</a>';  // Lien pour ajouter un équipement
            echo '<a href="liste_equipements.php">Liste des équipements</a>';  // Lien pour la liste des équipements
            echo '<a href="visualisation_reseau.php">Visualisation du réseau</a>';  // Lien pour la visualisation du réseau
        } else {
            // L'utilisateur n'est pas connecté
            echo '<a href="inscription.php">Inscription</a>';
            echo '<a href="connexion.php">Connexion</a>';
        }
        ?>
    </nav>
    <main>
        <p>Explorez les fonctionnalités de notre site.</p>
    </main>
    <footer>
        <p>&copy; <?= date('Y'); ?> - BE ROUTAGE - </p>
    </footer>
</body>
</html>
