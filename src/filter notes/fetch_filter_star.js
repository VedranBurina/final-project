// Selektiraj element koji sadrži prikaz bilješki
const NOTES_CONTAINER = document.getElementById("notes-container");

// Selektiraj gumb za filtriranje bilješki označenih zvjezdicom
const FILTER_STAR_BTN = document.getElementById("filter-star-btn");

// Dodaj slušač događaja na klik gumba za filtriranje
FILTER_STAR_BTN.addEventListener("click", () => {
    // Izvrši HTTP zahtjev za dohvat filtriranih bilješki označenih zvjezdicom
    fetch("../src/filter notes/filter_star.php")
        .then(response => response.text()) // Pretvori odgovor u tekst
        .then(data => {

            // Postavi dobivene podatke u kontejner za prikaz bilješki
            NOTES_CONTAINER.innerHTML = data;
            
            // Ponovno dohvati elemente i dodaj im funkcionalnost
            // Zato što su HTTP zahtjevom (fetch API) dodani novi elementi filtriranjem
            // Te elemente se ponovno dohvaća i dodaje im se funkcionalnost

            star = document.querySelectorAll(".star");
            starNote();

            mainPageNoteCompletion = document.querySelectorAll(".note-completion");
            mainPageNoteCompletionTitle = document.querySelectorAll(".note-completion-title"); 
            noteCompletionStateSet();

            
            viewNoteBtn = document.querySelectorAll(".view-note-btn");
            noteViewContainer = document.getElementById("note-view");
            viewNote();

            expiringDeadlinesHighlight();
        })
        .catch(error => {
            // U slučaju greške pri prikazu, prikaži poruku o grešci
            NOTES_CONTAINER.innerHTML = "Došlo je do greške kod prikaza: " + error;
        });
});


