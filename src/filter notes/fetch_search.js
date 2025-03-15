// Selektiraj gumb za pretraživanje bilješki
const SEARCH_NOTES_BTN = document.getElementById("search-notes-btn");
// Selektiraj polje za upis za pretraživanje bilješki
const SEARCH_INPT = document.getElementById("search");

function httpReqFilter() {
    // Izvrši HTTP zahtjev za dohvat filtriranih bilješki - pretraživanje
    fetch(`../src/filter notes/search.php?search=${SEARCH_INPT.value}`)
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
}

// Dodaj slušač događaja na klik gumba za filtriranje
SEARCH_NOTES_BTN.addEventListener("click", httpReqFilter);

// Dodaj slušač događaja na pritisak tipke 
document.addEventListener("keydown", (event) => {
    // Ako je pritisnuta tipka enter
    // I polje za pretraživanje nije prazno
    if(event.key === "Enter" && SEARCH_INPT.value !== "") {
        httpReqFilter();
    }
});
