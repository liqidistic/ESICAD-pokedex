<?php
// Ajouter un Pokémon à l'équipe avec la date d'ajout
function addPokemonToTeam($userId, $pokemonId, $databaseConnection) {
    // Vérifier si le Pokémon est déjà dans l'équipe de l'utilisateur
    $stmt = $databaseConnection->prepare("SELECT IdCapture FROM user_pokemon WHERE IdUser = ? AND IdPokemon = ?");
    $stmt->bind_param("ii", $userId, $pokemonId);
    $stmt->execute();
    $stmt->store_result();
    
    // Si le Pokémon est déjà dans l'équipe de l'utilisateur, on l'ajoute avec une nouvelle date
    if ($stmt->num_rows > 0) {
        // Ajouter une nouvelle instance de Pokémon avec une nouvelle date
        $stmt = $databaseConnection->prepare("INSERT INTO user_pokemon (IdUser, IdPokemon, DateCapture) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $pokemonId);
        if ($stmt->execute()) {
            return "Une nouvelle instance de ce Pokémon a été ajoutée à votre équipe !";
        } else {
            return "Erreur lors de l'ajout du Pokémon. Veuillez réessayer.";
        }
    } else {
        // Si ce Pokémon n'est pas dans l'équipe, on l'ajoute avec une nouvelle date
        $stmt = $databaseConnection->prepare("INSERT INTO user_pokemon (IdUser, IdPokemon, DateCapture) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $pokemonId);
        if ($stmt->execute()) {
            return "Pokémon ajouté à votre équipe !";
        } else {
            return "Erreur lors de l'ajout du Pokémon. Veuillez réessayer.";
        }
    }
    $stmt->close();
}

// Retirer un Pokémon de l'équipe
function removePokemonFromTeam($captureId, $databaseConnection) {
    $stmt = $databaseConnection->prepare("DELETE FROM user_pokemon WHERE IdCapture = ?");
    $stmt->bind_param("i", $captureId);
    if ($stmt->execute()) {
        return "Pokémon retiré de votre équipe.";
    } else {
        return "Erreur lors de la suppression du Pokémon. Veuillez réessayer.";
    }
    $stmt->close();
}

// Récupérer tous les Pokémon disponibles
function getAllPokemons($databaseConnection) {
    $stmt = $databaseConnection->prepare("SELECT IdPokemon, NomPokemon FROM pokemon ORDER BY IdPokemon");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getUserPokemons($userId, $databaseConnection) {
    $stmt = $databaseConnection->prepare("
        SELECT p.NomPokemon, t1.NomType AS Type1, t2.NomType AS Type2, up.IdCapture, up.DateCapture
        FROM pokemon p
        JOIN user_pokemon up ON p.IdPokemon = up.IdPokemon
        LEFT JOIN typepokemon t1 ON p.IdType1 = t1.IdType
        LEFT JOIN typepokemon t2 ON p.IdType2 = t2.IdType
        WHERE up.IdUser = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
?>
