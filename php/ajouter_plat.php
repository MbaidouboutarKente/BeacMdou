<!-- ajouter_plat.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: index.php");
    exit();
}
include '../config/connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plat = $_POST['plat'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $date = $_POST['date'];
    $image = $_FILES['image']['name'];
    $target = "../img/" . basename($image);

    // Vérification des permissions du dossier
    if (!is_dir('../img')) {
        echo "Erreur: Le dossier 'img' n'existe pas.";
        exit();
    }
    if (!is_writable('../img')) {
        $permissions = substr(sprintf('%o', fileperms('../img')), -4);
        echo "Erreur: Le dossier 'img' n'est pas accessible en écriture. Permissions actuelles: $permissions";
        exit();
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO menu (plat, description, prix, date, image) VALUES ('$plat', '$description', '$prix', '$date', '$image')";
        if ($conn->query($sql) === TRUE) {
            header("Location: menu.php");
        } else {
            echo "Erreur lors de l'insertion des données dans la base : " . $conn->error;
        }
    } else {
        echo "Erreur lors du téléchargement de l'image. Code d'erreur : " . $_FILES['image']['error'];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Plat</title>
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
        input[type="text"], textarea, input[type="number"], input[type="date"], input[type="file"] {
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
        <h1>Ajouter un Plat</h1>
        <form action="ajouter_plat.php" method="POST" enctype="multipart/form-data">
            <label for="plat">Nom du plat :</label>
            <input type="text" id="plat" name="plat" required>
            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            <label for="prix">Prix (FCFA) :</label>
            <input type="number" id="prix" name="prix" step="0.01" required>
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" required>
            <label for="image">Image :</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            <input type="submit" value="Ajouter">
        </form>
        <div class="link">
            <a href="menu.php">Retour au menu</a>
        </div>
    </div>
</body>
</html>
