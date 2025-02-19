<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: connexion.php");
    exit();
}
include '../config/connexion_bd.php';

function get_period($period) {
    $current_date = date('Y-m-d');
    if ($period == 'mois') {
        return date('Y-m-01') . " to " . $current_date;
    } elseif ($period == 'semaine') {
        return date('Y-m-d', strtotime('monday this week')) . " to " . $current_date;
    } elseif ($period == 'jour') {
        return $current_date . " to " . $current_date;
    }
    return date('Y-m-01') . " to " . $current_date; // Par défaut, les commandes du mois en cours
}

$selected_period = isset($_GET['period']) ? $_GET['period'] : 'mois';
list($start_date, $end_date) = explode(' to ', get_period($selected_period));

$custom_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$custom_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

if ($custom_start_date && $custom_end_date) {
    $start_date = $custom_start_date;
    $end_date = $custom_end_date;
}

$days = [];

$months = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 
    'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin', 
    'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 
    'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$current_month = $months[date('F')];
$current_date = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commandes</title>
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
            animation: fadeIn 1s ease-in-out;
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
        .filters {
            margin-bottom: 20px;
            text-align: center;
        }
        .filters select, .filters input[type="date"] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .month-title {
            margin-top: 20px;
            font-size: 1.5rem;
            color: #555;
        }
        .deleted {
            color: red;
            text-decoration: line-through;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <script>
        function updatePeriod() {
            var period = document.getElementById('period').value;
            window.location.href = 'historique_commandes.php?period=' + period;
        }
        function updateCustomPeriod() {
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;
            if (new Date(endDate) > new Date('<?php echo $current_date; ?>')) {
                alert('Vous ne pouvez pas sélectionner une date future.');
                return;
            }
            window.location.href = 'historique_commandes.php?start_date=' + startDate + '&end_date=' + endDate;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Historique des Commandes - <?php echo $current_month; ?></h1>
        <div class="filters">
            <label for="period">Période :</label>
            <select id="period" onchange="updatePeriod()">
                <option value="jour" <?php if ($selected_period == 'jour') echo 'selected'; ?>>Aujourd'hui</option>
                <option value="semaine" <?php if ($selected_period == 'semaine') echo 'selected'; ?>>Cette Semaine</option>
                <option value="mois" <?php if ($selected_period == 'mois') echo 'selected'; ?>>Ce Mois</option>
            </select>
            <br><br>
            <label for="start_date">De :</label>
            <input type="date" id="start_date" value="<?php echo $start_date; ?>">
            <label for="end_date">À :</label>
            <input type="date" id="end_date" value="<?php echo $end_date; ?>" max="<?php echo $current_date; ?>">
            <button class="btn" onclick="updateCustomPeriod()">Filtrer</button>
        </div>
        
        <?php
        $sql = "SELECT c.date, u.nom AS employe, m.plat, c.status 
                FROM commandes c 
                JOIN utilisateurs u ON c.employe = u.id 
                JOIN menu m ON c.plat_id = m.id 
                WHERE c.date BETWEEN '$start_date' AND '$end_date'
                ORDER BY c.date DESC";
        $result = $conn->query($sql);
        
        $commandes_par_mois = [];
        while ($row = $result->fetch_assoc()) {
            $month_year = date('F Y', strtotime($row['date']));
            if (!isset($commandes_par_mois[$month_year])) {
                $commandes_par_mois[$month_year] = [];
            }
            $commandes_par_mois[$month_year][] = $row;
        }

        $date1 = new DateTime($start_date);
        $date2 = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($date1, $interval, $date2->modify('+1 day')); // Modification ici

        foreach ($period as $dt) {
            $month_year = $dt->format('F Y');
            $month_year_fr = $months[$dt->format('F')] . " " . $dt->format('Y');

            if ($dt > new DateTime($current_date)) {
                continue; // Skip future months
            }

            echo "<div class='month-title'>$month_year_fr</div>";
            if (isset($commandes_par_mois[$month_year])) {
                echo "<table>";
                echo "<thead><tr><th>Date</th><th>Employé</th><th>Plat</th><th>Status</th></tr></thead>";
                echo "<tbody>";
                foreach ($commandes_par_mois[$month_year] as $commande) {
                    echo "<tr>";
                    echo "<td>" . $commande['date'] . "</td>";
                    echo "<td>" . $commande['employe'] . "</td>";
                    echo "<td class='" . ($commande['status'] == 'deleted' ? 'deleted' : '') . "'>" . $commande['plat'] . "</td>";
                    echo "<td>" . $commande['status'] . "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>Aucune commande trouvée pour $month_year_fr.</p>";
            }
        }

        $conn->close();
        ?>
        
        <div class="link">
            <a href="index.php">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
