<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // Dobivanje naslova bilješke iz URL-a
    $title = $_GET["title"];

    // Putanja za preusmjeravanje nakon brisanja bilješke
    $path = "../../public/";

    // Funkcija za brisanje bilješke iz baze podataka i brisanje privitka iz user data mape
    function delete_note($note_id) {
        global $conn;

        // SQL upit za dobavljanje putanje prema privitku koji je priložen bilješci za brisanje
        $sql_del_file = "SELECT attachment FROM attachment WHERE note_id = ?";
        $del_file_return_res = true;

        // SQL upiti za brisanje podsjetnika, privitaka i same bilješke iz baze podataka
        $sql_delete_reminder = "DELETE FROM reminder WHERE note_id = ?";
        $sql_delete_attachment = "DELETE FROM attachment WHERE note_id = ?";
        $sql_delete_note = "DELETE FROM note WHERE note_id = ?";

        // Tip specifikatora za pripremljene izjave za brisanje
        $del_type_specifier = "i";

        // Parametri za pripremljene izjave za brisanje
        $del_param = [$note_id];

        // Varijabla za vraćanje uspješnosti brisanja
        $del_res_return = false;


        // Izvršavanje pripremljenih SQL upita za dobivanje putanje prema privitku koji se briše
        $del_file = exec_prep_stmt($conn, $sql_del_file, $del_type_specifier, $del_param, $del_file_return_res);
        // Ako nije bool (postoji) onda vrati ime attachmenta
        if(!is_bool($del_file)) 
        {
            $del_file = $del_file[0]["attachment"];
        }

        // Izvršavanje pripremljenih SQL upita za brisanje
        $del_rem = exec_prep_stmt($conn, $sql_delete_reminder, $del_type_specifier, $del_param, $del_res_return);
        $del_att = exec_prep_stmt($conn, $sql_delete_attachment, $del_type_specifier, $del_param, $del_res_return);
        $del_note = exec_prep_stmt($conn, $sql_delete_note, $del_type_specifier, $del_param, $del_res_return);
        
        /*
            Da bi MySQL dozvolio brisanje bilješke ne smiju postojati reference prema ID-u te bilješke
            zato je dovoljno provjeriti je li bilješka ($del_note) izbrisana jer to znači da su ostali 
            upiti za brisanje uspjeli
        */
        if($del_note && file_exists($del_file)) { 
            // Ako postoji file (privitak) uz bilješku
            if(unlink($del_file)) {
                // File (privitak) se mora izbrisati da funkcija vrati true
                return true; 
            } else { return false; } // Inače false  
        }
        if($del_note && !file_exists($del_file)) {
            // Ako ne postoji privitak dovoljno je da se upit izvrši ($del_note)
            return true;
        } else { return false; } 
    }
    

    // Provjera jesu li svi koraci za brisanje bilješke uspješno izvršeni
    if(delete_note(get_note_id($title))) {
        // Preusmjeri korisnika i resetiraj parametre URL-a
        redirect($path, "?"); 
    } else {
        // Preusmjeri korisnika i resetiraj parametre URL-a
        redirect($path, "?"); 
    }
