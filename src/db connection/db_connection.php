<?php
    // Postavi podatke za povezivanje s bazom podataka
    $host = "localhost"; // Adresa hosta, obično "localhost" ako se baza podataka nalazi na istom serveru
    $username = "root"; // Korisničko ime za pristup bazi podataka
    $password = ""; // Lozinka za pristup bazi podataka
    $db = "notes"; // Ime baze podataka kojoj se želi pristupiti

    // Stvori novu vezu (objekt) s bazom podataka
    $conn = new mysqli($host, $username, $password, $db);

    // Provjeri vezu s bazom podataka
    if ($conn->connect_error) {
        // Ako postoji problem s povezivanjem, ispiši poruku o grešci
        die("Greška prilikom povezivanja s bazom podataka: " . $conn->connect_error);
    }
    