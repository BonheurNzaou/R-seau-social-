<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Paramètres</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
            <?php include("menu.php"); ?>
        </header>
        <div id="wrapper" class='profile'>


            <aside>
                <?php include("userimg.php"); ?>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les informations de l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?></p>

                </section>
            </aside>
            <main>
                <?php
                /**
                 * Etape 1: Les paramètres concernent une utilisatrice en particulier
                 * La première étape est donc de trouver quel est l'id de l'utilisatrice
                 * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
                 * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
                 * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
                 * FAIT
                 */
                $userId = intval($_GET['user_id']);

                /**
                 * Etape 2: se connecter à la base de donnée
                 * FAIT
                 */
                include("connect.php");
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 * FAIT
                 */
                $laQuestionEnSql = "
                    SELECT users.*, 
                    COUNT(DISTINCT posts.id) AS totalpost, 
                    COUNT(DISTINCT given.post_id) AS totalgiven, 
                    COUNT(DISTINCT recieved.user_id) AS totalrecieved 
                    FROM users 
                    LEFT JOIN posts ON posts.user_id=users.id 
                    LEFT JOIN likes AS given ON given.user_id=users.id 
                    LEFT JOIN likes AS recieved ON recieved.post_id=posts.id 
                    WHERE users.id = '$userId' 
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }
                $user = $lesInformations->fetch_assoc();

                /**
                 * Etape 4: à vous de jouer
                 */
                //@todo: afficher le résultat de la ligne ci dessous, remplacer les valeurs ci-après puiseffacer la ligne ci-dessous
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                //FAIT
                ?>                
                <article class='parameters'>
                    <h3>Mes paramètres</h3>
                    <dl>
                        <dt>Pseudo</dt>
                        <dd><?php echo $user['alias']; ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo $user['email']; ?></dd>
                        <dt>Nombre de message</dt>
                        <dd><?php echo $user['totalpost']; ?></dd>
                        <dt>Nombre de "J'aime" donnés </dt>
                        <dd><?php echo $user['totalgiven']; ?></dd>
                        <dt>Nombre de "J'aime" reçus</dt>
                        <dd><?php echo $user['totalrecieved']; ?></dd>
                    </dl>

                </article>
            </main>
        </div>
    </body>
</html>