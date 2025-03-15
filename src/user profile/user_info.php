<?php
    // Započni sesiju
    session_start();

    // Funkcija za ispis polja ali uz prijevod naziva stupca
    function translate_output($translated, $translation, $value) {
        return "
        <div>
            <input type=\"text\" name=\"{$translated}\" id=\"{$translated}\" autocomplete=\"on\" value=\"{$value}\">
            <span>$translation</span>
        </div>
        ";
    }

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // SQL upit za dohvaćanje korisničkih podataka
    $sql_user = "SELECT first_name, last_name, username, email, profile_picture
        FROM user
        WHERE user_id = ?";
    $user_TS = "i";
    $user_param = [$_SESSION["user_id"]];
    $return_res = true;

    // Izvrši pripremljeni SQL upit
    $user_info = exec_prep_stmt($conn, $sql_user, $user_TS, $user_param, $return_res)[0];

    // Provjera postojanja korisničkih podataka
    if(!empty($user_info)) {
        // Postavljanje starih vrijednosti korisničkog imena i profilske slike u sesiju
        $_SESSION["old_username"] = $user_info["username"];
        $_SESSION["old_profile_picture"] = $user_info["profile_picture"] ?? null;

        // Ispisivanje HTML forme za uređivanje korisničkih podataka
        echo "<form action=\"../src/user profile/edit_user_info.php\" method=\"POST\" enctype=\"multipart/form-data\">
        <h2>Postavke računa</h2>";
        foreach($user_info as $column => $value) {
            // Ako je stupac 'email', ispiši input polje onemogućeno za uređivanje
            if($column === "email") {
                echo "
                    <div>
                        <input type=\"text\" name=\"{$column}\" id=\"{$column}\" disabled value=\"{$value}\">
                        <span>E-mail</span>
                    </div>
                ";
            }
            // Ako je stupac 'profile_picture', ispiši input polje za odabir datoteke
            // Profična slika se selektira klikom na label (input je skriven)
            elseif($column === "profile_picture") {    
                // U labelu će se nalaziti profilna slika, isto tako selektirana profilna slika
                // Input ima atribut accept s vrijednošću image/* tako da prihavaća samo slike
                echo "
                    <div>
                        <label for=\"profile_picture\" id=\"pfp_label\"></label>
                        <input type=\"file\" name=\"profile_picture\" id=\"profile_picture\" accept=\"image/*\" hidden> 
                    </div>
                ";
            } 
            // Inače, ispiši input polje za uređivanje ostalih korisničkih podataka
            elseif($column === "first_name") {
                echo translate_output($column, "Ime", $value);
            }
            elseif($column === "last_name") {
                echo translate_output($column, "Prezime", $value);
            }
            else {
                echo translate_output($column, "Korisničko ime", $value);
            }   
        }
        // Ispisivanje gumba za uređivanje korisničkih podataka
        echo "<button id=\"edit-user-info\">Pohrani promjene</button></form>";
    } else {
        // Ako nije moguće dohvatiti korisničke podatke, ispiši poruku o grešci
        echo "<div>Došlo je do greške kod prikazivanja vaših podataka.</div>";
    }

