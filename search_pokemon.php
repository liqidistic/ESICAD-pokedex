<?php
require_once("head.php");
require_once("database-connection.php")

$recherche = $_GET["q"];
$sql = "SELECT p.*, t.NomType, t.IdType
    FROM pokemon p
    LEFT JOIN typepokemon t ON t.IdType = p.IdType1 OR t.IdType = p.IdType2
    WHERE p.NomPokemon LIKE '%" . $recherche . "%' 
    OR t.NomType LIKE '%" . $recherche . "%'
    ORDER BY p.IdPokemon ASC
";
$result = mysqli_query($databaseConnection, $sql); 

echo "<table style='width: 100%; border-spacing: 10px;'>"; // Une table toute simple

$count = 0; // Compteur pour gérer les colonnes

if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
    
    if ($count % 4 == 0 && $count > 0) { 
        echo "</tr><tr>";  // On créé une ligne tout les 4 pokémons
    }
  echo "<td class='pokemon-card'>";
        
        echo "<a href='pokemon_detail.php?id=" . $row["IdPokemon"] . "' class='pokemon-link'>";
        
        echo "<img src='" . $row["UrlPhoto"] . "' alt='" . $row["NomPokemon"] . "' class='pokemon-image' />";
        echo "<h3>" . $row["NomPokemon"] . "</h3>";
        echo "</a>";
        echo "</td>";

    $count++; 
}
echo "</tr>"; 
echo "</table>";
}

require_once("footer.php");
?>