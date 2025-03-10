<?php
session_start(); // Démarrer la session utilisateur
?>


<aside id="side-menu">
    <ul>
        <a href="list.php">
            <li>
                Liste des pokémons
            </li>
        </a>
        <a href="list-by-type.php">
            <li>
                Pokémons par type
            </li>
        </a>
        <a href="team.php">
            <li>
                Mon équipe
             </li>
        </a>
        <a href="login.php">
            <li>
                Se connecter
            </li>
        </a>
        <a href="register.php">
            <li>
                S'inscrire
            </li>
        </a>
    </ul>
    <header>
    <div style="float: left;">
        <?php
        if (isset($_SESSION['user_id'])):
            // Récupérer le prénom et le nom de l'utilisateur connecté
            require_once("database-connection.php");

            $userId = $_SESSION['user_id'];
            $stmt = $databaseConnection->prepare("SELECT Prenom, Nom FROM user WHERE IdUser = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $prenom = $user['Prenom'];
            $nom = $user['Nom'];
            $stmt->close();

            // Afficher le prénom et le nom de l'utilisateur
            echo "<p>Bienvenue, " . htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . " !</p>";
        endif;
        ?>
    </div>

    <div style="float: right;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="logout.php" method="post">
                <button type="submit">Se déconnecter</button>
            </form>
        <?php endif; ?>
    </div>
</header>
    
</aside>