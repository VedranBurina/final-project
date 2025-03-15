<?php
    // Uključi datoteku za povezivanje s bazom podataka
    include_once("../db connection/db_connection.php");
    // Uključi česte funkcije
    include_once("../common.php");

    // Dohvati e-poštu korisnika iz POST zahtjeva
    $user_email = $_POST["user_email"];
    // Dohvati lozinku korisnika iz POST zahtjeva
    $password = $_POST["password"];
    // Putanja do stranice za autentikaciju
    $auth_path = "../../public/authentication/index.php";
    // Putanja do glavne stranice
    $main_path = "../../public/index.php";

    // SQL upit za prijavu korisnika
    $sql_login = "SELECT * FROM user
        WHERE email = ? OR username = ?";
    // Specifikatori tipova podataka za parametre upita
    $type_specifier = "ss";
    // Parametri za upit (e-pošta i korisničko ime)
    $params = [$user_email, $user_email];
    // Definicija povratnih rezultata (true - vrati rezultat upita)
    $return_res = true;

    // Izvrši pripremljeni SQL upit za prijavu korisnika
    $res_login = exec_prep_stmt($conn, $sql_login, $type_specifier, $params, $return_res);

    // Provjeri je li rezultat upita nije prazan
    if(!empty($res_login)) {
        // Dohvati prvi redak rezultata
        $res_login_row = $res_login[0];

        // Provjeri odgovara li unesena lozinka hashu lozinke u bazi podataka
        if(password_verify($password, $res_login_row["password_hash"])) {
            // Pokreni sesiju
            session_start();
            // Postavi korisnički ID u sesiju
            $_SESSION["user_id"] = $res_login_row["user_id"];
            // Postavi korisničko ime u sesiju
            $_SESSION["username"] = $res_login_row["username"];
            // Postavi ime korisnika u sesiju
            $_SESSION["first_name"] = $res_login_row["first_name"];

            // Preusmjeri korisnika na glavnu stranicu (null - bez statusa)
            redirect($main_path, null);
        } else {
            // Ako lozinke ne odgovaraju, preusmjeri korisnika na stranicu za autentikaciju s odgovarajućim statusom
            redirect($auth_path, "status=login_failed");
        }  
    } else {
        // Ako nema rezultata upita, preusmjeri korisnika na stranicu za autentikaciju s odgovarajućim statusom
        redirect($auth_path, "status=login_failed");
    }



    