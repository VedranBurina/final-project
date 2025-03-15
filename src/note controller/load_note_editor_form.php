<?php
    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../../src/db connection/db_connection.php");
    include_once("../../src/common.php");

    // Funkcija koja provjerava je li odabrana kategorija
    function select_category($category, $comparing_category) {
        // Ako je trenutna kategorija jednaka uspoređivanoj kategoriji, vrati "selected" kako bi označio odabir u HTML-u
        return $category == $comparing_category ? "selected" : "";
    }

    // Funkcija koja prikazuje checkbox
    function display_completion_checkbox($action, $completion){
        $checkbox_section = null;
        // Checkbox se prikazuje ako je akcija forme "note_edit"
        if($action === "note_edit") {
            // Prikaži korisniku checkbox kao opciju da označi bilješku (podsjetnik) kao izvršenu
            if($completion == 1) {
                // Prikaži označeni checkbox ako je bilješka izvršena
                $checkbox_section = "<div>Obveza izvršena
                <input type=\"checkbox\" id=\"completion\" name=\"completion\" value=\"1\" checked></div>";
            } else {
                // Inače prikaži neoznačeni
                $checkbox_section = "<div>Obveza izvršena
                <input type=\"checkbox\" id=\"completion\" name=\"completion\" value=\"1\"></div>";
            }
        }
        return $checkbox_section;
    }

    // Funkcija koja dohvaća podatke o bilješki iz baze podataka na temelju naslova
    function get_note($title) {
        global $conn;

        $sql_note_edit = "SELECT * FROM note WHERE title = ?";
        $note_edit_TS = "s";
        $note_edit_param = [$title];
        $note_edit_RR = true;

        return exec_prep_stmt($conn, $sql_note_edit, $note_edit_TS, $note_edit_param, $note_edit_RR);
    }

    // Funkcija koja dobavlja privitak bilješke
    function get_attachment($note_id) {
        global $conn;

        // SQL upit za dobavljanje putanje prema privitku koji je priložen bilješci 
        $sql_del_file = "SELECT attachment FROM attachment 
            WHERE note_id = ?";
        $type_spec = "i";
        $param = [$note_id];
        $return_res = true;

        return exec_prep_stmt($conn, $sql_del_file, $type_spec, $param, $return_res);
    }

    // Funkcija koja dohvaća podsjetnik bilješke na temelju njenog ID-a
    function get_reminder($note_id) {
        global $conn;

        $sql_reminder = "SELECT reminder_time FROM reminder WHERE note_id = ?";
        $reminder_TS = "i";
        $reminder_param = [$note_id];
        $reminder_RR = true;

        return exec_prep_stmt($conn, $sql_reminder, $reminder_TS, $reminder_param, $reminder_RR);
    }

    // Generiranje note_id varijable da joj doseg bude van else bloka
    $note_id = null;

    // Funkcija koja generira HTML obrazac za uređivanje ili dodavanje bilješke
    function generate_note_editor_form($title, $category, $content, $deadline, $completion, $action, 
    $btn_txt, $attachment, $reminder_time) {
        global $note_id;
        $attachment_html = "
            <label id=\"lbl-file\" for=\"attachment\">Odaberite ili povucite privitak
            </label>";

        // Dobavljanje točnog naziva filea (privitka) iz putanje ako bilješka ima file
        if($attachment !== "nema priloga" && $action !== "note_save") {
            $attachment_filename = explode("/", $attachment)[4];

            // Kreiranje HTML-a za obavijest o postojećem privitku
            $attachment_html = "
                <label id=\"lbl-file\" for=\"attachment\">Postojeći prilog: {$attachment_filename}<br>
                Ako postavite novi prilog, stari će biti izmjenjen</label>";
        } else {
            // Ako bilješka nema privitak ne treba dobaviti točan naziv filea (privitka)
            // Već dodjeliti defaultnu vrijednost $attachment (koja će biti "nema priloga") 
            $attachment_filename = $attachment;
        }

        $note_editor_form = "
        <form action=\"../../src/note controller/{$action}.php\" method=\"post\" enctype=\"multipart/form-data\">
            <div class=\"split\">
                <input type=\"text\" name=\"title\" id=\"title\" autocomplete=\"on\" placeholder=\"Naslov\" value=\"{$title}\">
                <textarea name=\"content\" id=\"content\" placeholder=\"Sadržaj...\">{$content}</textarea>
                <select name=\"category\" id=\"category\">
                    <!-- Opcije kategorija s odabranom kategorijom ako je točno -->
                    <option value=\"11\"" . select_category($category, 11) . ">Bez kategorije</option>
                    <option value=\"1\"" . select_category($category, 1) . ">Osobno</option>
                    <option value=\"2\"" . select_category($category, 2) . ">Posao</option>
                    <option value=\"3\"" . select_category($category, 3) . ">Učenje</option>
                    <option value=\"4\"" . select_category($category, 4) . ">Kupovina</option>
                    <option value=\"5\"" . select_category($category, 5) . ">Zdravlje</option>
                    <option value=\"6\"" . select_category($category, 6) . ">Fitness</option>
                    <option value=\"7\"" . select_category($category, 7) . ">Dom</option>
                    <option value=\"8\"" . select_category($category, 8) . ">Financije</option>
                    <option value=\"9\"" . select_category($category, 9) . ">Putovanje</option>
                    <option value=\"10\"" . select_category($category, 10) . ">Recepti</option>
                </select>
            </div>
            <div class=\"split-line\"></div>
            <div class=\"split\">
                <h2>Opcionalno</h2>
                <div>
                    <div>Rok izvršenja:</div>
                    <input type=\"datetime-local\" name=\"deadline\" id=\"deadline\" placeholder=\"Rok izvršenja\" 
                    value=\"{$deadline}\">
                </div>
                " . display_completion_checkbox($action, $completion) . "
                <div>
                    {$attachment_html}
                    <input type=\"file\" name=\"attachment\" id=\"attachment\" accept=\".txt, .jpg, .jpeg, .png, .gif, .pdf, 
                    .doc, .docx, .csv, .xls, .xlsx, .svg\">
                </div>
                <div>
                    <div>Podsjeti me e-mailom:</div>
                    <input type=\"datetime-local\" name=\"reminder_time\" id=\"reminder_time\" 
                    placeholder=\"Datum i vrijeme podsjetnika\" value=\"{$reminder_time}\">
                </div>  
            </div>
            <div class=\"submit-btn-wrapper\">
                <input type=\"submit\" value=\"{$btn_txt}\">
            </div>
        </form>
        ";

        /* 
            Ako je parametar action "note_edit" onda se za uređivanje bilješke posprema u sesiju:
            stari ID bilješke,
            stari naslov bilješke,
            stari privitak
        */
        if($action === "note_edit") {
            $_SESSION["old_note_id"] = $note_id;
            $_SESSION["old_note_title"] = $title;
            $_SESSION["old_attachment"] = $attachment;
        }

        return $note_editor_form;
    }

    // Provjeri je li definiran naslov bilješke u URL-u
    if(!isset($_GET["title"])) {
        // Ako naslov nije definiran, generiraj obrazac za dodavanje nove bilješke
        echo generate_note_editor_form("", "", "", "", "", "note_save", "Spremi bilješku",
        "", "");
    } else {
        // Ako je naslov definiran, dohvati podatke bilješke iz baze podataka i generiraj obrazac za uređivanje
        $title = $_GET["title"];

        // Inicijaliziranje vrijednosti potrebnih za generiranje forme za uređivanje bilješke (editor)
        $note_to_edit_rows = get_note($title);
        $note_id = $note_to_edit_rows[0]["note_id"];
        $category = $note_to_edit_rows[0]["category_id"];
        $content = $note_to_edit_rows[0]["content"];
        $deadline = $note_to_edit_rows[0]["deadline"];
        $completion = $note_to_edit_rows[0]["completion"];
        $reminder_time = "";

        // Dohvati privitak ovisno o tome je li postavljen uz bilješku
        $attachment_rows = get_attachment($note_id);
        if(!empty($attachment_rows)) {
            $attachment = $attachment_rows[0]["attachment"];
        } else { $attachment = "nema priloga"; }

        // Dohvati datum remindera ovisno o tome je li postavljen uz bilješku
        $reminder_rows = get_reminder($note_id);
        if(!empty($reminder_rows)) {
            $reminder_time = $reminder_rows[0]["reminder_time"];
        }

        // Generiraj formu za uređivanje bilješke na temelju prosljeđenih parametara
        echo generate_note_editor_form($title, $category, $content, $deadline, $completion, "note_edit", "Spremi promjene",
        $attachment, $reminder_time);
    }



    