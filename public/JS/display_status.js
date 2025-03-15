// Export ključne riječi za korištenje funkcija u drugim skriptama

// Funkcija za dobavljanje vrijednosti parametra iz URL-a
export function paramValue(paramName) {
    // Dobavi "query string" odnosno parametre iz URL-a
    const QUERY_STRING = window.location.search;

    // Stvaranje novog objekta URLSearchParams s parametrom "query stringa"
    const PARAMS = new URLSearchParams(QUERY_STRING);

    // Dobivanje vrijednosti parametra pomoću objekta i imena parametra
    const paramValue = PARAMS.get(paramName);

    // Vraćanje vrijednost parametra
    return paramValue;
}

// Funkcija za prikazivanje poruke korisniku
// Npr. krivi podaci za prijavu, korisničko ime zauzeto...
export function displayPopupMessage(icon, message) {
    // Stvaranje novog div elementa za prikazivanje poruke
    const DISPLAY_STATUS = document.createElement('div');
    DISPLAY_STATUS.classList.add('display-status');
    // Postavljanje HTML sadržaja div elementa na ikonu i poruku
    DISPLAY_STATUS.innerHTML = `
        ${icon}
        <div class="message">${message}</div>
        <div class="timeline"></div>
        <div class="close">
            <svg width="29" height="29" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.25 6.75L6.75 17.25"></path>
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 6.75L17.25 17.25"></path>
            </svg>      
        </div>
    `;

    // Dobivanje elementa za prikaz vremenske crte
    const TIMELINE = DISPLAY_STATUS.querySelector('.timeline');
    const CLOSE = DISPLAY_STATUS.querySelector('.close');

    // Na element close se dodaje slušač događaja
    // Ako se klikne taj element (x ikona)
    CLOSE.addEventListener("click", () => {
        // Zatvori poruku 
        DISPLAY_STATUS.style.opacity = 0;
        DISPLAY_STATUS.style.visibility = "hidden";
    });

    // Pauziranje animacije kada korisnik prijeđe mišem preko elementa s porukom
    // Vremenska se ne kreće (nema animacije, vrijeme ne teče)
    DISPLAY_STATUS.addEventListener("mouseenter", () => {
        TIMELINE.style.animationPlayState = "paused";
    });

    // Pokretanje animacije kada korisnik ukloni miš s preko elementa s porukom
    // Vremenska crta se ponovo kreće (animacija nastavlja)
    DISPLAY_STATUS.addEventListener("mouseleave", () => {
        TIMELINE.style.animationPlayState = "running";
    });

    // Sakrivanje elementa s porukom nakon završetka animacije vremenske crte
    // Vremenska crta je istekla i poruka nestajse s ekrana
    TIMELINE.addEventListener("animationend", () => {
        DISPLAY_STATUS.style.opacity = 0;
        DISPLAY_STATUS.style.visibility = "hidden";
    });

    // Dodavanje pripremljenog elementa s porukom u tijelo dokumenta
    document.body.appendChild(DISPLAY_STATUS);
}



