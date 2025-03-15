<?php 
    // Uključi datoteku common.php koja sadrži česte (zajedničke) funkcije
    include_once("../src/common.php");

    // SQL upit za dohvaćanje općenitih podataka o bilješci za trenutnog korisnika
    $sql_note = "SELECT note.title, note.created_at, note.updated_at, note.deadline, 
        note.completion, note.star, note.content, note_category.category_id
        FROM note INNER JOIN note_category ON note.category_id = note_category.category_id
        WHERE user_id = ?";
    $note_type_spec = "i"; 
    $note_param = [$_SESSION["user_id"]]; 
    $note_return_res = true; 
    // Izvrši pripremljeni upit
    $note_res = exec_prep_stmt($conn, $sql_note, $note_type_spec, $note_param, $note_return_res); 

    // Provjeri da rezultati upita nisu prazni
    if (!empty($note_res)) {
        // Iteriraj kroz rezultate upita (bilješke)
        foreach ($note_res as $n) {
            // Dohvati podatke o bilješci
            $title = $n["title"]; 
            // Formatiraj datum na način da se ne prikazuju sekunde (nebitno za korisnika)
            $created = substr($n["created_at"], 0, -9);
            $updated = substr($n["updated_at"], 0, -9);
            $deadline = substr($n["deadline"], 0, -9);

            $completed = $n["completion"];
            $stared = $n["star"];
            $content = $n["content"];
            $category_id = $n["category_id"];

            // Postavi boju zvjezdice na temelju rezultata upita 
            if($stared) { 
                $star_color = "yellow"; 
            } else {
                $star_color = "none";
            }

            // Obilježi checkbox na temelju upita 
            if($completed) { 
                $completed_checked = "checked"; 
            } else {
                $completed_checked = "";
            }

            // Generiraj HTML za prikaz bilješke i pripadajućih akcija
            $note_preview = generate_note_previw($created, $updated, $title, $content, $category_id, $star_color, 
                $deadline, $completed_checked);

            // Ispiši HTML za prikaz bilješki
            echo $note_preview;
        }
    } else {
        // Ako korisnik nema bilješki, ispiši poruku
        echo "<div>Još nemate bilješki.</div>";
    }

