// Dohvaćanje elemenata u konstante koristeći CSS selektore
// Koristeći metodu querySelectorAll za sve elemente i querSelector za samo 1
const INPUT = document.querySelectorAll("input");
const ANIM1 = document.querySelector("#anim1");
const ANIM2 = document.querySelector("#anim2");
const ANIM3 = document.querySelector("#anim3");

// Stavljanje animiranih elemenata
const ANIM = [ANIM1, ANIM2, ANIM3];

// Iteriraj kroz sve input elemente
INPUT.forEach(em => {
    // Dodaj slušač događaja na svaki input, sluša je li input u fokusu 
    em.addEventListener("focus", () => {    
        // Ako je neki od input elemenata u fokusu
        // Iteriraj kroz polje animiranih elemenata 
        ANIM.forEach(em => {
            // Dodaj svakom elementu klasu anim 
            // Ima stil za animirane elemente kad je neki input u fokusu
            em.classList.add("anim");
        });
    });
    // Dodaj slušač događaja na svaki input, sluša je li input van fokusa
    em.addEventListener("blur", () => {
        // Iteriraj kroz polje animiranih elemenata
        ANIM.forEach(em => {
            // Makni klasu svakom elementu
            em.classList.remove("anim");
        });
    });
});

