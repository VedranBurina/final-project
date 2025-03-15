// Dohvati sve elemente s klasom "star" i spremi ih u konstantu star
let star = document.querySelectorAll(".star");

// Funkcija koja dodaje zvjezdicu bilješci
function starNote() {
    // Iteriraj kroz sve elemente u star
    star.forEach(em => {
        // Dodaj slušač događaja na svaki element koji reagira na klik
        em.addEventListener("click", () =>  { 
            // Napravi HTTP zahtjev prema PHP skripti note_star.php s naslovom bilješke kao parametrom
            fetch(`../src/note controller/note_star.php?title=${em.value}`)
                // Kada se vrati odgovor iz PHP skripte, pretvori ga u tekst
                .then(response => response.text())
                // Kada se tekst primi, postavi ga kao HTML sadržaj kliknutog elementa (em)
                .then(data => em.innerHTML = data)
                // U slučaju greške, postavi poruku o neuspjehu u HTML sadržaj kliknutog elementa (em)
                .catch(error => em.innerHTML = "Failed to star a note: " + error);
        });
    });
}

// Zvanje funkcije
starNote();

/*  
    Napomena: funkcija je stvorena kako bi se omogućilo ponovno povezivanje slušača događaja
    (tako da se ponovno poozove ta funkcija) u slučaju da se elementi s klasom "star" promijene 
    dinamički, osiguravajući da slušači događaja budu prisutni za nove elemente. (npr. kod filtriranja)
*/
