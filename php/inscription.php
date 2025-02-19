<!-- inscription.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
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
            top: 50px;
            max-width: 500px;
            margin: auto;
            background: rgba(8, 8, 8, 0.599);
            color: white;
            padding: 15px 20px 50px 20px;
            box-shadow: 0 0 10px rgba(217, 172, 172, 0.707);
            border-radius: 30px;
        }
        h1, h2 {
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
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input[type="submit"] {
            font-weight: bold;
            padding: 10px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-size:1rem;
        }
        input[type="submit"]:hover {
            color: orange;
            background-color: #218838;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .link {
            text-align: center;
            margin-top: 10px;
            font-size: 1.2rem;
        }
        .link a {
            color: #007bff;
            text-decoration: none;
        }
        .link a:hover {
            color: orange;
        }
    </style>
</head>
<body>

<div class="background-image"></div>

    <div class="container">
        <h1>Inscription</h1>
        
        <div class="form-section">
            <!-- <h2>Inscription Employé</h2> -->
            <form action="inscription.php" method="POST">
                <input type="hidden" name="role" value="employe">
                <label for="nom_employe">Nom</label>
                <input type="text" id="nom_employe" name="nom" placeholder="Entrez votre Nom" required>
                <label for="email_employe">Email </label>
                <input type="email" id="email_employe" name="email" placeholder="Entrez votre Email(exemple@gmail.com)" required>
                <label for="password_employe">Mot de passe </label>
                <input type="password" id="password_employe" name="password" placeholder="Entrez votre mot de passe" required>
                <input type="submit" value="S'inscrire">
            </form>
            <div class="link">
                <p> Déjà inscrit?<a href="connexion.php"> Connectez-vous</a></p>
            </div>
        </div>
        
        <!-- <div class="form-section">
            <h2>Inscription Super Utilisateur</h2>
            <form action="inscription.php" method="POST">
                <input type="hidden" name="role" value="super_utilisateur">
                <label for="nom_super">Nom :</label>
                <input type="text" id="nom_super" name="nom" required>
                <label for="email_super">Email :</label>
                <input type="email" id="email_super" name="email" required>
                <label for="password_super">Mot de passe :</label>
                <input type="password" id="password_super" name="password" required>
                <input type="submit" value="S'inscrire">
            </form>
            <div class="link">
                <a href="connexion.php">Déjà inscrit? Connectez-vous</a>
            </div>
        </div> -->

        <?php
            // Inclure le fichier de connexion à la base de données
            include '../config/connexion_bd.php';
            // include 'connexion_bd.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer les données du formulaire
                $nom = $_POST['nom'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe
                $role = $_POST['role'];

                // Insérer les données dans la table `utilisateurs`
                $sql = "INSERT INTO utilisateurs (nom, email, role, password) VALUES ('$nom', '$email', '$role', '$password')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Inscription réussie! <a href='connexion.php'>Connectez-vous ici</a></p>";
                } else {
                    echo "<p>Erreur: " . $sql . "<br>" . $conn->error . "</p>";
                }
            }

            $conn->close();
        ?>
    </div>
</body>
</html>
