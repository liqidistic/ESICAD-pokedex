<?php
require_once("head.php");
?>

<?php
require_once("database-connection.php");
require_once("functions.php");

if (!isset($_SESSION['login'])) {
    header("Location: login.php"); // Redirige si l'utilisateur n'est pas connecté
    exit();
}

$IdUser = $_SESSION['user_id']; // Utiliser l'ID de l'utilisateur connecté

// Récupérer les Pokémon de l'équipe de l'utilisateur
$pokemonsInTeam = getUserPokemons($IdUser, $databaseConnection);

// Vérifie si des Pokémon sont retournés
if ($pokemonsInTeam->num_rows > 0) {
    $pokemonsExist = true;
} else {
    $pokemonsExist = false;
}

// Ajouter un Pokémon à l'équipe
$success = '';
$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["pokemon_id"])) {
    $pokemonId = $_POST["pokemon_id"];
    $resultMessage = addPokemonToTeam($IdUser, $pokemonId, $databaseConnection);
    
    if (strpos($resultMessage, "ajouté") !== false) {
        $success = $resultMessage;
    } else {
        $error = $resultMessage;
    }
    // Rediriger pour actualiser la page après l'ajout
    header("Location: equipe.php");
    exit();
}

// Supprimer un Pokémon de l'équipe
if (isset($_GET['remove'])) {
    $captureId = $_GET['remove'];
    $resultMessage = removePokemonFromTeam($captureId, $databaseConnection);
    if (strpos($resultMessage, "retiré") !== false) {
        $success = $resultMessage;
    } else {
        $error = $resultMessage;
    }
    // Rediriger pour actualiser la page après le retrait
    header("Location: equipe.php");
    exit();
}

?>

<html>
<head>
    <title>Mon équipe</title>
</head>
<body>
    <h2>Mon équipe</h2>
    
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    
    <h3>Pokémons dans votre équipe</h3>
    <?php if ($pokemonsExist): ?>
        <ul>
            <?php while ($pokemon = $pokemonsInTeam->fetch_assoc()): ?>
                <li>
                    <?php echo htmlspecialchars($pokemon['NomPokemon']); ?> - Type(s): <?php echo htmlspecialchars($pokemon['Type1']); ?>
                    <?php if ($pokemon['Type2']) echo "/" . htmlspecialchars($pokemon['Type2']); ?>
                    - Date d'ajout : <?php echo htmlspecialchars($pokemon['DateCapture']); ?>
                    <a href="?remove=<?php echo $pokemon['IdCapture']; ?>">Retirer</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Vous n'avez aucun Pokémon dans votre équipe.</p>
    <?php endif; ?>
    
    <h3>Ajouter un Pokémon à votre équipe</h3>
    <form method="post" action="">
        <label for="pokemon_id">Sélectionner un Pokémon :</label>
        <select name="pokemon_id" required>
            <?php
            $allPokemons = getAllPokemons($databaseConnection);
            while ($pokemon = $allPokemons->fetch_assoc()) {
                echo "<option value='" . $pokemon['IdPokemon'] . "'>" . htmlspecialchars($pokemon['NomPokemon']) . "</option>";
            }
            ?>
        </select>
        <button type="submit">Ajouter à l'équipe</button>
    </form>
    
    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>

<?php
require_once("footer.php");
?>
