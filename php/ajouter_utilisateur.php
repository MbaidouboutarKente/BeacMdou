<!-- ajouter_utilisateur.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: index.php");
    exit();
}
include '../config/connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    $sql = "INSERT INTO utilisateurs (nom, email, role, password) VALUES ('$nom', '$email', '$role', '$password')";
    if ($conn->query($sql) === TRUE) {
        header("Location: gestion_utilisateurs.php");
    } else {
        echo "Erreur lors de l'insertion des données dans la base : " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
        .link a {
            color: #007bff;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Utilisateur</h1>
        <form action="ajouter_utilisateur.php" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="role">Rôle :</label>
            <select id="role" name="role" required>
                <option value="utilisateur">Utilisateur</option>
                <option value="employe">Employé</option>
                <option value="super_utilisateur">Super Utilisateur</option>
            </select>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Ajouter">
        </form>
        <div class="link">
            <a href="gestion_utilisateurs.php">Retour à la gestion des utilisateurs</a>
        </div>
    </div>
</body>
</html>
