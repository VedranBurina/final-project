<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // Funkcija za dodavanje zvjezdive bilješci
    function note_star($note_id) {
        global $conn;

        // SQL upit za ažuriranje statusa zvjezdice bilješke
        $sql_star = "UPDATE note 
            SET star = CASE
                WHEN star = 0 THEN 1
                ELSE 0
            END
            WHERE note_id = ?";
        $type_specifier = "i";
        $params_array = [$note_id];
        $return_res = false;

        // Izvrši pripremljeni SQL upit za ažuriranje statusa zvjezdice, vrati uspješnost
        return exec_prep_stmt($conn, $sql_star, $type_specifier, $params_array, $return_res);
    }

    // Funkcija za provjeru stanja zvjezdice bilješke
    function check_star_state($note_id) {
        global $conn;

        // SQL upit za provjeru stanja zvjezdice bilješke
        $sql_check_star_state = "SELECT star FROM note WHERE note_id = ?";
        $type_specifier = "i";
        $params_array = [$note_id];
        $return_res = true;

        // Izvrši pripremljeni SQL upit za provjeru stanja zvjezdice
        $result = exec_prep_stmt($conn, $sql_check_star_state, $type_specifier, $params_array, $return_res);
        $star = $result[0]["star"];
        
        // Ispis HTML koda ovisno o stanju zvjezdice
        if($star == 1) {
            echo "<svg width=\"24\" height=\"24\" fill=\"yellow\" viewBox=\"0 0 24 24\">
                <path stroke=\"yellow\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M12 4.75L13.75 10.25H19.25L14.75 13.75L16.25 19.25L12 15.75L7.75 19.25L9.25 13.75L4.75 10.25H10.25L12 4.75Z\"></path>
                </svg>";
        } else {
            echo "<svg width=\"24\" height=\"24\" fill=\"none\" viewBox=\"0 0 24 24\">
                <path stroke=\"yellow\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M12 4.75L13.75 10.25H19.25L14.75 13.75L16.25 19.25L12 15.75L7.75 19.25L9.25 13.75L4.75 10.25H10.25L12 4.75Z\"></path>
                </svg>";
        }
    }

    // Dohvati naslov bilješke iz GET zahtjeva
    $title = $_GET["title"];

    // Dohvati ID bilješke putem naslova
    $note_id = get_note_id($title);

    // Provjeri postoji li bilješka s tim ID-om i ažuriraj status zvjezdice 
    if($note_id !== null && note_star($note_id)) {
        // Provjeri trenutno stanje zvjezdice i prikaži odgovarajući simbol
        check_star_state($note_id);
    } else {
        // Prikazuje poruku o pogrešci ako je došlo do problema
        echo "ERROR";
    }



