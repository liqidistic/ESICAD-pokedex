<?php
require_once("head.php");
require_once("database-connection.php");
require_once("functions.php");

$recherche = isset($_GET["q"]) ? trim($_GET["q"]) : "";
$success = "";
$error = "";

// Ajouter la recherche aux favoris si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_search"]) && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $searchQuery = $_POST["search_query"];
    $resultMessage = addFavoriteSearch($userId, $searchQuery, $databaseConnection);
    if (strpos($resultMessage, "Erreur") === false) {
        $success = $resultMessage;
    } else {
        $error = $resultMessage;
    }
}

if (!empty($recherche)) {
    $sql = "SELECT p.*, t.NomType, t.IdType
            FROM pokemon p
            LEFT JOIN typepokemon t ON t.IdType = p.IdType1 OR t.IdType = p.IdType2
            WHERE p.NomPokemon LIKE ? OR t.NomType LIKE ?
            ORDER BY p.IdPokemon ASC";
    $stmt = $databaseConnection->prepare($sql);
    $searchTerm = "%" . $recherche . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Résultats pour : " . htmlspecialchars($recherche) . "</h2>";
    if (isset($_SESSION['user_id'])) {
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='search_query' value='" . htmlspecialchars($recherche) . "'>";
        echo "<button type='submit' name='save_search'>Ajouter aux favoris</button>";
        echo "</form>";
    }
    if (!empty($success)) echo "<p style='color:green;'>$success</p>";
    if (!empty($error)) echo "<p style='color:red;'>$error</p>";

    echo "<table style='width: 100%; border-spacing: 10px;'>";
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
            echo "</a>";
            echo "</td>";
            $count++;
        }
        echo "</tr>";
    } else {
        echo "<tr><td>Aucun résultat trouvé.</td></tr>";
    }
    echo "</table>";
    $stmt->close();
}

require_once("footer.php");
?>