<!-- modifier_plat.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: index.php");
    exit();
}
include '../config/connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $plat = $_POST['plat'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $date = $_POST['date'];
    $image = $_FILES['image']['name'];

    if ($image) {
        // Récupérer l'ancienne image
        $sql = "SELECT image FROM menu WHERE id='$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $old_image_path = '../img/' . $row['image'];

        // Supprimer l'ancienne image
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }

        // Télécharger la nouvelle image
        $target = "../img/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "UPDATE menu SET plat='$plat', description='$description', prix='$prix', date='$date', image='$image' WHERE id='$id'";
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            exit();
        }
    } else {
        $sql = "UPDATE menu SET plat='$plat', description='$description', prix='$prix', date='$date' WHERE id='$id'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: afficher_plats.php");
    } else {
        echo "Erreur: " . $conn->error;
    }
}

$id = $_GET['id'];
$sql = "SELECT * FROM menu WHERE id='$id'";
$result = $conn->query($sql);
$plat = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Plat</title>
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
        <h1>Modifier Plat</h1>
        <form action="modifier_plat.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $plat['id']; ?>">
            <label for="plat">Nom du plat :</label>
            <input type="text" id="plat" name="plat" value="<?php echo $plat['plat']; ?>" required>
            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" required><?php echo $plat['description']; ?></textarea>
            <label for="prix">Prix (FCFA) :</label>
            <input type="number" id="prix" name="prix" step="0.01" value="<?php echo $plat['prix']; ?>" required>
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" value="<?php echo $plat['date']; ?>" required>
            <label for="image">Image (laisser vide pour ne pas changer) :</label>
            <input type="file" id="image" name="image" accept="image/*">
            <input type="submit" value="Modifier">
        </form>
        <div class="link">
            <a href="afficher_plats.php">Retour aux plats</a>
        </div>
    </div>
</body>
</html>
