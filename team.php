<?php
require_once("head.php");
require_once("database-connection.php");
require_once("functions.php");

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$IdUser = $_SESSION['user_id'];
$pokemonsInTeam = getUserPokemons($IdUser, $databaseConnection);
$pokemonsExist = $pokemonsInTeam->num_rows > 0;

// Gestion de l'ajout et de la suppression de Pokémon
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["pokemon_id"])) {
    $pokemonId = $_POST["pokemon_id"];
    addPokemonToTeam($IdUser, $pokemonId, $databaseConnection);
    header("Location: team.php");
    exit();
}
if (isset($_GET['remove'])) {
    $captureId = $_GET['remove'];
    removePokemonFromTeam($captureId, $databaseConnection);
    header("Location: team.php");
    exit();
}

// Récupérer les recherches favorites
$favoriteSearches = getFavoriteSearches($IdUser, $databaseConnection);
?>

<html>
<head>
    <title>Mon équipe</title>
</head>
<body>
    <h2>Mon équipe</h2>
    
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

    <h3>Vos recherches favorites</h3>
    <?php if ($favoriteSearches->num_rows > 0): ?>
        <ul>
            <?php while ($search = $favoriteSearches->fetch_assoc()): ?>
                <li>
                    <a href="search_pokemon.php?q=<?php echo urlencode($search['SearchQuery']); ?>">
                        <?php echo htmlspecialchars($search['SearchQuery']); ?>
                    </a> - Sauvegardé le : <?php echo htmlspecialchars($search['DateSaved']); ?>
                    <a href="?remove_search=<?php echo $search['IdSearch']; ?>">Supprimer</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Vous n'avez aucune recherche favorite pour le moment.</p>
    <?php endif; ?>

    <?php
    if (isset($_GET['remove_search'])) {
        $searchId = $_GET['remove_search'];
        removeFavoriteSearch($searchId, $databaseConnection);
        header("Location: team.php");
        exit();
    }
    ?>
    
    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>

<?php
require_once("footer.php");
?>