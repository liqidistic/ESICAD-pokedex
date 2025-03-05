<?php
require_once("database-connection.php");
?>

<?php
if (!$databaseConnection) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "<p>" . "Connected successfully" . "</p>" ; 
?>

<?php
require_once("head.php");
?>

<?php

$idPokemon = intval($_GET['id']); // Sécuriser l'entrée ??

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

// Couleur associée au type principal
$bgColor = isset($colors[$pokemon["Type1"]]) ? $colors[$pokemon["Type1"]] : "#7f8c8d";
?>

<?php

$result = $databaseConnection->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    //Placement de l'encart principal
    echo "<table style='width: 25%; padding: 15px; border: 2px solid #ccc; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                        border-radius: 10px; text-align: center; 
                        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); background: #f9f9f9;'>"; 
    //Encart pour le nom du pokemon, avec couleur selon type
    echo "<tr><td colspan='2' style='text-align: center; font-size: 22px; font-weight: bold; padding: 10px; background-color: " . 
    $colors[$row["Type1"]] . "; color: white; border-radius: 10px 10px 0 0;'>" . htmlspecialchars($row["NomPokemon"]) . "</td></tr>";
    //Image du pokemon
    echo "<tr><td colspan='2' style='text-align: center;'><img src='" . htmlspecialchars($row["UrlPhoto"]) . "' alt='" . htmlspecialchars($row["NomPokemon"]) . "' style='width: 200px;'></td></tr>";
    echo "<tr><th style='padding: 10px;'>ID</th><td style='padding: 10px;'>" . $row["IdPokemon"] . "</td></tr>";
    // Gestion des types avec couleurs associées
    echo "<tr><th style='padding: 10px;'>Type</th><td style='padding: 10px;'>";
    echo "<span style='color: " . $colors[$row["Type1"]] . "; font-weight: bold;'>" . $row["Type1"] . "</span>";
    if (!empty($row["Type2"])) {
        echo " / <span style='color: " . $colors[$row["Type2"]] . "; font-weight: bold;'>" . $row["Type2"] . "</span>";
    }
    echo "</td></tr>";
    //Informations du Pokemon
    echo "<tr><th style='padding: 10px;'>Points de Vie</th><td style='padding: 10px;'>" . $row["PtsVie"] . "</td></tr>";
    echo "<tr><th style='padding: 10px;'>Défense</th><td style='padding: 10px;'>" . $row["PtsDefense"] . "</td></tr>";
    echo "<tr><th style='padding: 10px;'>Vitesse</th><td style='padding: 10px;'>" . $row["PtsVitesse"] . "</td></tr>";
    echo "<tr><th style='padding: 10px;'>Spéciaux</th><td style='padding: 10px;'>" . $row["PtsSpeciaux"] . "</td></tr>";
    echo "</table>";
} else {
    echo "<p style='text-align: center;'>Aucun Pokémon trouvé.</p>";
}

$databaseConnection->close();
?>




<?php
require_once("footer.php");
?>