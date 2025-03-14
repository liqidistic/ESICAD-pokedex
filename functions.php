<?php
// Ajouter un Pokémon à l'équipe avec la date d'ajout et une limite de 6
function addPokemonToTeam($userId, $pokemonId, $databaseConnection) {
    // Vérifier le nombre actuel de Pokémon dans l'équipe
    $stmt = $databaseConnection->prepare("SELECT COUNT(*) as teamCount FROM user_pokemon WHERE IdUser = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $teamCount = $row['teamCount'];
    $stmt->close();

    // Limite de 6 Pokémon
    if ($teamCount >= 6) {
        return "Votre équipe est déjà complète (6 Pokémon maximum). Retirez un Pokémon pour en ajouter un nouveau.";
    }

    // Vérifier si le Pokémon est déjà dans l'équipe de l'utilisateur
    $stmt = $databaseConnection->prepare("SELECT IdCapture FROM user_pokemon WHERE IdUser = ? AND IdPokemon = ?");
    $stmt->bind_param("ii", $userId, $pokemonId);
    $stmt->execute();
    $stmt->store_result();
    
    // Si le Pokémon est déjà dans l'équipe, on l'ajoute avec une nouvelle date (si la limite n'est pas atteinte)
    if ($stmt->num_rows > 0) {
        $stmt->close();
        // Ajouter une nouvelle instance de Pokémon avec une nouvelle date
        $stmt = $databaseConnection->prepare("INSERT INTO user_pokemon (IdUser, IdPokemon, DateCapture) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $pokemonId);
        if ($stmt->execute()) {
            $stmt->close();
            return "Une nouvelle instance de ce Pokémon a été ajoutée à votre équipe !";
        } else {
            $stmt->close();
            return "Erreur lors de l'ajout du Pokémon. Veuillez réessayer.";
        }
    } else {
        $stmt->close();
        // Si ce Pokémon n'est pas dans l'équipe, on l'ajoute avec une nouvelle date
        $stmt = $databaseConnection->prepare("INSERT INTO user_pokemon (IdUser, IdPokemon, DateCapture) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $pokemonId);
        if ($stmt->execute()) {
            $stmt->close();
            return "Pokémon ajouté à votre équipe !";
        } else {
            $stmt->close();
            return "Erreur lors de l'ajout du Pokémon. Veuillez réessayer.";
        }
    }
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
// Ajouter une recherche favorite
function addFavoriteSearch($userId, $searchQuery, $databaseConnection) {
    // Vérifier si la recherche existe déjà pour cet utilisateur
    $stmt = $databaseConnection->prepare("SELECT IdSearch FROM favorite_searches WHERE IdUser = ? AND SearchQuery = ?");
    $stmt->bind_param("is", $userId, $searchQuery);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        return "Cette recherche est déjà dans vos favoris.";
    } else {
        // Ajouter la recherche favorite
        $stmt = $databaseConnection->prepare("INSERT INTO favorite_searches (IdUser, SearchQuery) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $searchQuery);
        if ($stmt->execute()) {
            $stmt->close();
            return "Recherche ajoutée aux favoris !";
        } else {
            $stmt->close();
            return "Erreur lors de l'ajout de la recherche. Veuillez réessayer.";
        }
    }
}

// Récupérer les recherches favorites d'un utilisateur
function getFavoriteSearches($userId, $databaseConnection) {
    $stmt = $databaseConnection->prepare("SELECT IdSearch, SearchQuery, DateSaved FROM favorite_searches WHERE IdUser = ? ORDER BY DateSaved DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

// Supprimer une recherche favorite
function removeFavoriteSearch($searchId, $databaseConnection) {
    $stmt = $databaseConnection->prepare("DELETE FROM favorite_searches WHERE IdSearch = ?");
    $stmt->bind_param("i", $searchId);
    if ($stmt->execute()) {
        $stmt->close();
        return "Recherche retirée des favoris.";
    } else {
        $stmt->close();
        return "Erreur lors de la suppression de la recherche. Veuillez réessayer.";
    }
}
?>
