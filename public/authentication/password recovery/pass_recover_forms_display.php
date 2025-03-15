<?php
    // Provjeri je li status parametar (u URL-u) postavljen i je li vrijednost "success"
    if(isset($_GET["status"]) && $_GET["status"] === "success") {
        // Ako je, prikaži formu za unos lozinke i sakrij formu za unos e-pošte
        // Korisnik može ponovno postaviti lozinku
        echo "
        <style>
            #frmEmail { display: none; } 
            #frmPass { display: flex; } 
        </style>
        ";
    } else {
        // Ako nije (inače), prikaži formu za unos e-pošte i sakrij formu za unos lozinke
        // Korisnik upisuje e-mail za ponovno postavljanje lozinke
        echo "
        <style>
            #frmEmail { display: flex; } 
            #frmPass { display: none; } 
        </style>
        ";
    }