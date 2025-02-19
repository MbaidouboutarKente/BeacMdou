<!-- supprimer_plat.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: index.php");
    exit();
}
include '../config/connexion_bd.php';

$id = $_GET['id'];

// Récupérer le chemin de l'image avant de supprimer le plat
$sql = "SELECT image FROM menu WHERE id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$image_path = '../img/' . $row['image'];

// Supprimer le plat de la base de données
$sql = "DELETE FROM menu WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    // Supprimer l'image du dossier
    if (file_exists($image_path)) {
        unlink($image_path);
    }
    header("Location: afficher_plats.php");
} else {
    echo "Erreur: " . $conn->error;
}

$conn->close();
?>
