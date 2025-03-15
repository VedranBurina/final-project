<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // Dohvati naslov bilješke iz GET zahtjeva
    $title = $_GET["title"];
    // Dohvati ID bilješke na temelju naslova
    $note_id = get_note_id($title);
    
    // Prilagodi podatak za upis u tablicu, dohvati stanje završenosti obveze bilješke
    if($_GET["completion"] === "true") {
        $completion = 1;
    } else {
        $completion = 0;
    }
    
    // SQL upit za ažuriranje završetka bilješke
    $sql = "UPDATE note
        SET completion = ?
        WHERE note_id = ?";
    $type_spec = "ii";
    $params = [$completion, $note_id];
    $return_res = false;

    // Izvrši pripremljeni SQL upit (prepared statement)
    $exec_query = exec_prep_stmt($conn, $sql, $type_spec, $params, $return_res);

    // Provjeri je li upit uspješno izvršen
    if($exec_query) {
        // Ako je uspješan, vrati poruku o uspjehu (u obliku parametra u URL-u)
        echo "?note_completion_set=success";
    } else {
        // Ako nije uspješan, vrati poruku o neuspjehu (u obliku parametra u URL-u)
        echo "?note_completion_set=fail";
    }



    