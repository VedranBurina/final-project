<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // Funkcija za uređivanje (editiranje) postojeće bilješke
    function edit_note_table($category, $title, $content, $date, $deadline, $completion) {
        global $conn;
        global $old_note_id;

        // SQL upit za uređivanje postojeće bilješke u bazi podataka
        $sql_note = "UPDATE note 
            SET category_id = ?,
                title = ?,
                content = ?,
                updated_at = ?,
                deadline = ?,
                completion = ?
            WHERE note_id = ?";
        $type_specifiers = "issssii";
        $params_array = [$category, $title, $content, $date, $deadline, $completion, $old_note_id];
        $return_res = false;
        
        // Izvrši pripremljeni SQL upit za uređivanje bilješke, vrati true ako uspije, inače false
        return exec_prep_stmt($conn, $sql_note, $type_specifiers, $params_array, $return_res);
    }

    // Funkcija za uređivanje (editiranje) postojećeg podsjetnika u tablici podsjetnika
    // Napomena: svaki note ima samo jedan pripadajući podsjetnik
    function edit_reminder_table($user_id, $reminder_time) {
        global $conn;
        global $old_note_id;

        // SQL upit za brisanje postojećeg podsjetnika povezanog s bilješkom
        $sql_delete_reminder = "DELETE FROM reminder WHERE note_id = ?";
        $type_specifiers_del = "i";
        $params_array_del = [$old_note_id];
        $return_res_del = false;
        
        // Izvrši pripremljeni SQL upit za brisanje podsjetnika
        exec_prep_stmt($conn, $sql_delete_reminder, $type_specifiers_del, $params_array_del, $return_res_del);

        // SQL upit za dodavanje novog podsjetnika
        $sql_reminder = "INSERT INTO reminder(note_id, user_id, reminder_time)
            VALUES(?, ?, ?)";
        $type_specifiers = "iis";
        $params_array = [$old_note_id, $user_id, $reminder_time];
        $return_res = false;

        // Izvrši pripremljeni SQL upit za dodavanje novog podsjetnika
        return exec_prep_stmt($conn, $sql_reminder, $type_specifiers, $params_array, $return_res);
    }

    // Funkcija za uređivanje postojećeg privitka u tablici privitaka
    function edit_attachment_table($attachment_file, $old_attachment) {
        global $conn;
        global $old_note_id;

        // Postavljanje varijable za provjeru uspješnosti postavljanja datoteke
        $file_upload_file_query = false;

        // Dohvaćanje informacija o privitku (prilogu) iz super globalne varijable $_FILES
        $file_name = $attachment_file["name"];
        $file_tmp = $attachment_file["tmp_name"];
        $file_type = $attachment_file["type"];
        $file_size = $attachment_file["size"];
        $file_error = $attachment_file["error"];

        // Putanja za pohranu privitka
        $attachment_path = "../../user data/" . $_SESSION["username"] . "/" . $file_name; 

        // SQL upit za brisanje postojećeg privitka povezanog s bilješkom
        $sql_delete_attachment = "DELETE FROM attachment WHERE note_id = ?";
        $type_specifiers_del = "i";
        $params_array_del = [$old_note_id];
        $return_res_del = false;
        $del_old_attachment = exec_prep_stmt($conn, $sql_delete_attachment, $type_specifiers_del, $params_array_del, $return_res_del);

        // SQL upit za dodavanje novog privitka
        $sql_attachment = "INSERT INTO attachment(note_id, attachment)
            VALUES(?, ?)";
        $type_specifiers = "is";
        $params_array = [$old_note_id, $attachment_path];
        $return_res = false;
        $insert_new_attachment = exec_prep_stmt($conn, $sql_attachment, $type_specifiers, $params_array, $return_res);

        // Provjera uspješnosti postavljanja datoteke
        if ($file_error === 0) {
            if(move_uploaded_file($file_tmp, $attachment_path)) {
                // Ako se uspješno postavi datoteka i ako se izvrši upit za uređivanje privitka u bazi
                if($del_old_attachment && $insert_new_attachment) {
                    // Varijabla koja govori o uspješnosti uploada i upit je true
                    $file_upload_file_query = true;
                }
            }
        }
        
        // Ako privitak postoji, treba ga izbrisati iz mape user data
        // Ako su prethodni koraci uspješni, provjeri postoji li stari privitak u mapi user data
        if($file_upload_file_query && $old_attachment !== "nema priloga") {
            // Provjeri je li stari privitak (file) izbrisan iz mape user data
            if(unlink($old_attachment)) { return true; } // Ako je funkcija vraca true
            else { return false; } // Inače false
        }

        // Ako nije bilo potrebno izbrisati stari privitak, vrati uspješnost uploada i upita
        return $file_upload_file_query;
    }

    /* 
        Napomena: funkcije za uređivanje koriste upit za brisanje retka i za dodavanje retka
        jer podsjetnik ili privitak ne moraju prethodno koristiti, kad bi se koristio upit s
        ključnom riječi UPDATE onda bi se mogli promjeniti podsjetnik ili privitak samo ako 
        su prethodno dodani
    */

    // Putanja za preusmjeravanje nakon uređivanja bilješke
    $public_index = "../../public/";

    // Dohvaćanje korisničkog ID-a iz sesije i ostalih podataka iz POST zahtjeva
    $user_id = $_SESSION["user_id"];
    $category = $_POST["category"];
    $title = $_POST["title"];
    $content = $_POST["content"];
    $date = date("Y-m-d H:i:s");
    $deadline = $_POST["deadline"];
    // Prilagodba podatka za unos u tablicu
    $completion = $_POST["completion"] ?? 0;
    $reminder_time = $_POST["reminder_time"];
    $attachment_file = $_FILES["attachment"];
    $status_success = "status=success_edit";

    // Dobavlja se prethodno pospremljen ID bilješke, naslov, privitak iz sesije
    // To je ID koji "pokazuje" koje bilješka se uređuje
    $old_note_id = $_SESSION["old_note_id"]; 
    $old_note_title = $_SESSION["old_note_title"];
    $old_attachment = $_SESSION["old_attachment"];

    // Ako je novi naslov bilješke različit starmo
    if($title != $old_note_title) {
        // Provjera postoji li već bilješka s istim naslovom
        if(note_title_exists($title)) {
            // Ako postoji, dodaj naslovu nasumičan broj (0 - 999)
            $random_number = rand(0, 999);
            $title .= "({$random_number})";
        }
    }

    // Uređivanje postojeće bilješke u bazi podataka
    if(edit_note_table($category, $title, $content, $date, $deadline, $completion)) {
        // Uređivanje podsjetnika ako je promijenjen datum podsjetnika
        if($reminder_time !== "") {
            if(edit_reminder_table($user_id, $reminder_time)) {
                $status_success .= "&reminder_set";
            }    
        }

        // Uređivanje privitka ako je poslan
        if(isset($attachment_file) && $attachment_file["error"] === 0) {
            // Privitak se mijenja samo ako je postavljen
            if(edit_attachment_table($attachment_file, $old_attachment)) {
                $status_success .= "&file_set_file_deleted";
            } 
        }

        // Preusmjeri korisnika s porukom o uspjehu (parametar u URL-u)
        redirect($public_index, $status_success);
    } else {
        // Preusmjeri korisnika s porukom o neuspjehu (parametar u URL-u)
        redirect($public_index, "status=error_edit");
    }
