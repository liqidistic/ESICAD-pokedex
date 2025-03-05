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


$sql = "SELECT 
            p.IdPokemon, p.NomPokemon, p.UrlPhoto, p.PtsVie, p.PtsDefense, 
            p.PtsVitesse, p.PtsSpeciaux, p.DateAjout, 
            t1.NomType AS Type1, t2.NomType AS Type2 
        FROM pokemon p
        LEFT JOIN typepokemon t1 ON p.IdType1 = t1.IdType
        LEFT JOIN typepokemon t2 ON p.IdType2 = t2.IdType
        ORDER BY p.IdPokemon ASC";

$result = $databaseConnection->query($sql);

echo "<table style='width: 100%; text-align: center; border-collapse: collapse;'>";
echo "<tr>"; // Début de la première ligne

$count = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($count % 4 == 0 && $count > 0) {
            echo "</tr><tr>"; // Retour à la ligne tout les 4 Pokémon
        
        }
        echo "<td style='width: 25%; padding: 15px; border: 2px solid #ccc; 
                        border-radius: 10px; text-align: center; 
                        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); background: #f9f9f9;'>";

        // Rendre la case cliquable ; Conserve le style et evite le soulignement grace à "text-decoration..."
        echo "<a href='pokemon_detail.php?id=" . $row["IdPokemon"] . "'  
        style='text-decoration: none; color: inherit; display: block;'>";
        //Affichage des information des différents pokemons
        echo "<img src='" . $row["UrlPhoto"] . "' alt='" . $row["NomPokemon"] . "' style='width: 100%; height: 150px; object-fit: contain; border-radius: 20px;' />";
        echo "<h3>" . $row["NomPokemon"] . "</h3>";
        echo "<p><strong>Type :</strong> " . $row["Type1"];
        if (!empty($row["Type2"])) {
            echo " / " . $row["Type2"];
        }
        echo "</p>";
        echo "<p>Vie: " . $row["PtsVie"] . " | Défense: " . $row["PtsDefense"] . "</p>";
        echo "<p>Vitesse: " . $row["PtsVitesse"] . " | Spéciaux: " . $row["PtsSpeciaux"] . "</p>";
        echo "</td>";

        $count++;
    }
    echo "</a>"; //Fin du lien
    echo "</tr>"; //Fin du dernier rang
}

echo "</table>";

$databaseConnection->close();
?>


<?php
require_once("footer.php");
?>