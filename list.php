<!-- 
    Ce fichier représente la page de liste de tous les pokémons.
-->

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

$sql = "SELECT * FROM Pokemon ORDER BY IdPokemon ASC";
$result = mysqli_query($databaseConnection, $sql);

echo "<table style='width: 100%; border-spacing: 20px;'>"; // Table avec un espacement entre les cellules

$count = 0; // Compteur pour gérer les colonnes

if(mysqli_num_rows($result) > 0){
    echo "<tr>"; // Début de la première ligne de la table

    while($row = mysqli_fetch_assoc($result)){
        
        if ($count % 4 == 0 && $count > 0) { 
            echo "</tr><tr>";  // On crée une nouvelle ligne tous les 4 pokémons
        }
        
        // Affichage d'une cellule avec une bordure autour du Pokémon
        echo "<td style='width: 23%; text-align: center; padding: 10px; border: 2px solid #000; border-radius: 10px;'>";
        echo "<img src='" . $row["UrlPhoto"] . "' alt='" . $row["NomPokemon"] . "' style='max-width: 100%; max-height: 150px; margin-bottom: 10px;' />";
        echo "<p>" . $row["NomPokemon"] . "</p>";
        echo "<p>" . $row["IdPokemon"] . "</p>";
        echo "</td>"; // Fin de la cellule contenant le Pokémon

        $count++; // Incrémentation du compteur
    }

    echo "</tr>"; // Fermeture de la dernière ligne
    echo "</table>"; // Fermeture de la table
}
?>

<?php
require_once("footer.php");
?>