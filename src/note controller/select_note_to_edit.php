<?php
    // Uključi datoteke za vezu s bazom podataka i česte (zajedničke) funkcije
    include_once("../db connection/db_connection.php");
    include_once("../common.php");

    // Putanja na koju se korisnik preusmjerava (u note editor)
    $path = "../../public/note editor/index.php";

    // Dohvaćanje naslova bilješke iz GET zahtjeva
    // Dohvaća se naslov bilješke koju je korisnik selektirao
    $title = $_GET["title"];
    
    // Preusmjeri korisnika na drugu stranicu s naslovom bilješke u URL parametru
    redirect($path, "title={$title}");


    
    


