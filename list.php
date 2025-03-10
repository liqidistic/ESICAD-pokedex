<?php
require_once("database-connection.php");
require_once("head.php");

$sql = "SELECT 
            p.IdPokemon, p.NomPokemon, p.UrlPhoto, p.PtsVie, p.PtsDefense, 
            p.PtsVitesse, p.PtsSpeciaux, p.DateAjout, 
            t1.NomType AS Type1, t2.NomType AS Type2 
        FROM pokemon p
        LEFT JOIN typepokemon t1 ON p.IdType1 = t1.IdType
        LEFT JOIN typepokemon t2 ON p.IdType2 = t2.IdType
        ORDER BY p.IdPokemon ASC";

$result = $databaseConnection->query($sql);

echo "<table class='pokemon-table'>";
echo "<tr>";

$count = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($count % 4 == 0 && $count > 0) {
            echo "</tr><tr>";
        }
        echo "<td class='pokemon-card'>";
        
        echo "<a href='pokemon_detail.php?id=" . $row["IdPokemon"] . "' class='pokemon-link'>";
        
        echo "<img src='" . $row["UrlPhoto"] . "' alt='" . $row["NomPokemon"] . "' class='pokemon-image' />";
        echo "<h3>" . $row["NomPokemon"] . "</h3>";
        echo "<p><strong>Type :</strong> " . $row["Type1"];
        if (!empty($row["Type2"])) {
            echo " / " . $row["Type2"];
        }
        echo "</p>";
        echo "<p>Vie: " . $row["PtsVie"] . " | Défense: " . $row["PtsDefense"] . "</p>";
        echo "<p>Vitesse: " . $row["PtsVitesse"] . " | Spéciaux: " . $row["PtsSpeciaux"] . "</p>";
        echo "</a>";
        echo "</td>";

        $count++;
    }
    echo "</tr>";
}

echo "</table>";

$databaseConnection->close();


require_once("footer.php");
?>