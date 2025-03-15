<?php
    // Pokreni sesiju
    session_start();
    // Uništi sve podatke o sesiji
    // Jer sesija sadrži podatke o korisniku
    // I podatke o njegovim bilješkama
    session_destroy();
    // Preusmjeri korisnika na stranicu za autentikaciju
    header("Location: ../../public/authentication/index.php");
