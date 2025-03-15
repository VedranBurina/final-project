// Funkcija za "highlightanje" / istaknuti rokove koji su istekli
function expiringDeadlinesHighlight() {
    // Dohvati element koji sadrži rok pomoću CSS selektora
    const DATE_EM = document.querySelectorAll(".completion-data > span > span > span");
    // Iteriraj kroz sve elemente
    DATE_EM.forEach((em, index) => {
        // Pomoću regularne ekspresije (izraza) presloži datum elementa u pravi format
        const FORMATED_DATE = em.textContent.replace(/^(\d{2})\.(\d{2})\.(\d{4})$/, '$3-$2-$1');
        // Iz formatiranog stringa datuma napravi datum objekt
        const DATE_OBJ = new Date(FORMATED_DATE);
        // Dobavljanje trenutnog datuma
        const CURRENT_DATE = new Date();

        // Ako je datum prošao
        if(DATE_OBJ < CURRENT_DATE) {
            // Istakni taj datum crvenom bojom
            DATE_EM[index].style.color = "#f94449";
        }
    });
}

// Pozovi funkciju
expiringDeadlinesHighlight();

/* Kod dinamičkog generiranja elemenata ova funkcija se ponovno 
mora pozvati kako bi se istaknuli isteknuti datumi generiranih elemenata */