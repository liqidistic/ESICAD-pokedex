<?php
require_once("head.php");
require_once("database-connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"]);
    $password = $_POST["password"];

    // Vérifier que tous les champs sont remplis
    if (empty($login) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Vérifier si le login existe
        $stmt = $databaseConnection->prepare("SELECT IdUser, PasswordHash FROM user WHERE Login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows === 0) {
            $error = "Login incorrect.";
        } else {
            // Récupérer le hash du mot de passe
            $stmt->bind_result($idUser, $passwordHash);
            $stmt->fetch();
            
            // Vérifier le mot de passe
            if (password_verify($password, $passwordHash)) {
                // Connexion réussie, démarrer la session
                session_start();
                $_SESSION['user_id'] = $idUser;
                $_SESSION['login'] = $login;
                header("Location: team.php"); // Rediriger vers la page équipe
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        }
        $stmt->close();
    }
    $databaseConnection->close();
}
?>

<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form method="post" action="">
        <label>Login :</label>
        <input type="text" name="login" required><br>
        
        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Se connecter</button>
    </form>
    
    <p>Pas encore inscrit ? <a href="register.php">Créez un compte ici</a></p>
</body>

<?php
require_once("footer.php");
?>