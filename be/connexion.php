<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['username']) && !empty($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $sql = "SELECT mdpUtilisateur FROM Utilisateur WHERE nomUtilisateur = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mdputilisateur'])) {
            // Si le mot de passe correspond, enregistrez l'utilisateur dans la session
            $_SESSION['user'] = $username;
            header("Location: profil.php"); // Rediriger vers le profil
            exit();
        } else {
            // Si le mot de passe ne correspond pas, affichez un message d'erreur
            $error = "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        // Gérer l'erreur
        $error = "Erreur de connexion : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f9;
        }
        form {
            margin-top: 20px;
        }
        input[type=text], input[type=password] {
            margin: 10px 0;
            padding: 8px;
        }
        input[type=submit], button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type=submit]:hover, button:hover {
            background-color: #45a049;
        }
        p {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Connexion</h2>
    <?php if (!empty($error)) { echo '<p>'.$error.'</p>'; } ?>
    <form action="connexion.php" method="post">
        Nom d'utilisateur: <input type="text" name="username" required><br>
        Mot de passe: <input type="password" name="password" required><br>
        <input type="submit" value="Se connecter">
    </form>
    <button onclick="window.location.href='inscription.php';">Retour à l'inscription</button>
</body>
</html>
