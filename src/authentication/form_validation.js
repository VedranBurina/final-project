const QSA = query => document.querySelectorAll(query); // Funkcija za odabir više elemenata po CSS selektoru
const QS = query => document.querySelector(query); // Funkcija za odabir jednog elementa po CSS selektoru
const GEBI = id => document.getElementById(id); // Funkcija za odabir elementa po ID-u

const NAME_INPUT = QSA(".name-input"); // Lista svih polja za unos imena

// Provjera unesenih slova u poljima za unos imena
NAME_INPUT.forEach(input => {
    // Regularni izraz (regular expression) koji dopušta samo slova i razmake
    const lettersRegExp = /^[A-Za-zŠĐŽĆČšđžćč ]+$/; 

    input.addEventListener("input", () => {
        // Provjera jesu li uneseni samo slova
        let inputIsLetters = lettersRegExp.test(input.value); 

        // Ako nisu unesena slova, ukloni posljednji uneseni znak
        if (!inputIsLetters) {
            input.value = input.value.slice(0, -1);
        }
    });
});

const FORM_SIGNUP = QS(".signup-form"); // Forma za registraciju
const INPUT_SUBMIT = QSA('input[type="submit"]')[0]; // Gumb za slanje forme
const INPUT_PASSWORD = GEBI("password"); // Polje za unos lozinke
const INPUT_CONFIRM_PASSWORD = GEBI("confirm-password"); // Polje za potvrdu lozinke
const NAME = GEBI("name"); // Polje za unos imena
const SURNAME = GEBI("surname"); // Polje za unos prezimena

// Funkcija koja provjerava jesu li sva polja u formi popunjena
function isFormFilled() {
    for (let i = 0; i < FORM_SIGNUP.elements.length; i++) {
        let input = FORM_SIGNUP.elements[i];
        if (input.value === "") {
            return false;
        }
    }
    return true;
}

// Funkcija koja provjerava ispravnost forme pri unosu podataka
function validateForm() {
    // Provjera duljine unesene lozinke
    let passwordLength = INPUT_PASSWORD.value.length >= 8; 
    // Provjera jesu li unesena lozinka i potvrda lozinke ista
    let passwordMatchingConfirmPassword = INPUT_PASSWORD.value === INPUT_CONFIRM_PASSWORD.value; 

    // Ako su uvjeti ispunjeni i sva polja popunjena, omogućeno slanje forme
    if (passwordLength && passwordMatchingConfirmPassword && isFormFilled()) {
        INPUT_SUBMIT.disabled = false;
    } else {
        INPUT_SUBMIT.disabled = true; // Inače onemogućeno slanje forme
    }
}

// Event listener koji prati unos podataka u formu i pokreće provjeru
FORM_SIGNUP.addEventListener("input", validateForm); 
