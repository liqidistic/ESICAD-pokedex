<!-- 
    Ce fichier représente la page de liste par type de pokémon du site.  
    A REMPLACER PAR VOTRE CODE POUR CHARGER ET AFFICHER DANS UN TABLEAU LA LISTE DES POKEMONS CLASSES PAR LEUR TYPE, PUIS PAR LEUR NOM.
    CHAQUE POKEMON DOIT ETRE CLIQUABLE POUR NAVIGUER SUR UNE PAGE OU L'ON AFFICHE SON IMAGE ET L'ENSEMBLE DE SES CARACTERISTIQUES
-->

<?php
require_once("database-connection.php");
require_once("head.php");

// Définition des couleurs pour chaque type 
$colors = [
    "Feu" => "#ff6b6b", "Eau" => "#3498db", "Plante" => "#2ecc71", "Electrique" => "#f1c40f",
    "Glace" => "#74b9ff", "Combat" => "#d63031", "Psy" => "#e84393", "Roche" => "#7f8c8d",
    "Spectre" => "#6c5ce7", "Dragon" => "#0984e3", "Normal" => "#dfe6e9", "Poison" => "#a29bfe", 
    "Vol" => "#81ecec", "Sol" => "#e67e22", "Insecte" => "#27ae60"
];

// Requête SQL
$sqlTypes = "SELECT DISTINCT t.NomType, t.IdType 
             FROM typepokemon t 
             JOIN pokemon p ON t.IdType = p.IdType1 OR t.IdType = p.IdType2 
             ORDER BY t.IdType ASC";
$resultTypes = $databaseConnection->query($sqlTypes);

if ($resultTypes->num_rows > 0) {
    while ($type = $resultTypes->fetch_assoc()) {
        $nomType = $type["NomType"];
        $idType = $type["IdType"];

        // Récupérer la couleur associée au type, sinon couleur par défaut
        $bgColor = isset($colors[$nomType]) ? $colors[$nomType] : "#7f8c8d";

        // Encadré visuel pour chaque type
        echo "<div class='type-container' style='background-color: $bgColor;'>";
        echo "<h2>" . $nomType . "</h2>";
        echo "</div>";

        $sqlPokemon = "SELECT 
                            p.IdPokemon, p.NomPokemon, p.UrlPhoto, p.PtsVie, p.PtsDefense, 
                            p.PtsVitesse, p.PtsSpeciaux, 
                            t1.NomType AS Type1, t2.NomType AS Type2 
                        FROM pokemon p
                        LEFT JOIN typepokemon t1 ON p.IdType1 = t1.IdType
                        LEFT JOIN typepokemon t2 ON p.IdType2 = t2.IdType
                        WHERE p.IdType1 = $idType OR p.IdType2 = $idType
                        ORDER BY p.IdPokemon ASC";
        
        $resultPokemon = $databaseConnection->query($sqlPokemon);

        // Tableau pour afficher les Pokémon du type en question
        echo "<table class='pokemon-table'>";
        echo "<tr>";
        $count = 0;

        if ($resultPokemon->num_rows > 0) {
            while ($row = $resultPokemon->fetch_assoc()) {
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
                echo "</a>";
                echo "</td>";

                $count++;
            }
            echo "</tr>";
        }

        echo "</table>";
    }
}

require_once("footer.php");
?>