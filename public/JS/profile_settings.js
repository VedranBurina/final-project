// Dohvaćanje i spremanje elemenata u konstante pomoću ID-a
const PROFILE_BTN = document.getElementById("profile");
const PROFILE_SETT = document.getElementById("profile-settings");
// Varijabla govori ako su postavke profile vidljive
let settingsVisible = false;

// Dodaj slučač događaja na ikonicu profila (klik miša)
PROFILE_BTN.addEventListener("click", () => {
    // Klikom na gumb mijenja se stanje vidljivosti
    settingsVisible = !settingsVisible;

    // Ako je vidljivost postavljena, prikaži ih
    if(settingsVisible) {
        PROFILE_SETT.style.visibility = "visible";
        PROFILE_SETT.style.opacity = "1";
    } else { // Inače ih sakrij
        PROFILE_SETT.style.visibility = "hidden";
        PROFILE_SETT.style.opacity = "0";
    }
});
