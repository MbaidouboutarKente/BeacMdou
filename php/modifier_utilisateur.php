<!-- modifier_utilisateur.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: index.php");
    exit();
}
include '../config/connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE utilisateurs SET nom='$nom', email='$email', role='$role' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: gestion_utilisateurs.php");
    } else {
        echo "Erreur: " . $conn->error;
    }
}

$id = $_GET['id'];
$sql = "SELECT * FROM utilisateurs WHERE id='$id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
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
        input[type="text"], input[type="email"], select {
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
        <h1>Modifier Utilisateur</h1>
        <form action="modifier_utilisateur.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            <label for="role">Rôle :</label>
            <select id="role" name="role" required>
                <option value="employe" <?php if ($user['role'] == 'employe') echo 'selected'; ?>>Employé</option>
                <option value="super_utilisateur" <?php if ($user['role'] == 'super_utilisateur') echo 'selected'; ?>>Super Utilisateur</option>
            </select>
            <input type="submit" value="Modifier">
        </form>
        <div class="link">
            <a href="gestion_utilisateurs.php">Retour à la gestion des utilisateurs</a>
        </div>
    </div>
</body>
</html>
