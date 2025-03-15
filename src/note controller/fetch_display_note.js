// Dohvaća sve elemente s klasom "note-container" i sprema ih u varijablu
let viewNoteBtn = document.querySelectorAll(".view-note-btn");

// Selektira kontejner za prikaz pojedinačne bilješke
let noteViewContainer = document.getElementById("note-view");

// Deklaracija varijable za zatvaranje kontejnera za prikaz pojedinačne bilješke
let closeNotViewContainer = null;

// Funkcija za zatvaranje kontejnera za prikaz pojedinačne bilješke
function closeNoteViewContainer() {
    // Dodaje slušač događaja za zatvaranje kontejnera na klik
    closeNotViewContainer.addEventListener("click", () => {
        // Postavlja opacity i visibility na skriveno kada se klikne na zatvaranje
        // Sakrivanje kontejnera (od korisnika i od miša(visibility))
        noteViewContainer.style.opacity = "0";
        noteViewContainer.style.visibility = "hidden";
        // Pomakni element koji pokriva pozadinu iza
        HIDE_BACKGROUND.style.zIndex = "-1";
        // Omogući scroll po stranici
        document.body.style.overflow = "hidden auto";
    });
}

// Funkcija za prikaz pojedinačne bilješke, bilješke koja je kliknuta
function viewNote() {
    // Iterira kroz sve elemente u viewNoteBtn
    viewNoteBtn.forEach(em => {
        // Dodaje slušač događaja na svaki element koji reagira na klik
        em.addEventListener("click", () =>  { 
            // Napravi HTTP zahtjev prema PHP skripti display_note.php s naslovom bilješke kao parametrom
            fetch(`../src/note controller/display_note.php?title=${em.value}`)
                // Kada se vrati odgovor iz PHP skripte, pretvori ga u tekst
                .then(response => response.text())
                // Kada se tekst primi, prikaži ga u kontejneru za prikaz bilješke
                .then(data => {
                    // Postavlja HTML sadržaj kontejnera za prikaz bilješke na dobivene podatke
                    noteViewContainer.innerHTML = data + NOTE_CLOSE;
                    // Postavlja opacity i visibility na vidljivo kada se bilješka prikaže
                    noteViewContainer.style.opacity = "1";
                    noteViewContainer.style.visibility = "visible";
                    // Pomakni element koji pokriva pozadinu naprijed
                    HIDE_BACKGROUND.style.zIndex = "100";
                    // Selektira element za zatvaranje bilješke
                    closeNotViewContainer = document.getElementById("nw-zatvori");
                    // Poziva funkciju za zatvaranje kontejnera za prikaz bilješke
                    closeNoteViewContainer();
                    // Onemogući scroll po stranici
                    document.body.style.overflow = "hidden";
                })
                // U slučaju greške, prikazuje poruku o neuspjehu u kontejneru za prikaz bilješke
                .catch(error => noteViewContainer.innerHTML = "Greška kod dohvata bilješke: " + error);
        });
    });
}

// Elment koji pokriva pozadinu dok je prikazana bilješka
const HIDE_BACKGROUND = document.getElementById("hide-background");

// Varijabla sadrži html element za zatvaranje bilješke
const NOTE_CLOSE = `
    <svg id=\"nw-zatvori\" width="32" height="32" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 6.75L6.75 17.25"></path>
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 6.75L17.25 17.25"></path>
    </svg>  
`;

// Poziva funkciju za prikaz pojedinačne bilješke
viewNote();

/*  
    Napomena: funkcija je stvorena kako bi omogućila ponovno povezivanje
    slušača događaja u slučaju dinamičkih promjena elemenata s klasom "note-container",
    osiguravajući da slušači budu prisutni za nove elemente. 
    (npr. nakon filtriranja ili drugih dinamičkih promjena)
*/
