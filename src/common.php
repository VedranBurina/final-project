<?php
    // Funkcija za preusmjeravanje korisnika na drugu stranicu
    // $location - putanja na koju će se korisnik preusmjeriti
    // $status - dodatni status koji se može dodati u URL, opcionalno
    function redirect($location, $status) {
        // Provjerava je li $status string
        if (is_string($status)) {
            // Dodaje status u URL putanju
            header("Location: {$location}?{$status}");
        } else {
            // Ako $status nije string, preusmjeri bez dodavanja dodatnih podataka u URL
            header("Location: {$location}"); 
        }

        // Prekida izvođenje skripte nakon preusmjeravanja
        die;
    }

    // Funkcija za izvršavanje pripremljenih izjava (prepared statements) odnosno MySQL upita
    /* 
        Pripremljene izjave koriste se kako bi se spriječili napadi ubacivanjem SQL koda
        (injection attacks) te kako bi se osigurala sigurna obrada korisničkih ulaznih podataka. 
    */
    // $conn - objekt veze s bazom podataka
    // $sql - SQL upit koji treba izvršiti
    // $type_specifiers - specifikatori tipova podataka za parametre upita
    // $params_array - polje parametara za upit
    // $return_res - zastavica koja označava vraćanje rezultata upita
    function exec_prep_stmt($conn, $sql, $type_specifiers, $params_array, $return_res) {
        // Priprema SQL upita
        $stmt = $conn->prepare($sql);

        // Inicijalizacija varijable za spremanje rezultata upita
        $rows = [];

        // Polje za vezanje parametara upita
        // Prvi element je referenca specifikatora tipa podataka parametara "prepared statement"
        $bind_param_params = [&$type_specifiers];

        // Iteracija kroz parametre i dodavanje referenci u polje za vezanje parametara
        foreach ($params_array as &$param) { 
            $bind_param_params[] = &$param; 
        }

        // Polje za pozivanje funkcije za vezanje parametara
        $execute_array = [$stmt, "bind_param"];

        // Pozivanje funkcije za vezanje parametara upita
        call_user_func_array($execute_array, $bind_param_params);

        // Izvršavanje SQL upita
        $stmt->execute();

        // Dobivanje rezultata upita
        $res = $stmt->get_result();

        // Provjera jesu li zahvaćeni redovi upitom (prepared statement)
        // Za upite sa ključnim riječima: INSERT, UPDATE, DELETE
        $affected_rows = $stmt->affected_rows > 0;

        // Inicijalizacija varijable za broj redova rezultata upita
        $num_rows = false;

        // Ako rezultat upita nije boolean, provjerava se broj redova
        // Za upite sa ključnim riječima: SELECT
        if (!is_bool($res)) {
            $num_rows = $res->num_rows > 0;
        }

        // Provjera uvjeta za vraćanje rezultata upita
        if (($affected_rows || $num_rows) && $return_res) { 
            // Učitavanje redova rezultata u polje
            // Rezultati su asocijativno polje
            while ($row = $res->fetch_assoc()) { 
                $rows[] = $row; 
            }
            // Zatvaranje pripremljene izjave (prepared statement) i vraćanje rezultata
            $stmt->close();
            return $rows;
        } 
        
        // Provjera uvjeta za vraćanje rezultata upita kada nije potrebno vratiti rezultat
        if (($affected_rows || $num_rows) && !$return_res) {
            // Zatvaranje pripremljene izjave i vraćanje TRUE
            $stmt->close();
            return true;
        }
        
        // Zatvaranje pripremljene izjave i vraćanje FALSE ako nijedan od uvjeta nije ispunjen
        // Znači da upit nije utjecao ni na jedan redak ili nije vratio ni jedan redak
        $stmt->close();
        return false;
    }

    // Funkcija koja dobavlja id bilješke prema naslovu
    // $title - naslov bilješke čiji id se dohvaća
    function get_note_id($title) {
        global $conn;
        
        // SQL upit za dobivanje ID-a bilješke putem naslova
        $sql_note_id = "SELECT note_id FROM note WHERE title = ? AND user_id = ?";
        $type_specifiers = "si"; 
        $params_array = [$title, $_SESSION["user_id"]];
        $return_res = true;

        // Izvrši pripremljeni SQL upit za dobivanje ID-a bilješke
        $result = exec_prep_stmt($conn, $sql_note_id, $type_specifiers, $params_array, $return_res);
        return $result[0]["note_id"];
    }

    // Funkcija koja provjerava postojanje naslova bilješke u bazi podataka
    // $title - naslov bilješke koji se provjerava
    function note_title_exists($title) {
        // Dobivanje globalne veze s bazom podataka
        global $conn;

        // SQL upit za provjeru postojanja naslova bilješke
        $sql_title_exists = "SELECT title FROM note WHERE title = ? AND user_id = ?";
        
        // Specifikatori tipova podataka za parametre upita
        $type_specifiers = "si";
        
        // Polje parametara za upit
        $params_array = [$title, $_SESSION["user_id"]];
        
        // Zastavica koja označava vraćanje rezultata upita
        $return_res = false;
        
        // Izvršavanje pripremljenog SQL upita
        $title_exists = exec_prep_stmt($conn, $sql_title_exists, $type_specifiers, $params_array, $return_res);

        // Vraćanje rezultata provjere postojanja naslova bilješke
        return $title_exists;
    }

    // Funkcija za nedetaljni prikaz bilješke (samo "preview")
    function generate_note_previw($created, $updated, $title, $content, $category_id, $star_color, 
        $deadline, $completed_checked) {    

        // Postavljanje lokalnog vremena
        setlocale(LC_TIME, 'hr_HR.UTF-8');

        // Ako vrijednosti nisu postavljene ne prikazuj ih
        // Inače ih formatiraj i prikaži
        if($updated == null) {
            $updated_html = "<span></span>";
        } else {
            $updated = strtotime($updated);
            $updated = date("d.m.Y", $updated);

            $updated_html = "<span>Uređeno: {$updated}</span>";
        }
        if($deadline === null) {
            $deadline_html = "<span></span>";
        } else {
            $deadline = strtotime($deadline);
            $deadline = date("d.m.Y", $deadline);

            $deadline_html = "<span>Rok za izvršiti: <span>{$deadline}</span></span>";
        }
        $created = strtotime($created);
        $created = date("d.m.Y", $created);

        // Pridruži id-ju kategorije odgovarajuću vrijednost
        switch ($category_id) {
            case '1':
                $category_id = "Osobno";
                break;
            case '2':
                $category_id = "Posao";
                break;
            case '3':
                $category_id = "Učenje";
                break;
            case '4':
                $category_id = "Kupovina";
                break;
            case '5':
                $category_id = "Zdravlje";
                break;
            case '6':
                $category_id = "Fitness";
                break;
            case '7':
                $category_id = "Dom";
                break;
            case '8':
                $category_id = "Financije";
                break;
            case '9':
                $category_id = "Putovanje";
                break;
            case '10':
                $category_id = "Recepti";
                break;
            case '11':
                $category_id = "Bez kategorije";
                break;
            
            default:
                break;
        }

        /* 
        Atribut onclick="this.submit();" za neke forme čiji je dizajn
        kao da je cijela forma gumb tako da korisnik može kliknuti 
        bilo gdje na formi da ju preda
        */
        // Formiranje HTML-a za prikaz "previewa" bilješke
        $note_preview = "<div class=\"note-container\">
                <div class=\"dates\">
                    <span>{$created}</span>
                    {$updated_html}
                </div>
                <h2>{$title}</h2>
                <div class=\"content\">{$content}</div>
                <div class=\"category\">Kategorija: <span>{$category_id}</span></div>
                <div class=\"completion-data\">
                    <span>{$deadline_html}</span>
                    <span>
                        Obveza izvršena <input type=\"checkbox\" class=\"note-completion\" {$completed_checked}>
                        <input type=\"text\" class=\"note-completion-title\" value=\"{$title}\" hidden>
                    </span>
                </div>
                <div class=\"controls\">
                    <form action=\"../src/note controller/select_note_to_edit.php\" method=\"GET\" onclick=\"this.submit();\">
                        <input type=\"hidden\" name=\"title\" id=\"title\" value=\"{$title}\">
                        <svg width=\"24\" height=\"24\" fill=\"none\" viewBox=\"0 0 24 24\">
                            <path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M4.75 19.25L9 18.25L18.2929 8.95711C18.6834 8.56658 18.6834 7.93342 18.2929 7.54289L16.4571 5.70711C16.0666 5.31658 15.4334 5.31658 15.0429 5.70711L5.75 15L4.75 19.25Z\"></path>
                            <path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M19.25 19.25H13.75\"></path>
                        </svg>
                        <button type=\"submit\">Uredi</button>
                    </form>
                    <form action=\"../src/note controller/note_delete.php\" method=\"GET\" onclick=\"this.submit();\">
                        <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                            <path d=\"M5.75 7.75L6.59115 17.4233C6.68102 18.4568 7.54622 19.25 8.58363 19.25H14.4164C15.4538 19.25 16.319 18.4568 16.4088 17.4233L17.25 7.75H5.75Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>
                            <path d=\"M9.75 10.75V16.25\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>
                            <path d=\"M13.25 10.75V16.25\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>
                            <path d=\"M8.75 7.75V6.75C8.75 5.64543 9.64543 4.75 10.75 4.75H12.25C13.3546 4.75 14.25 5.64543 14.25 6.75V7.75\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>
                            <path d=\"M4.75 7.75H18.25\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>
                        </svg>
                        <input type=\"hidden\" name=\"title\" id=\"title\" value=\"{$title}\">
                        <button type=\"submit\">Izbriši</button>
                    </form> 
                    <button class=\"star\" value=\"{$title}\">
                        <svg width=\"24\" height=\"24\" fill=\"{$star_color}\" viewBox=\"0 0 24 24\">
                            <path stroke=\"yellow\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M12 4.75L13.75 10.25H19.25L14.75 13.75L16.25 19.25L12 15.75L7.75 19.25L9.25 13.75L4.75 10.25H10.25L12 4.75Z\"></path>
                        </svg>
                    </button>
                    <button class=\"view-note-btn\" value=\"{$title}\">
                        <svg width=\"24\" height=\"24\" fill=\"none\" viewBox=\"0 0 24 24\">
                            <path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M19.25 12C19.25 13 17.5 18.25 12 18.25C6.5 18.25 4.75 13 4.75 12C4.75 11 6.5 5.75 12 5.75C17.5 5.75 19.25 11 19.25 12Z\"></path>
                            <circle cx=\"12\" cy=\"12\" r=\"2.25\" stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\"></circle>
                        </svg>                  
                        <span>Prikaži</span>
                    </button>
                </div>  
            </div>";

            // Ispisivanje HTML-a za prikaz "previewa"
            echo $note_preview;
    }


