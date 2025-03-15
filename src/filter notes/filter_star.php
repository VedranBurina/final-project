<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../../src/db connection/db_connection.php");
    include_once("../../src/common.php");

    // Upit i parametri upita za filtriranje po zvjezdici
    $sql_notes_star = "SELECT note.title, note.created_at, note.updated_at, note.deadline, 
        note.completion, note.content, note_category.category_id
        FROM note INNER JOIN note_category ON note.category_id = note_category.category_id
        WHERE star = 1 AND user_id = ?";
    $type_spec = "i";
    $param = [$_SESSION["user_id"]];
    $return_res = true;
    
    // Izvršavanje upita odnosno "prepared statementa"
    $star_notes = exec_prep_stmt($conn, $sql_notes_star, $type_spec, $param, $return_res);

    // Provjeri da rezultati upita nisu prazni
    if(!empty($star_notes)) {
        // Iteriraj kroz rezultate upita (bilješke)
        foreach ($star_notes as $n) {
            // Dohvati podatke o bilješci
            $title = $n["title"]; 
            // Formatiraj datume i vrijeme, izreži zadnje 3 znamenke (god., mjesec, dan)
            $created = substr($n["created_at"], 0, -9); 
            $updated = substr($n["updated_at"], 0, -9); 
            $deadline = substr($n["deadline"], 0, -9);
            $completed = $n["completion"];

            $content = $n["content"];
            $category_id = $n["category_id"];

            // Obilježi checkbox na temelju upita 
            if($completed) { 
                $completed_checked = "checked"; 
            } else {
                $completed_checked = "";
            }

            $star_color = "yellow";

            // Generiraj HTML za prikaz bilješke i pripadajućih akcija
            $note_preview = generate_note_previw($created, $updated, $title, $content, $category_id, $star_color, 
                $deadline, $completed_checked);

            // Ispiši HTML za prikaz bilješki
            echo $note_preview;
        }
    } else {
        // Ako korisnik nema bilješki označenih zvjezdicom, ispiši poruku
        echo "<div>Još nemate bilješki sa zvjezdicom.</div>";
    }