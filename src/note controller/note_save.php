<?php 
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");
    

    // Funkcija za dodavanje novog unosa u tablicu bilješki
    function fill_note_table($user_id, $category, $title, $content, $date, $deadline) {
        global $conn;

        // SQL upit za dodavanje nove bilješke u bazu podataka
        $sql_note = "INSERT INTO note (user_id, category_id, title, content, created_at, deadline)
            VALUES (?, ?, ?, ?, ?, ?)";
        $type_specifiers = "iissss";
        $params_array = [$user_id, $category, $title, $content, $date, $deadline];
        $return_res = false;
        
        // Izvrši pripremljeni SQL upit za dodavanje bilješke, vrati true ako uspije, inače false
        return exec_prep_stmt($conn, $sql_note, $type_specifiers, $params_array, $return_res);
    }

    // Funkcija za dodavanje novog unosa u tablicu podsjetnika
    function fill_reminder_table($note_id, $user_id, $reminder_time) {
        global $conn;

        // SQL upit za dodavanje novog podsjetnika u bazu podataka
        $sql_reminder = "INSERT INTO reminder (note_id, user_id, reminder_time)
            VALUES (?, ?, ?)";
        $type_specifiers = "iis";
        $params_array = [$note_id, $user_id, $reminder_time];
        $return_res = false;

        // Izvrši pripremljeni SQL upit za dodavanje podsjetnika
        return exec_prep_stmt($conn, $sql_reminder, $type_specifiers, $params_array, $return_res);
    }

    // Funkcija za dodavanje novog unosa u tablicu privitaka
    function fill_attachment_table($note_id) {
        global $conn;
        global $attachment_file;

        // Postavljanje varijable za provjeru uspješnosti postavljanja datoteke
        // I za provjeru uspješnosti izvršavanja upita
        $file_upload_file_query = false;

        // Dohvaćanje informacija o privitku (prilogu) iz super globalne varijable $_FILES
        $file_name = $attachment_file['name'];
        $file_tmp = $attachment_file['tmp_name'];
        $file_type = $attachment_file['type'];
        $file_size = $attachment_file['size'];
        $file_error = $attachment_file['error'];

        // Putanja za pohranu privitka
        $attachment_path = "../../user data/" . $_SESSION["username"] . "/" . $file_name; 

        // SQL upit za dodavanje novog privitka u bazu podataka
        $sql_attachment = "INSERT INTO attachment (note_id, attachment)
            VALUES (?, ?)";
        $type_specifiers = "is";
        $params_array = [$note_id, $attachment_path];
        $return_res = false;

        // Provjera uspješnosti postavljanja datoteke
        if ($file_error === 0) {
            if(move_uploaded_file($file_tmp, $attachment_path)) {
                // Ako se uspješno postavi datoteka i ako se izvrši upit za dodavanje privitka u bazu
                if(exec_prep_stmt($conn, $sql_attachment, $type_specifiers, $params_array, $return_res)) {
                    // Varijabla koja govori o uspješnosti uploada i upit je true
                    $file_upload_file_query = true;
                }
            }
        }  

        return $file_upload_file_query;
    }

    // Putanja za preusmjeravanje nakon dodavanja bilješke
    $public_index = "../../public/";

    // Dohvaćanje korisničkog ID-a iz sesije i ostalih podataka iz POST zahtjeva
    $user_id = $_SESSION["user_id"];
    $category = $_POST["category"];
    $title = $_POST["title"];
    $content = $_POST["content"];
    $date = date("Y-m-d H:i:s");
    $deadline = $_POST["deadline"];
    if($_POST["deadline"] === "") { $deadline = null; } 
    $reminder_time = $_POST["reminder_time"];
    $attachment_file = $_FILES["attachment"];
    $status_success = "status=succes";

    // Provjera postoji li već bilješka s istim naslovom
    if(note_title_exists($title)) {
        // Ako postoji, dodaj naslovu nasumičan broj (0 - 999)
        $random_number = rand(0, 999);
        $title .= "({$random_number})";
    }

    // Dodavanje nove bilješke u bazu podataka
    if(fill_note_table($user_id, $category, $title, $content, $date, $deadline)) {
        $note_id = get_note_id($title);

        // Dodavanje podsjetnika ako je upisan datum podsjetnika
        if($reminder_time !== "" && fill_reminder_table($note_id, $user_id, $reminder_time)) {
            $status_success .= "&reminder_set";
        }

        // Dodavanje privitka ako je poslan
        if($attachment_file["error"] === 0 && fill_attachment_table($note_id)) {
            $status_success .= "&file_set";
        }

        // Preusmjeri korisnika s porukom o uspjehu (parametar u URL-u)
        redirect($public_index, $status_success);
    } else {
        // Preusmjeri korisnika s porukom o neuspjehu (parametar u URL-u)
        redirect($public_index, "status=error");
    }



    

    
