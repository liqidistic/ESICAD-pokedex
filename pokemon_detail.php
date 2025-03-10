<?php
require_once("database-connection.php");
require_once("head.php");

$idPokemon = intval($_GET['id']); // Sécurisation basique de l'entrée

// Définition des couleurs pour chaque type 
$colors = [
    "Feu" => "#ff6b6b", "Eau" => "#3498db", "Plante" => "#2ecc71", "Electrique" => "#f1c40f",
    "Glace" => "#74b9ff", "Combat" => "#d63031", "Psy" => "#e84393", "Roche" => "#7f8c8d",
    "Spectre" => "#6c5ce7", "Dragon" => "#0984e3","Normal" => "#dfe6e9", "Poison" => "#a29bfe", 
    "Vol" => "#81ecec","Sol" => "#e67e22", "Insecte" => "#27ae60"
];

// Récupérer les détails du Pokémon
$sql = "SELECT p.*, t1.NomType AS Type1, t2.NomType AS Type2 
        FROM pokemon p
        LEFT JOIN typepokemon t1 ON p.IdType1 = t1.IdType
        LEFT JOIN typepokemon t2 ON p.IdType2 = t2.IdType
        WHERE p.IdPokemon = $idPokemon";
$result = $databaseConnection->query($sql);

if ($result->num_rows == 0) {
    echo "Pokémon introuvable.";
    exit;
}

$pokemon = $result->fetch_assoc();
$bgColor = isset($colors[$pokemon["Type1"]]) ? $colors[$pokemon["Type1"]] : "#7f8c8d";

// Récupérer l'évolution du Pokémon
$sqlEvo = "SELECT p.IdPokemon, p.NomPokemon, p.UrlPhoto 
           FROM evolutions e 
           JOIN pokemon p ON e.IdEvolution = p.IdPokemon
           WHERE e.IdAncetre = $idPokemon";
$resultEvo = $databaseConnection->query($sqlEvo);

// Récupérer l'ancêtre du Pokémon
$sqlAncetre = "SELECT p.IdPokemon, p.NomPokemon, p.UrlPhoto 
               FROM evolutions e 
               JOIN pokemon p ON e.IdAncetre = p.IdPokemon
               WHERE e.IdEvolution = $idPokemon";
$resultAncetre = $databaseConnection->query($sqlAncetre);

// Affichage des détails du Pokémon
echo "<div class='pokemon-detail-container' style='display: flex; align-items: center; justify-content: space-between; background-color: $bgColor; padding: 20px; border-radius: 10px;'>";

// Ancêtre
echo "<div class='pokemon-evolution' style='text-align: center; width: 20%;'>";
if ($resultAncetre->num_rows > 0) {
    while ($anc = $resultAncetre->fetch_assoc()) {
        echo "<a href='pokemon_detail.php?id=" . $anc['IdPokemon'] . "'>";
        echo "<img src='" . $anc['UrlPhoto'] . "' alt='" . $anc['NomPokemon'] . "' class='evolution-image' style='max-width: 100px;'>";
        echo "<p>" . $anc['NomPokemon'] . "</p>";
        echo "</a>";
    }
} else {
    echo "<p>Pas d'ancêtre</p>";
}
echo "</div>";

// Pokémon principal
echo "<div class='pokemon-main' style='text-align: center; width: 50%;'>";
echo "<h2>" . htmlspecialchars($pokemon["NomPokemon"]) . "</h2>";
echo "<img src='" . htmlspecialchars($pokemon["UrlPhoto"]) . "' alt='" . htmlspecialchars($pokemon["NomPokemon"]) . "' class='pokemon-image' style='max-width: 150px;'>";
echo "<p>Type: " . $pokemon["Type1"];
if (!empty($pokemon["Type2"])) {
    echo " / " . $pokemon["Type2"];
}
echo "</p>";
echo "<p>PV: " . $pokemon["PtsVie"] . " | Défense: " . $pokemon["PtsDefense"] . "</p>";
echo "<p>Vitesse: " . $pokemon["PtsVitesse"] . " | Spéciaux: " . $pokemon["PtsSpeciaux"] . "</p>";
echo "</div>";

// Évolution
echo "<div class='pokemon-evolution' style='text-align: center; width: 20%;'>";
if ($resultEvo->num_rows > 0) {
    while ($evo = $resultEvo->fetch_assoc()) {
        echo "<a href='pokemon_detail.php?id=" . $evo['IdPokemon'] . "'>";
        echo "<img src='" . $evo['UrlPhoto'] . "' alt='" . $evo['NomPokemon'] . "' class='evolution-image' style='max-width: 100px;'>";
        echo "<p>" . $evo['NomPokemon'] . "</p>";
        echo "</a>";
    }
} else {
    echo "<p>Pas d'évolution</p>";
}
echo "</div>";


echo "</div>";

$databaseConnection->close();
require_once("footer.php");
?>
