<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // Funkcija za dohvat bilješke na temelju njezinog identifikatora
    function get_note($type_spec, $param, $return_res) {
        global $conn;
        global $note_id;

        // SQL upit za dohvat bilješke i pripadajuće kategorije
        $sql = "SELECT note.title, note.content, note.created_at, note.updated_at, 
            note.deadline, note.completion, note.star, note_category.category_id
            FROM note
            INNER JOIN note_category ON note.category_id = note_category.category_id
            WHERE note.note_id = ?";

        // Izvršavanje pripremljenog upita i dohvat rezultata
        $query_res = exec_prep_stmt($conn, $sql, $type_spec, $param, $return_res);

        // Ako postoji rezultat upita, vrati ga, inače vrati null
        if(!empty($query_res)) {
            return $query_res[0];
        }
        return null;
    }

    // Funkcija za dohvat podsjetnika na temelju identifikatora bilješke
    function get_reminder($type_spec, $param, $return_res) {
        global $conn;
        global $note_id;

        // SQL upit za dohvat podsjetnika
        $sql = "SELECT reminder_time, active FROM reminder WHERE note_id = ?";

        // Izvršavanje pripremljenog upita i dohvat rezultata
        $query_res = exec_prep_stmt($conn, $sql, $type_spec, $param, $return_res);

        // Ako postoji rezultat upita, vrati ga, inače vrati null
        if(!empty($query_res)) {
            return $query_res[0];
        }
        return null;
    }

    // Funkcija za dohvat privitaka na temelju identifikatora bilješke
    function get_attachment($type_spec, $param, $return_res) {
        global $conn;
        global $note_id;

        // SQL upit za dohvat privitaka
        $sql = "SELECT attachment FROM attachment WHERE note_id = ?";

        // Izvršavanje pripremljenog upita i dohvat rezultata
        $query_res = exec_prep_stmt($conn, $sql, $type_spec, $param, $return_res);

        // Ako postoji rezultat upita, vrati ga, inače vrati null
        if(!empty($query_res)) {
            return $query_res[0];
        }
        return null;
    }

    // Funkcija za generiranje HTML prikaza bilješke
    function generate_note_view($note_row, $reminder_row, $attachment_row) {
        // Izvlačenje podataka iz rezultata upita
        $title = $note_row["title"];
        $content = $note_row["content"];
        $created_at = substr($note_row["created_at"], 0, -3);

        

        // Formatiraj datum kreiranja
        $created_at = strtotime($created_at);
        $created_at = date("d.m.Y H:i", $created_at);

        $updated_at = substr($note_row["updated_at"], 0, -3);
        $deadline = substr($note_row["deadline"], 0, -3);
        $completion = $note_row["completion"];
        $star = $note_row["star"];
        $category_id = $note_row["category_id"];

        // Dodjeljivanje odgovarajućih vrijednosti ovisno o kategoriji bilješke
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

        // Generiranje teksta o podsjetniku, ako postoji
        if($reminder_row != null) {
            $reminder_time = substr($reminder_row["reminder_time"], 0, -3);
            // Formatiranje datuma i generiranje HTML-a za prikaz podsjetnika
            $reminder_time = $reminder_time = strtotime($reminder_time);
            $reminder_time = date("d.m.Y H:i", $reminder_time);
            $reminder_active = $reminder_row["active"];
            $reminder_txt = "<div>
                <h2>Podsjetnik</h2>
                <div>Podsjetnik: {$reminder_time}</div>";
            if($reminder_active === "0") {
                $reminder_txt .= "<div>Već smo vas podsjetili.</div></div>";
            } else {
                $reminder_txt .= "<div>Nismo vas još podsjetili.</div></div>";
            }
        } else {
            $reminder_txt = "<div>
                <h2>Podsjetnik</h2>
                <div>Nemate podsjetnik uz bilješku</div>
            </div>";
        }

        // Generiranje HTML-a o privitku, ako postoji
        // Generiraj ponovo putanju jer se možda promjenilo korisničko ime
        if($attachment_row != null) {
            $attachment_filename = explode("/", $attachment_row["attachment"])[4];
            $attachment = "../user data/" . $_SESSION["username"] . "/{$attachment_filename}";
            $attachment_txt = "<div>
                <h2>Privitak</h2>
                <div>Vaš privitak: <a href=\"{$attachment}\" target=\"_blank\">{$attachment_filename}</a></div>
            </div>";
        } else {
            $attachment_txt = "<div><h2>Privitak</h2><div>Nemate privitak uz bilješku</div></div>";
        }

        // Provjera izvršenosti bilješke i prikaz odgovarajućeg teksta
        if($completion == 1) {
            $completion_txt = "Izvršili ste obvezu.";
        } else {
            $completion_txt = "Niste izvršili ste obvezu.";
        }

        // Provjera postojanja zvjezdice i generiranje odgovarajućeg HTML-a
        if($star == 1) {
            $star_txt = "Bilješka ima zvjezdicu
                <svg width=\"24\" height=\"24\" fill=\"yellow\" viewBox=\"0 0 24 24\">
                    <path stroke=\"#dddd33\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M12 4.75L13.75 10.25H19.25L14.75 13.75L16.25 19.25L12 15.75L7.75 19.25L9.25 13.75L4.75 10.25H10.25L12 4.75Z\"></path>
                </svg>";
        } else {
            $star_txt = "Bilješka nema zvjezdicu.";
        }

        // Provjera postojanja datuma uređenja i generiranje odgovarajućeg teksta
        if($updated_at == null) {
            $updated_at = "nije bila uređena";
        } else {
            $updated_at = strtotime($updated_at);
            $updated_at = date("d.m.Y H:i", $updated_at);
        }

        // Provjera postojanja roka obveze i generiranje odgovarajućeg HTML-a
        if($deadline !== "0000-00-00 00:00") {
            // Provjera je li rok obveze prošao
            $current_date = new DateTime();
            $deadline_datetime = new DateTime($deadline);
            if($current_date >= $deadline_datetime) {
                $deadline_txt = "Rok obveze je prošao.";
            } else {
                $deadline_txt = "Rok obveze nije prošao";
            }
            // Formatiranje datuma i generiranje HTML-a za prikaz roka
            $deadline = strtotime($deadline);
            $deadline = date("d.m.Y H:i", $deadline);
            $deadline_html = "<div>
                <h2>Rok</h2>
                <div>Rok: {$deadline}</div>
                <div>{$deadline_txt}</div>
            </div>";
        } else { 
            $deadline_html = "<div>
                    <h2>Rok</h2>
                    <div>Bilješka nema roka.</div>
                </div>"; 
        }

        // Generiranje HTML-a za prikaz bilješke
        $html = "
            <h1>{$title}</h1>
            <div id=\"nw-content\">
                <h2>Sadržaj bilješke</h2>
                <div>{$content}</div>
            </div>
            <div>
                <h2>Kategorija</h2>
                <div>Kategorija bilješke: <span>{$category_id}</span></div>
            </div>
            <div>
                <h2>Stvaranje i uređivanje bilješke</h2>
                <div>Kreirana: {$created_at}</div>
                <div>Uređena: {$updated_at}</div>
            </div>
            {$deadline_html}
            <div>
                <h2>Izvršenost</h2>
                <div>{$completion_txt}</div>
            </div>
            <div>
                <h2>Zvjezdica</h2>
                <div id=\"nw-svg-align\">{$star_txt}</div>
            </div>
            {$attachment_txt}
            {$reminder_txt}    
        ";

        return $html;
    }

    // Dohvat naslova bilješke iz URL parametara
    $title = $_GET["title"];
    // Dohvat identifikatora bilješke
    $note_id = get_note_id($title);

    // Česti parametri za pripremljene upite
    $type_spec = "i";
    $param = [$note_id];
    $return_res = true;

    // Dohvat podataka o bilješki, podsjetniku i privitku
    $note_row = get_note($type_spec, $param, $return_res);
    $reminder_row = get_reminder($type_spec, $param, $return_res);
    $attachment_row = get_attachment($type_spec, $param, $return_res);

    // Ako je dobiven podatak o bilješci generiranje HTML-a za prikaz bilješke
    if(!empty($note_row)) {
        echo generate_note_view($note_row, $reminder_row, $attachment_row);
    } else {
        // Inače obavijesti korisnika o grešci
        echo "<div>Došlo je do greške kod prikaza bilješke</div>";
    }