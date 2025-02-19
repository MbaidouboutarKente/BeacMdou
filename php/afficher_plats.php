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
    <title>Afficher les Plats</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: black;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #000;
        }
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("../css/images.jpeg") center center / cover no-repeat;
            filter: blur(10px);
            z-index: -1;
            opacity: 0.7;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        .container {
            position: relative;
            width: 90%;
            max-width: 1200px;
            margin: auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
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
        .link_ajout {
            text-align: right;
            margin-bottom: 20px;
        }
        .date-section {
            margin-top: 30px;
            font-weight: bold;
            color: #007bff;
        }
        .scroll-buttons {
            position: fixed;
            right: 20px;
            bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .scroll-buttons .btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        @media (max-width: 800px) {
            .container {
                width: 100%;
                padding: 10px;
            }
            th, td {
                padding: 5px;
            }
            .btn {
                padding: 8px;
                font-size: 0.9rem;
            }
            .actions {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }
        }
        td img {
            height: 75px;
            width: 80px;
        }
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="container">
        <h1>Afficher les Plats</h1>
        <?php
        $sql = "SELECT * FROM menu ORDER BY date DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $current_date = '';
            while ($row = $result->fetch_assoc()) {
                if ($current_date != $row['date']) {
                    if ($current_date != '') {
                        echo "</tbody></table>";
                    }
                    $current_date = $row['date'];
                    echo "<div class='date-section'>Plats pour le " . date("d M Y", strtotime($current_date)) . "</div>";
                    echo "<table><thead><tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix (FCFA)</th>
                        <th>Image</th>
                        <th>Actions</th>
                        </tr></thead><tbody>";
                }
                echo "<tr>";
                echo "<td style='max-width: 50px;'>" . $row['id'] . "</td>";
                echo "<td style='max-width: 150px;'>" . $row['plat'] . "</td>";
                echo "<td style='max-width: 300px;'>" . $row['description'] . "</td>";
                echo "<td style='max-width: 100px;'>" . $row['prix'] . "</td>";
                echo "<td><img src='../img/" . $row['image'] . "' alt='" . $row['plat'] . "' style='max-width: 100px;'></td>";
                echo "<td class='actions' style='max-width: 150px;'><a href='modifier_plat.php?id=" . $row['id'] . "' class='btn'>Modifier</a> <a href='supprimer_plat.php?id=" . $row['id'] . "' class='btn' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce plat?\");'>Supprimer</a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Aucun plat trouvé.</p>";
        }

        $conn->close();
        ?>

        <?php if ($_SESSION['role'] == 'super_utilisateur') : ?>
            <div class="link_ajout">
                <a href="ajouter_plat.php" class="btn">Ajouter un plat</a>
            </div>
        <?php endif; ?>
        <div class="link">
            <a href="menu.php">Retour au menu</a>
        </div>
    </div>

    <div class="scroll-buttons">
        <button class="btn" onclick="scrollToTop()">&#8679;</button>
        <button class="btn" onclick="scrollToBottom()">&#8681;</button>
    </div>

    <script>
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        function scrollToBottom() {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    </script>
</body>
</html>
