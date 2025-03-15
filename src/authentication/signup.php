<?php
    // Uključi datoteku za povezivanje s bazom podataka
    include_once("../db connection/db_connection.php");
    // Uključi česte funkcije
    include_once("../common.php");

    // Dobavi podatke iz POST zahtjeva
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $user = $_POST["user"];
    $email = $_POST["email"];
    // Kriptiraj dobivenu lozinku po "defaultnom" algoritmu
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $date = date('Y-m-d H:i:s');
    $auth_path = "../../public/authentication/index.php";

    // SQL upit za provjeru postojećih podataka
    $sql_existing_data = "SELECT * FROM user WHERE email = ? OR username = ?";
    $existing_data_type_specifier = "ss";
    $existing_data_params = [$email, $user];
    $existing_data_return_res = false;

    // Izvrši pripremljeni SQL upit za provjeru postojećih podataka
    $existing_data_query_success = exec_prep_stmt($conn, $sql_existing_data, $existing_data_type_specifier, 
        $existing_data_params, $existing_data_return_res);

    // Ako postoje podaci, preusmjeri korisnika s obavijesti (status u URL-u) da postoje
    if($existing_data_query_success) {
        redirect($auth_path, "status=duplicate");
    }

    // SQL upit za unos novog korisnika
    $sql_input = "INSERT INTO user(username, email, password_hash, first_name, last_name, created_at) 
        VALUES(?, ?, ?, ?, ?, ?)";
    $input_type_specifier = "ssssss";
    $input_params = [$user, $email, $password_hash, $name, $surname, $date];
    $input_return_res = false;

    // Izvrši pripremljeni SQL upit za unos novog korisnika
    $input_query_success = exec_prep_stmt($conn, $sql_input, $input_type_specifier, 
        $input_params, $input_return_res);

    // Ako je unos uspješan, stvori mapu za korisnika i preusmjeri s obavijesti o uspjehu
    if($input_query_success) {
        $folder_name = "../../user data/" . $user;
        mkdir($folder_name, 0700, false);
        redirect($auth_path, "status=success");
    } else {
        // Ako unos nije uspio, preusmjeri s odgovarajućom obavijesti
        redirect($auth_path, "status=signup_failed");
    }


    


    