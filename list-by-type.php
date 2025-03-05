<!-- 
    Ce fichier représente la page de liste par type de pokémon du site.  
    A REMPLACER PAR VOTRE CODE POUR CHARGER ET AFFICHER DANS UN TABLEAU LA LISTE DES POKEMONS CLASSES PAR LEUR TYPE, PUIS PAR LEUR NOM.
    CHAQUE POKEMON DOIT ETRE CLIQUABLE POUR NAVIGUER SUR UNE PAGE OU L'ON AFFICHE SON IMAGE ET L'ENSEMBLE DE SES CARACTERISTIQUES
-->
<?php
require_once("database-connection.php");
?>  

<?php
require_once("head.php");
?>
<?php

// Définition des couleurs pour chaque type 
$colors = [
    "Feu" => "#ff6b6b", "Eau" => "#3498db", "Plante" => "#2ecc71", "Electrique" => "#f1c40f",
    "Glace" => "#74b9ff", "Combat" => "#d63031", "Psy" => "#e84393", "Roche" => "#7f8c8d",
    "Spectre" => "#6c5ce7", "Dragon" => "#0984e3","Normal" => "#dfe6e9", "Poison" => "#a29bfe", 
    "Vol" => "#81ecec","Sol" => "#e67e22", "Insecte" => "#27ae60"
];
// Requete SQL
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

        //  Encadré visuel pour chaque type
        echo "<div style='margin: 30px 0; padding: 15px; background-color: $bgColor; 
                color: white; text-align: center; border-radius: 10px;
                box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);'>";
        echo "<h2 style='margin: 0; font-size: 24px;'>" . $nomType . "</h2>";
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
        echo "<table style='width: 100%; text-align: center; border-collapse: collapse; border: none;'>";
        echo "<tr>"; // Début de la première ligne
        $count = 0;

        if ($resultPokemon->num_rows > 0) {
            while ($row = $resultPokemon->fetch_assoc()) {
                if ($count % 4 == 0 && $count > 0) {
                    echo "</tr><tr>"; // Retour à la ligne tous les 4 Pokémon
                }

                echo "<td style='width: 25%; padding: 15px; border: 2px solid #ccc; 
                        border-radius: 10px; text-align: center; 
                        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); background: #f9f9f9;'>";

                // Rendre la case cliquable ; Conserve le style et evite le soulignement grace à "text-decoration..."
                echo "<a href='pokemon_detail.php?id=" . $row["IdPokemon"] . "'  
                style='text-decoration: none; color: inherit; display: block;'>";
                //Affichage des information des Pokemons
                echo "<img src='" . $row["UrlPhoto"] . "' alt='" . $row["NomPokemon"] . "' 
                    style='width: 100%; height: 150px; object-fit: contain; border-radius: 20px;' />";
                echo "<h3 style='margin: 5px 0;'>" . $row["NomPokemon"] . "</h3>";
                echo "<p><strong>Type :</strong> " . $row["Type1"];
                if (!empty($row["Type2"])) {
                    echo " / " . $row["Type2"];
                }

                $count++;
            }
            echo "</a>"; //Fin du lien
            echo "</tr>"; // Fin du dernier rang
    
        }

        echo "</table>";
    }
}


?>

<?php
require_once("footer.php");
?>