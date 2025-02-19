<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant BEAC Moundou</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/custom.css"> <!-- Fichier de style séparé -->
</head>
<body>
    <header>
        <nav>
            <div id="logo"><a href="#">Resto BEAC</a></div>
            <ul>
                <?php
                session_start();
                if (isset($_SESSION['user_id'])) {
                    echo "<li><a href='deconnexion.php' class='btn'>Se déconnecter</a></li>";
                    echo "<li><a href='menu.php'>Voir le Menu</a></li>";

                    if ($_SESSION['role'] == 'employe' ) {
                        echo "<li><a href='commandes.php' class='btn'>Voir les Commandes du Jour</a></li>";
                    }
                    
                    if ($_SESSION['role'] == 'super_utilisateur') {
                        echo "<li class='dropdown'>";
                        echo "<button class='dropbtn'>Admin</button>";
                        echo "<div class='dropdown-content'>";
                        echo "<a href='gestion_utilisateurs.php'>Gérer les Utilisateurs</a>";
                        echo "<a href='commandes.php'>Voir les Commandes du Jour</a>";
                        echo "</div>";
                        echo "</li>";
                    }
                } else {
                    echo "<li><a href='connexion.php' class='btn'>Connexion</a></li>";
                    // echo "<li><a href='inscription.php' class='btn'>Inscription</a></li>";
                }
                ?>
            </ul>
        </nav>
        <div class="img1">
            <h1>BEAC Moundou</h1>
            <div id="trait1"></div>
            <h3>Restaurant et Services</h3>
        </div>
    </header>

    <section id="presentation">
        <div id="textIntro">
            <div class="div1">
                <h2>Un lieu unique pour une expérience culinaire inoubliable</h2>
                <p>Bienvenue au restaurant BEAC Moundou, où nous offrons une expérience culinaire unique avec des plats exquis préparés par nos chefs talentueux.</p>
                <p>Nous vous invitons à découvrir notre menu varié et à savourer nos spécialités dans une ambiance chaleureuse et conviviale. Que ce soit pour un déjeuner d'affaires, un dîner en famille ou une soirée entre amis, notre restaurant est l'endroit idéal pour toutes les occasions.</p>
            </div>
            <div class="carousel-container">
                <div class="carousel-slide">
                    <div class="slide">
                        <img src="../css/img/pizza.jpeg" alt="Pizza délicieuse">
                    </div>
                    <div class="slide">
                        <img src="../css/img/img.jpeg" alt="Ambiance conviviale">
                    </div>
                    <div class="slide">
                        <img src="../css/img/images4.jpeg" alt="Plats savoureux">
                    </div>
                    <div class="slide">
                        <img src="../css/img/ic_food.png" alt="Spécialités culinaires"> <!-- Répéter la première diapositive -->
                    </div>
                </div>
            </div>
        </div>
        <div class="presentations">
            <div class="imgpresentations">
                <h4>Nous contacter</h4>
                <a href="#"><img src="../css/images.jpeg" alt="Contactez-nous"></a>
            </div>
            <div class="imgpresentations">
                <h4>Nos Chambres</h4>
                <a href="#"><img src="../css/fg3.jpeg" alt="Chambres confortables"></a>
            </div>
            <div class="imgpresentations">
                <h4>Nos Plats</h4>
                <a href="#"><img src="../css/images3.jpeg" alt="Plats délicieux"></a>
            </div>
            <div class="imgpresentations">
                <h4>Nos Services</h4>
                <a href="#"><img src="../css/images3.jpeg" alt="Services divers"></a>
            </div>
        </div>
    </section>
    
    <section id="tourismes">
        <h2>Découvrez plus...</h2>
        <ul>
            <li id="ocean"><p>Des cascades</p></li>
            <li id="art"><p>Oeuvres d'art</p></li>
            <li id="avion"><p>Notre flotte</p></li>
            <li id="goho"><p>Le meilleur service</p></li>
        </ul>
    </section>
    
    <footer>
        <div class="footer">
            <div class="footer-section">
                <h3>Nos Contacts</h3>
                <p>Restaurant BEAC Moundou</p>
                <p>1234 Rue du Grand Marché, 5678 Moundou</p>
                <p>Tél : +235-6656-7890</p>
                <p>Tél : +235-9956-7890</p>
                <p>Email : support@beacmoundou.com</p>
                <p>Ouvert du lundi au vendredi de 9h à 22h</p>
                <p>Ouvert le samedi et dimanche de 10h à 21h</p>
            </div>
            <div class="footer-section">
                <p>BEAC Moundou</p>
                <p>Commandez en ligne ou en personne à notre restaurant.</p>
                <p><img src="../css/beac.jpeg" alt=""></p>
            </div>
        </div>
        <div id="trait2"></div>
        <div id="copyringhEtIcons">
            <div id="copyright">
                <p>&copy; 2025 BEAC Moundou - Tous droits réservés.</p>
            </div>
            <div id="icons">
                <a href="http://www.twitter.fr"><i class="fa fa-twitter"></i></a>
                <a href="http://www.facebook.fr"><i class="fa fa-facebook"></i></a>
                <a href="http://www.instagram.fr"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script>
        const slides = document.querySelectorAll('.slide');
        let counter = 0; // Débuter depuis la première diapositive
        const slideWidth = slides[0].clientWidth;
        
        setInterval(() => {
            counter++;
            // Déplacer les images plutôt que le conteneur du carousel
            slides.forEach((slide, index) => {
                slide.style.transform = `translateX(-${slideWidth * counter}px)`;
                // Ajouter la classe 'in-view' à la diapositive à l'intérieur du carousel-slide
                if (index === counter % slides.length) {
                    slide.classList.add('in-view');
                } else {
                    slide.classList.remove('in-view');
                }
            });

            // Réinitialiser le compteur si c'est la dernière diapositive
            if (counter === slides.length - 1) {
                setTimeout(() => {
                    // Réinitialiser la position de chaque image pour créer une transition fluide
                    slides.forEach((slide, index) => {
                        slide.style.transition = 'none';
                        slide.style.transform = `translateX(0)`;
                    });

                    // Activer la transition après un bref délai pour une transition fluide
                    setTimeout(() => {
                        slides.forEach((slide, index) => {
                            slide.style.transition = 'transform 0.5s ease';
                        });
                    }, 50);
                    counter = 0; // Réinitialiser le compteur à zéro
                }, 3000); // Temps d'attente avant de réinitialiser le compteur
            }
        }, 3000); // Intervalle de défilement automatique
    </script>
</body>
</html>
