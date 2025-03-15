import { paramValue } from "../../JS/display_status.js";

// dohvaćanje elemenata: forme za prijavu i izradu računa, element za promjenu forme
const SIGNUP_FORM = document.querySelector(".signup-form");
const LOGIN_FORM = document.querySelector(".login-form");
const CHANGE_FORM = document.getElementById("change-form");

// varijabla govori o aktivnosti forme za prijavu
let loginFormActive = false;

// Element za promjenu forma ima šlušač događaja na klik
CHANGE_FORM.addEventListener("click", () => {
    // klikom na element za promjenu forme mijenja se stanje aktivne forme
    // svakim klikom je suprotno
    loginFormActive = !loginFormActive;

    // ovisno o varijabli mijenja se koja se forma prikazuje
    // mijenja se HTML sadržaj elementa za promjenu forme
    if(loginFormActive) {
        SIGNUP_FORM.classList.add("active-form");
        SIGNUP_FORM.classList.remove("inactive-form");
        LOGIN_FORM.classList.add("inactive-form");
        LOGIN_FORM.classList.remove("active-form");
        CHANGE_FORM.innerHTML = `
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 8.75L13.25 12L9.75 15.25"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 4.75H17.25C18.3546 4.75 19.25 5.64543 19.25 6.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H9.75"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 12H4.75"></path>
        </svg>
        Već imam račun - prijava`;
    } else if(!loginFormActive) {
        SIGNUP_FORM.classList.add("inactive-form");
        SIGNUP_FORM.classList.remove("active-form");
        LOGIN_FORM.classList.add("active-form");
        LOGIN_FORM.classList.remove("inactive-form"); 
        CHANGE_FORM.innerHTML = `
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="3.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></circle>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12.25 19.25H6.94953C5.77004 19.25 4.88989 18.2103 5.49085 17.1954C6.36247 15.7234 8.23935 14 12.25 14"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 14.75V19.25"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 17L14.75 17"></path>
        </svg>
        Izradi račun`;
    }
});

// Slušač događaja na cijeli dokument
// "Čuje" dok su DOM (svi elementi(ojekti) dokumenta) sadržaj učita
document.addEventListener("DOMContentLoaded", () => {
    // Ako korisnik pokušava napraviti račun i pogriješi
    // Onda se to vidi iz parametra (status parametar) URL-a
    const status = paramValue("status");

    // Varijabla provjerava ako se korisnik pokušava prijaviti
    const userIsLoggingIn = (status === "duplicate" || status === "signup_failed");

    // Prikaži formu za izradu računa ponovno
    if(userIsLoggingIn) {
        SIGNUP_FORM.classList.add("active-form");
        SIGNUP_FORM.classList.remove("inactive-form");
        LOGIN_FORM.classList.add("inactive-form");
        LOGIN_FORM.classList.remove("active-form");
        CHANGE_FORM.innerHTML = `
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 8.75L13.25 12L9.75 15.25"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 4.75H17.25C18.3546 4.75 19.25 5.64543 19.25 6.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H9.75"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 12H4.75"></path>
        </svg>
        Već imam račun - prijava`;

        // Pohrani promjene o aktivnoj varijabli
        loginFormActive = !loginFormActive;
    }
});

