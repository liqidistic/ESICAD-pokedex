    
<?php
require_once("head.php");
require_once("database-connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $login = trim($_POST["login"]);
    $password = $_POST["password"];

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($prenom) || empty($login) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Vérifier si le login est déjà utilisé
        $stmt = $databaseConnection->prepare("SELECT IdUser FROM user WHERE Login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Ce login est déjà utilisé, veuillez en choisir un autre.";
        } else {
            // Hachage du mot de passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insérer l'utilisateur
            $stmt = $databaseConnection->prepare("INSERT INTO user (Nom, Prenom, Login, PasswordHash) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nom, $prenom, $login, $passwordHash);
            
            if ($stmt->execute()) {
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
        $stmt->close();
    }
    $databaseConnection->close();
}
?>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    
    <form method="post" action="">
        <label>Nom :</label>
        <input type="text" name="nom" required><br>
        
        <label>Prénom :</label>
        <input type="text" name="prenom" required><br>
        
        <label>Login :</label>
        <input type="text" name="login" required><br>
        
        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">S'inscrire</button>
    </form>
    
    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
</body>


<?php
require_once("footer.php");
?>