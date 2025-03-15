// Odabir više elemenata po CSS selektoru (checkboxovi o izvršenju obveze na bilješci)
let mainPageNoteCompletion = document.querySelectorAll(".note-completion");
// Odabir više elemenata po CSS selektoru (inputi koji sadržavaju naslove bilješki)
let mainPageNoteCompletionTitle = document.querySelectorAll(".note-completion-title");

function noteCompletionStateSet() {
    // Iteriraj kroz sve elemente s klasom "note-completion"
    mainPageNoteCompletion.forEach((em, index) => {
        // Dodaj slušač događaja na klik svakog elementa
        em.addEventListener("click", () => {
            // Dohvati vrijednost checkbox-a
            let completionParam = em.checked;
            // Dohvati naslov bilješke
            let completionNoteTitleParam = mainPageNoteCompletionTitle[index].value;

            // Napravi HTTP zahtjev za označavanje da je obveza na bilješci izvršena 
            fetch(`../src/note controller/note_completion.php?completion=${completionParam}&title=${completionNoteTitleParam}`)
                .then(response => response.text()) // Pretvori odgovor u tekst
                // Dodaj novi unos u povijest preglednika
                .then(data => window.history.pushState(null, "", window.location.pathname + data))
                // U slučaju greške, prikaži poruku o neuspjehu
                .catch(error => em.innerHTML = "Neuspješno označeno: " + error);
        });
    });
}

// Zvanje funkcije
noteCompletionStateSet();

/*  
    Napomena: funkcija je stvorena kako bi se omogućilo ponovno povezivanje slušača događaja
    (tako da se ponovno poozove ta funkcija) u slučaju da se elementi s klasom "note-completion" promijene 
    dinamički, osiguravajući da slušači događaja budu prisutni za nove elemente. (npr. kod filtriranja)
*/


