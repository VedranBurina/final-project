<?php
    // Uključi datoteku za povezivanje s bazom podataka
    include_once("../db connection/db_connection.php");
    // Uključi česte funkcije
    include_once("../common.php");

    // Putanja za oporavak lozinke
    $recovery_path = "../../public/authentication/password recovery/index.php";
    // E-pošta korisnika
    $user_email = $_POST["email"];
    // Generiraj slučajni token (nasumični binarni pretvori u heksadekadski zapis)
    $random_token = bin2hex(random_bytes(32));
    // Vrijeme isteka tokena (1 sat od sada)
    $token_expiration = date("Y:m:d H:i:s", strtotime("+1 hour"));
    // Vrsta tokena
    $token_type = "password_reset";

    // SQL upit za provjeru postoji li korisnik s danom e-poštom
    $sql_email = "SELECT user_id FROM user WHERE email = ?";
    $email_type_specifier = "s";
    $email_param = [$user_email];
    $email_return_res = true;

    // Izvrši pripremljeni SQL upit za e-poštu
    $res_email = exec_prep_stmt($conn, $sql_email, $email_type_specifier, $email_param, $email_return_res);

    // Ako postoji korisnik s danom e-poštom (polje rezultata nije prazno)
    if(!empty($res_email)) {
        // Dobavi ID korisnika
        $user_id = $res_email[0]["user_id"];

        // SQL upit za umetanje tokena u bazu podataka
        $sql_token = "INSERT INTO token(user_id, token_type, token, token_expiration) VALUES(?, ?, ?, ?)";
        $token_type_specifier = "isss";
        $token_params = [$user_id, $token_type, $random_token, $token_expiration];
        $token_return_res = false;

        // Izvrši pripremljeni SQL upit za token
        $token_query_success = exec_prep_stmt($conn, $sql_token, $token_type_specifier, $token_params, $token_return_res);

        // Ako je upit uspješan, preusmjeri korisnika s obavijesti o uspjehu (status u urlu = success)
        if($token_query_success) {
            redirect($recovery_path, "status=success");
        } else {
            // Ako upit nije uspio, preusmjeri korisnika s odgovarajućom obavijesti o grešci
            redirect($recovery_path, "status=failed");
        }
    } else {
        // Ako korisnik s danom e-poštom ne postoji, preusmjeri korisnika s odgovarajućom obavijesti
        redirect($recovery_path, "status=failed_email");
    }



    

