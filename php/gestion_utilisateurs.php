<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: index.php");
    exit();
}
include '../config/connexion_bd.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            animation: fadeIn 2s;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 15px;
            border: 1px solid #ccc;
            text-align: left;
            animation: slideIn 0.5s ease-in-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        th {
            background-color: #f7f7f7;
            font-weight: bold;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 1rem;
            display: inline-block;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
        .link a {
            color: #007bff;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .add-user {
            text-align: right;
            margin-bottom: 20px;
        }
        .contenus {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            .gap: 10px;
        }
        a {
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .container {
                width: 100%;
                padding: 10px;
            }
            th, td {
                padding: 10px;
            }
            .btn {
                padding: 8px;
                font-size: 0.9rem;
            }
            .contenus {
                flex-direction: column;
                align-items: center;
            }
            .contenus p {
                margin-right: 0;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Utilisateurs</h1>
        <div class="add-user">
            <a href="ajouter_utilisateur.php" class="btn">Ajouter un Utilisateur</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $current_user_id = $_SESSION['user_id'];
                $sql = "SELECT * FROM utilisateurs";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nom'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['role'] . "</td>";
                        echo "<td>";
                        echo "<div class='contenus'>";
                        if ($row['id'] != $current_user_id) {
                            echo "<p><a href='modifier_utilisateur.php?id=" . $row['id'] . "' class='btn'>Modifier</a></p>";
                            echo "<p><a href='supprimer_utilisateur.php?id=" . $row['id'] . "' class='btn' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet utilisateur?\");'>Supprimer</a></p>";
                        } else {
                            echo "<p><button class='btn' style='color: red' disabled>Modifier</button></p>";
                            echo "<p><button class='btn' style='color: red' disabled>Supprimer</button></p>";
                        }
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Aucun utilisateur trouvé.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <div class="link">
            <a href="index.php">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>