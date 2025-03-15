<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // Funkcija koja provjerava je li korisničko ime zauzeto
    function check_username_taken($username) {
        global $conn;

        // Ako je novo korisničko ime isto kao staro, vrati false
        if($username === $_SESSION["old_username"]) {
            return false;
        }

        // SQL upit za provjeru zauzetosti korisničkog imena
        $sql = "SELECT username FROM user
            WHERE username = ?";
        $type_spec = "s";
        $param = [$username];
        $return_res = false;

        // Izvrši pripremljeni SQL upit
        $username_taken = exec_prep_stmt($conn, $sql, $type_spec, $param, $return_res);

        // Upit vrati TRUE ako ima redaka (korisničko ime je zauzeto)
        return $username_taken;
    }

    // Funkcija za upload profilne slike
    function upload_pfp($profile_picture) {
        // Varijabla pohranjuje uspješnost uploadanja, početno FALSE
        $pfp_upload = false;

        // Prvo dobivanje informacije samo o imenu
        $file_name = $profile_picture["name"];

        // Ako postoji nova profilna slika
        if($file_name !== "") {
            // Ako postoji stara profilna slika, obriši je
            if($_SESSION["old_profile_picture"] != null) {
                unlink($_SESSION["old_profile_picture"]);
            }

            // Dobivanje informacija o novoj profilnoj slici
            $file_tmp = $profile_picture["tmp_name"];
            $file_type = $profile_picture["type"];
            $file_size = $profile_picture["size"];
            $file_error = $profile_picture["error"];
            $pfp_path = "../../user data/" . $_SESSION["old_username"] . "/" . $file_name;

            // Ako nema greške prilikom uploada slike
            if($file_error === 0) {
                // Pomakni sliku na odredišnu lokaciju
                // Ako uspije $pfp_upload je TRUE, inače FALSE
                $pfp_upload = move_uploaded_file($file_tmp, $pfp_path);
            }
            return $pfp_upload;
        } 
        return $pfp_upload;
    }

    // Dohvaćanje podataka iz POST zahtjeva
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $username = $_POST["username"];
    $profile_picture = $_FILES["profile_picture"]; 
    $updated_at = date("Y-m-d H:i:s");
    $path = "../../public/";

    // Provjera zauzetosti korisničkog imena
    if(!check_username_taken($username)) {
        // Promjeni korisničko ime u sesiji
        $_SESSION["username"] = $username;

        // Ako nije postavljena nova profilna slika
        if($profile_picture["name"] === "" &&
            $_SESSION["old_profile_picture"] != null) {
            // Onda ostavi staru profilnu sliku
            $pfp = explode("/", $_SESSION["old_profile_picture"])[4];

            // Putanja za novu profilnu
            $pfp_path = "../../user data/" . $_SESSION["username"] . "/" . $pfp;
        } elseif($profile_picture["name"] !== "") { 
            // Ako je postavljena onda postavi novu profilnu sliku
            $pfp = $profile_picture["name"]; 

            // Putanja za novu profilnu
            $pfp_path = "../../user data/" . $_SESSION["username"] . "/" . $pfp;
        } else {
            // Inače ne postoji stara ni nova profilna slika
            // Putanja za novu profilnu sliku je null
            $pfp_path = null;
        }

        // SQL upit za ažuriranje korisničkih podataka
        $sql_edit = "UPDATE user
            SET first_name = ?,
                last_name = ?,
                username = ?,
                profile_picture = ?,
                updated_at = ?
            WHERE user_id = ?";
        $edit_TS = "sssssi";
        $edit_params = [$first_name, $last_name, $username, $pfp_path, $updated_at, $_SESSION["user_id"]];
        $edit_return_res = false;

        $old_user_folder = null;
        $user_folder = null;

        // Dobavljanje imena user folder (mape korisnika)
        // Korisnikova mapa se mora preimenovati
        $old_user_folder = "../../user data/" . $_SESSION["old_username"];
        $user_folder = "../../user data/" . $username;

        // Upload nove profilne slike
        $upload_pfp = upload_pfp($profile_picture);
        
        // Preimenuj korisnikov folder
        rename($old_user_folder, $user_folder);

        // Ako korisničko ime nije zauzeto, ažuriraj korisničke podatke
        $edit_user = exec_prep_stmt($conn, $sql_edit, $edit_TS, $edit_params, $edit_return_res);

        // Ako je ažuriranje korisničkih podataka uspješno
        if($edit_user) {
            // Ako je upload profilne slike uspješan, preusmjeri na glavnu stranicu s parametrima u URL-u (status)
            if($upload_pfp) {
                redirect($path, "status=user_data_changed&pfp_set");
            }
            // Ako nije uploadana nova profilna slika, preusmjeri na glavnu stranicu s parametrom u URL-u
            redirect($path, "status=user_data_changed");
        } else {
            // Ako nije uspjelo ažuriranje korisničkih podataka, preusmjeri na glavnu stranicu s parametrom u URL-u
            redirect($path, "status=!user_data_changed");
        }
    } else {
        // Ako je korisničko ime zauzeto, preusmjeri na glavnu stranicu s parametrom u URL-u
        redirect($path, "status=username_duplicate");
    }
