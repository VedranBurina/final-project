function previewProfilePicture(event) {
    // Dobivanje odabrane datoteke iz događaja
    const file = event.target.files[0]; 
    // Stvaranje objekta FileReader
    const reader = new FileReader(); 
                
    // Postavljanje funkcije koja će se izvršiti kada se učita slika
    reader.onload = e => {
        // Postavljanje putanje slike kao izvor slike u HTML elementu
        inptPFPLblImg.src = e.target.result;
    };
                
    // Ako postoji odabrana datoteka, čitaj je kao URL
    if(file) {
        reader.readAsDataURL(file);
    }
}

// Funkcija za dobivanje slike
function getProfilePicture() {
    // Napravi HTTP zahtjev prema PHP skripti user_pfp.php
    fetch("../src/user profile/user_pfp.php")
        // Odgovor pretvori u tekst
        .then(response => response.text())
        .then(data => {
            // Postavljanje slike profila u HTML elementa za otvaranje postavki
            PROFILE_SETTINGS_TOGGLE.innerHTML = data + EDIT_ICON;
            // Postavljanje slike profila u HTML labela za postavljanje profilne slike
            inptPFPLbl.innerHTML += data;
            // Dohvaćanje elementa slike (<img>) koja je prethodno postavljena u elemente
            inptPFPLblImg = document.querySelector("#pfp_label > img");
            // Stavljanje slušača događaja na konkretni input element za promjenu profilne slike
            inptPFP.addEventListener("change", previewProfilePicture);
        })
        .catch(error => PROFILE_SETTINGS_TOGGLE.innerHTML = "Greška: " + error);
}

// Funkcija za dobivanje informacija o korisniku
function getUserInfo() {
    // Napravi HTTP zahtjev prema PHP skripti user_info.php
    fetch("../src/user profile/user_info.php")
        // Odgovor pretvori u tekst
        .then(response => response.text())
        .then(data => {
            // Postavljanje informacija o korisniku u HTML elementa koji sadrži postavke računa korisnika
            PROFILE_SETT.innerHTML += data;
            // Dohvaćanje input elementa za promjenu profilne slike
            inptPFP = document.getElementById("profile_picture");
            // Dohvaćanje label elementa za input element promjene profilne slike
            inptPFPLbl = document.getElementById("pfp_label");
        })
        .catch(error => PROFILE_SETT.innerHTML = "Greška kod prikaza podataka: " + error);
}

// Deklariranje elemenata
const PROFILE_SETTINGS_TOGGLE = document.getElementById("profile");
let inptPFP = null;
let inptPFPLbl = null;
let inptPFPLblImg = null;
 
// Ikona za editiranje
const EDIT_ICON = `
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4.75 19.25L9 18.25L18.9491 8.30083C19.3397 7.9103 19.3397 7.27714 18.9491 6.88661L17.1134 5.05083C16.7228 4.6603 16.0897 4.6603 15.6991 5.05083L5.75 15L4.75 19.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        <path d="M14.0234 7.03906L17.0234 10.0391" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
`;

// Pozivanje funkcije za dobivanje informacija o korisniku
getUserInfo();

// Slušač događaja - kad se svi elementi stranice učitaju
document.addEventListener("DOMContentLoaded", () => {
    // Pozivanje funkcije za dobivanje slike profila
    getProfilePicture();
});


