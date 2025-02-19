<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
include '../config/connexion_bd.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes du Jour</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ececec;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
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
            font-size: 1rem;
        }
        .btn:hover {
            background-color: #0056b3;
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
        .deleted {
            color: red;
            text-decoration: line-through;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Commandes du Jour</h1>
        <table>
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Plat</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $today = date('Y-m-d');
                $sql = "SELECT u.nom AS employe, m.plat, c.date, c.status 
                        FROM commandes c 
                        JOIN utilisateurs u ON c.employe = u.id 
                        JOIN menu m ON c.plat_id = m.id 
                        WHERE c.date = '$today'";
                $result = $conn->query($sql);

                $commandes = [];
                $nonAnnulees = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['employe'] . "</td>";
                        echo "<td class='" . ($row['status'] == 'deleted' ? 'deleted' : '') . "'>" . $row['plat'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "</tr>";

                        // Comptabiliser seulement les commandes non annulées
                        if ($row['status'] !== 'deleted') {
                            if (!isset($commandes[$row['plat']])) {
                                $commandes[$row['plat']] = 1;
                            } else {
                                $commandes[$row['plat']]++;
                            }
                        }
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucune commande pour aujourd'hui.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php if (array_sum($commandes) > 2) : ?>
            <h2>Résumé des Commandes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Plat</th>
                        <th>Nombre de demandes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($commandes as $plat => $count) {
                        echo "<tr>";
                        echo "<td>" . $plat . "</td>";
                        echo "<td>" . $count . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="link">
            <a href="menu.php">Retour au menu</a><br>
            <?php if ($_SESSION['role'] == 'super_utilisateur') : ?>
                <a href="historique_commandes.php">Historique</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
