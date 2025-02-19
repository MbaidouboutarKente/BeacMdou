<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
include '../config/connexion_bd.php';

date_default_timezone_set('Africa/Douala'); // Set the timezone
$currentHour = date('H');
$currentMinute = date('i');
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['plats'])) {
        $employe = $_SESSION['user_id'];
        $date = date('Y-m-d');
        $plats = $_POST['plats'];

        $validPlats = [];
        foreach ($plats as $plat_id) {
            $sql = "SELECT date FROM menu WHERE id = '$plat_id' AND date = '$date'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $validPlats[] = $plat_id;
            }
        }

        if (!empty($validPlats)) {
            foreach ($validPlats as $plat_id) {
                $sql = "INSERT INTO commandes (employe, plat_id, date) VALUES ('$employe', '$plat_id', '$date')";
                $conn->query($sql);
            }

            if ($_SESSION['role'] == 'super_utilisateur') {
                header("Location: commandes.php");
                exit();
            } else {
                $message = "Félicitations! Votre commande a été passée avec succès.";
            }
            
        } else {
            $message = "Aucun plat sélectionné n'est disponible dans le menu du jour.";
        }

        header("Location: menu.php?message=" . urlencode($message));
        exit();
    }

    if (isset($_POST['annuler_commande'])) {
        $commandeId = $_POST['annuler_commande'];
        $userId = $_SESSION['user_id'];
        $sql = "UPDATE commandes SET status = 'deleted' WHERE id = '$commandeId' AND employe = '$userId'";
        if ($conn->query($sql) === TRUE) {
            $message = "Votre commande a été annulée avec succès.";
        } else {
            $message = "Erreur lors de l'annulation de la commande.";
        }

        header("Location: menu.php?message=" . urlencode($message));
        exit();
    }
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu du Jour</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: white;
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
            background: url("../css/beac.jpeg") center center / cover no-repeat;
            filter: blur(10px);
            z-index: -1;
            opacity: 0.8;
        }
        .container {
            text-align: center;
            position: relative;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 80%;
            max-width: 1200px;
            margin: auto;
            padding-bottom: 50px;
            animation: fadeIn 2s;
        }
        h1 {
            text-align: center;
            color: #fff;
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
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            animation: slideIn 0.5s ease-in-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        th {
            background-color: rgba(0, 0, 0, 0.5);
        }
        .btn {
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .plat-image {
            max-width: 100px;
            height: auto;
        }
        .pied {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }
        .link a {
            color: #007bff;
            text-decoration: none;
        }
        .btns {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            border: 2px solid #007bff;
            border-radius: 5px;
            color: #007bff;
            font-size: 1.2rem;
            transition: background-color 0.3s, color 0.3s;
        }
        .btns:hover {
            background-color: #007bff;
            color: #fff;
        }
        .message {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #4caf50;
            animation: fadeIn 1s;
        }
        .recap {
            margin-top: 20px;
            color: white;
        }
        .countdown {
            font-size: 1.5rem;
            color: red;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 10px;
            }
            th, td {
                padding: 5px;
            }
            .btn {
                padding: 8px;
                font-size: 0.9rem;
            }
        }
        .pas{
            font-size: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="background-image"></div>
    <div class="container">
        <?php
        $closingHour = 23;
        $closingMinute = 0;
        $remainingMinutes = ($closingHour * 60 + $closingMinute) - ($currentHour * 60 + $currentMinute);

        if ($remainingMinutes <= 30 && $remainingMinutes > 0) {
            echo "<div class='countdown'>Attention! Il reste moins de 30 minutes avant la fermeture. ($remainingMinutes minutes restantes)</div>";
        }

        if ($currentHour >= $closingHour && $currentMinute >= $closingMinute) {
            echo "<h1>Les commandes sont fermées pour aujourd'hui. Revenez demain!</h1>";
        } else {
            echo "<h1>Menu du Jour</h1>";
            echo '<form action="" method="POST">';
            echo '<table>';
            echo '<thead><tr><th>Image</th><th>Plat</th><th>Description</th><th>Prix (FCFA)</th><th>Sélectionner</th></tr></thead>';
            echo '<tbody>';
            
            $today = date('Y-m-d');
            $sql = "SELECT * FROM menu WHERE date = '$today'";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='../img/" . $row['image'] . "' alt='" . $row['plat'] . "' class='plat-image'></td>";
                    echo "<td>" . $row['plat'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['prix'] . "</td>";
                    echo "<td><input type='checkbox' name='plats[]' value='" . $row['id'] . "'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='pas'>Pas de plats disponibles aujourd'hui.</td></tr>";
            }

            echo '</tbody>';
            echo '</table><br>';
            if ($currentHour < $closingHour) {
                echo '<input type="submit" class="btn" value="Commander">';
            }
            echo '</form>';

            // Afficher le récapitulatif uniquement si c'est avant l'heure de fermeture et qu'il y a des commandes
            $userId = $_SESSION['user_id'];
            $sql = "SELECT c.id, m.image, m.plat, m.description, m.prix 
                    FROM commandes c 
                    JOIN menu m ON c.plat_id = m.id 
                    WHERE c.employe = '$userId' AND c.date = '$today' AND c.status != 'deleted'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0 && $currentHour < $closingHour) {
                echo '<div class="recap">';
                echo '<h2>Récapitulatif de mes commandes</h2>';
                echo '<table>';
                echo '<thead><tr><th>Image</th><th>Plat</th><th>Description</th><th>Prix (FCFA)</th><th>Annuler</th></tr></thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='../img/" . $row['image'] . "' alt='" . $row['plat'] . "' class='plat-image'></td>";
                    echo "<td>" . $row['plat'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['prix'] . "</td>";
                    echo "<td><form method='POST' action=''><button type='submit' name='annuler_commande' value='" . $row['id'] . "' class='btn'>Annuler</button></form></td>";
                    echo "</tr>";
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
        }
        ?>

        <?php if ($message) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="pied">
            <?php if ($_SESSION['role'] == 'super_utilisateur') : ?>
                <div class="link">
                    <a href="commandes.php" class="btns">Afficher les commandes</a>
                </div>
                <div class="link">
                    <a href="afficher_plats.php" class="btns">Afficher les plats</a>
                </div>
            <?php endif; ?>
            <div class="link">
                <a href="index.php" class="btns">Retour à l'accueil</a>
            </div>
        </div>
    </div>

    <script>
        // Countdown timer function
        function startCountdown(minutes) {
            var countdownElement = document.querySelector('.countdown');
            var endTime = new Date().getTime() + minutes * 60000;

            var countdownInterval = setInterval(function() {
                var now = new Date().getTime();
                var distance = endTime - now;

                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = "Attention! Il reste " + minutes + " minutes et " + seconds + " secondes avant la fermeture.";

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = "Les commandes sont fermées pour aujourd'hui. Revenez demain!";
                }
            }, 1000);
        }

        // Start the countdown if less than 30 minutes remain
        <?php if ($remainingMinutes <= 30 && $remainingMinutes > 0) : ?>
            startCountdown(<?php echo $remainingMinutes; ?>);
        <?php endif; ?>

        // Supprimer le message de confirmation après 5 secondes
        document.addEventListener('DOMContentLoaded', function() {
            const messageDiv = document.querySelector('.message');
            if (messageDiv) {
                setTimeout(() => {
                    messageDiv.remove(); // Supprimer l'élément du DOM
                }, 5000); // Supprimer après 5 secondes
            }
        });

        // Prévenir la resoumission du formulaire
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
