<?php
    // Uključi česte funkcije
    include_once("../src/common.php");

    function translate_cat($cat) {
        switch($cat) {
            case "Personal":
                $cat = "Osobno";
                break;
            case "Work":
                $cat = "Posao";
                break;
            case "Study":
                $cat = "Učenje";
                break;
            case "Shopping":
                $cat = "Kupovina";
                break;
            case "Health":
                $cat = "Zdravlje";
                break;
            case "Fitness":
                $cat = "Fitness";
                break;
            case "Home": 
                $cat = "Dom";
                break;
            case "Finance":
                $cat = "Financije";
                break;
            case "Travel":
                $cat = "Putovanje";
                break;
            case "Recipe":
                $cat = "Recepti";
                break;
            case "Uncategorized":
                $cat = "Bez kategorije";
                break;
            
            default: 
                break;
        }

        return $cat;
    }

    $sql = "SELECT note_category.name, COUNT(*) AS 'Broj bilješki' FROM note
        INNER JOIN note_category 
        ON note.category_id = note_category.category_id
        WHERE user_id = ?
        GROUP BY note_category.name";
    $type_spec = "i";
    $param = [$_SESSION["user_id"]];
    $return_res = true;

    $stat = exec_prep_stmt($conn, $sql, $type_spec, $param, $return_res);

    if(!empty($stat)) {
        foreach($stat as $row) {
            echo "<div>";
            foreach($row as $col => $val) {
                echo "<span>" .
                translate_cat($val) .
                "</span>";
            }
            echo "</div>";
        }
    } 
    
    