<!-- supprimer_utilisateur.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_utilisateur') {
    header("Location: index.php");
    exit();
}
include '../config/connexion_bd.php';

$id = $_GET['id'];

$sql = "DELETE FROM utilisateurs WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    header("Location: gestion_utilisateurs.php");
} else {
    echo "Erreur: " . $conn->error;
}

$conn->close();
?>
