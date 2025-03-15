// Selektiraj gumb za filtriranje bilješki koje su neizvršene
const FILTER_UNCOMPLETED_BTN = document.getElementById("filter-completion0-btn");

// Dodaj slušač događaja na klik gumba za filtriranje
FILTER_UNCOMPLETED_BTN.addEventListener("click", () => {
    // Izvrši HTTP zahtjev za dohvat filtriranih bilješki koje su neizvršene
    fetch("../src/filter notes/filter_completion0.php")
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
            NOTES_CONTAINER.innerHTML = "Došlo je do greške kod prikaza: " + error;
        });
});