<!-- connexion.php -->
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        } */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: #000;
        }
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("../css/fg3.jpeg") center center / cover no-repeat;
            filter: blur(10px);
            z-index: -1;
            opacity: 0.8;
        }
        .container {
            position: relative;
            top: 100px;
            max-width: 500px;
            margin: auto;
            background: rgba(8, 8, 8, 0.599);
            color: white;
            padding: 15px 20px 50px 20px;
            box-shadow: 0 0 10px rgba(217, 172, 172, 0.707);
            border-radius: 30px;
        }
        h1 {
            text-align: center;
            font-size: 2.7rem;
            text-align: center;
            color: white;
            font-family: 'Great Vibes', cursive;
            transform: scaleX(2);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 1.5rem;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 15px;
        }
        input[type="submit"] {
            padding: 15px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 15px;
            font-weight: bold;
            font-size:1rem;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            color: orange;
            background-color: #0056b3;
        }
        .hidden {
            display: none;
        }
        .link {
            text-align: center;
            margin-top: 10px;
            font-size: 1.2rem;
        }
        .link p {
            color: white;
        }
        .link p a{
            color: #007bff;
            text-decoration: none;
        }
        .link a:hover {
            color: orange;
        }

    </style>
    <script>
        function showPasswordInput() {
            document.getElementById('password_form').classList.remove('hidden');
        }
    </script>
</head>
<body>
    <div class="background-image"></div>
    <div class="container">
        <h1>Connexion</h1>
        <form id="email_form" method="POST" action="connexion.php">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
            <input type="submit" value="Suivant">
        </form>
        
        <form id="password_form" class="hidden" method="POST" action="connexion.php">
            <input type="hidden" id="hidden_email" name="email">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>

        <div class="link">
           <p> Pas encore inscrit?<a href="inscription.php"> Inscrivez-vous ici</a></p>
        </div>

        <?php
        // Inclure le fichier de connexion à la base de données
        include '../config/connexion_bd.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Vérification si l'email est envoyé (première étape)
            if (isset($_POST['email']) && !isset($_POST['password'])) {
                $email = $_POST['email'];

                // Vérifier si l'email existe
                $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<script>document.getElementById('hidden_email').value = '$email';</script>";
                    echo "<script>document.getElementById('email_form').classList.add('hidden');</script>";
                    echo "<script>document.getElementById('password_form').classList.remove('hidden');</script>";
                } else {
                    echo "<p>Aucun utilisateur trouvé avec cet email.</p>";
                }
            }

            // Vérification si l'email et le mot de passe sont envoyés (deuxième étape)
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                // Vérifier les informations d'identification
                $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['role'] = $user['role'];
                        echo "<script>window.location.href = 'index.php';</script>";
                        exit();
                    } else {
                        echo "<p>Mot de passe incorrect.</p>";
                        echo "<script>document.getElementById('hidden_email').value = '$email';</script>";
                        echo "<script>document.getElementById('email_form').classList.add('hidden');</script>";
                        echo "<script>document.getElementById('password_form').classList.remove('hidden');</script>";
                    }
                }
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
