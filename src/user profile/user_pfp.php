<?php
    // Započni sesiju
    session_start();

    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // SQL upit za dohvaćanje putanje profilne slike korisnika iz baze podataka
    $sql = "SELECT profile_picture FROM user WHERE user_id = ?";

    // Specifikatori tipova za pripremljenu izjavu
    $type_spec = "i";

    // Parametri za pripremljenu izjavu
    $param = [$_SESSION["user_id"]];

    // Varijabla za vraćanje rezultata izvršavanja upita
    $return_res = true;

    // Izvršavanje pripremljene SQL izjave i dohvaćanje rezultata
    $path = exec_prep_stmt($conn, $sql, $type_spec, $param, $return_res)[0]["profile_picture"] ?? "img/default-pfp.jpg";

    // Ako putanja nije zadana profilna slika, uzmite zadani put do profilne slike
    if ($path !== "img/default-pfp.jpg") {
        // Ukloni prvih 3 znaka iz putanje
        $path = substr($path, 3);
    }

    // Ispis HTML oznake slike s odgovarajućom putanjom
    echo "<img alt=\"profilna-slika\" src=\"{$path}\">";


