<?php
    // Provjeri je li ime postavljeno u sesiji
    if(isset($_SESSION["first_name"])) {
        // Dohvati ime iz sesije
        $ime = $_SESSION["first_name"];
        // Ako je ime postavljeno, ispiši pozdravnu poruku
        echo "<h1 id=\"dynamic-greet\">Bok, <span>{$ime}</span>!</h1>";
    } else {
        // Ako ime nije postavljeno u sesiji, preusmjeri korisnika na stranicu za prijavu
        // header("Location: authentication/index.php");
        /* 
        Popravak koda za hosting jer je na hostingu već poslan header kod učitavanja ovoga
        koda na početnoj stranici i dolazi do greške
        Rješenje: prosljeđivanje JS koda za redirekciju
        */

        echo "<script>window.onload = () => {
            window.location.href = 'authentication/';
        };</script>";
        
        // Prekini izvođenje skripte
        die;
    }

