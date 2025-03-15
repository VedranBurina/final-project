// Importiranje displayPopupMessage i paramValue funkcije iz specificiranog modula
import { displayPopupMessage, paramValue } from "../../JS/display_status.js";

// Definiranje konstanti objekata za pojedine vrijednosti statusa
// Objekti sadrže: moguću vrijednost statusa
// Ikonu za poruku korisniku
// Tekst poruke
const FAIL = {
    status: "failed",
    icon: `
    <svg width="90" height="90" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 14.25C11.5858 14.25 11.25 14.5858 11.25 15C11.25 15.4142 11.5858 15.75 12 15.75V14.25ZM12.01 15.75C12.4242 15.75 12.76 15.4142 12.76 15C12.76 14.5858 12.4242 14.25 12.01 14.25V15.75ZM12 15.75H12.01V14.25H12V15.75Z" fill="#f94449"></path>
        <path d="M10.4033 5.41136L10.9337 5.94169L10.4033 5.41136ZM5.41136 10.4033L4.88103 9.87301L4.88103 9.87301L5.41136 10.4033ZM5.41136 13.5967L5.94169 13.0663L5.94169 13.0663L5.41136 13.5967ZM10.4033 18.5886L10.9337 18.0583L10.4033 18.5886ZM13.5967 18.5886L14.127 19.119L14.127 19.119L13.5967 18.5886ZM18.5886 10.4033L19.119 9.87301L19.119 9.87301L18.5886 10.4033ZM13.5967 5.41136L14.127 4.88103L14.127 4.88103L13.5967 5.41136ZM9.87301 4.88103L4.88103 9.87301L5.94169 10.9337L10.9337 5.94169L9.87301 4.88103ZM4.88103 14.127L9.87301 19.119L10.9337 18.0583L5.94169 13.0663L4.88103 14.127ZM14.127 19.119L19.119 14.127L18.0583 13.0663L13.0663 18.0583L14.127 19.119ZM19.119 9.87301L14.127 4.88103L13.0663 5.94169L18.0583 10.9337L19.119 9.87301ZM19.119 14.127C20.2937 12.9523 20.2937 11.0477 19.119 9.87301L18.0583 10.9337C18.6472 11.5226 18.6472 12.4774 18.0583 13.0663L19.119 14.127ZM9.87301 19.119C11.0477 20.2937 12.9523 20.2937 14.127 19.119L13.0663 18.0583C12.4774 18.6472 11.5226 18.6472 10.9337 18.0583L9.87301 19.119ZM4.88103 9.87301C3.70632 11.0477 3.70632 12.9523 4.88103 14.127L5.94169 13.0663C5.35277 12.4774 5.35277 11.5226 5.94169 10.9337L4.88103 9.87301ZM10.9337 5.94169C11.5226 5.35277 12.4774 5.35277 13.0663 5.94169L14.127 4.88103C12.9523 3.70632 11.0477 3.70632 9.87301 4.88103L10.9337 5.94169Z" fill="#f94449"></path>
        <path d="M12 8.75V12.25" stroke="#f94449" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
    `,
    msg: "Greška kod stvaranja tokena."
};

const FAIL_EMAIL = {
    status: "failed_email",
    icon: `
    <svg width="90" height="90" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.25 19C12.6642 19 13 18.6642 13 18.25C13 17.8358 12.6642 17.5 12.25 17.5V19ZM18.5 10.25C18.5 10.6642 18.8358 11 19.25 11C19.6642 11 20 10.6642 20 10.25H18.5ZM6.75 6.5H17.25V5H6.75V6.5ZM5.5 16.25V7.75H4V16.25H5.5ZM12.25 17.5H6.75V19H12.25V17.5ZM18.5 7.75V10.25H20V7.75H18.5ZM4 16.25C4 17.7688 5.23122 19 6.75 19V17.5C6.05964 17.5 5.5 16.9404 5.5 16.25H4ZM17.25 6.5C17.9404 6.5 18.5 7.05964 18.5 7.75H20C20 6.23122 18.7688 5 17.25 5V6.5ZM6.75 5C5.23122 5 4 6.23122 4 7.75H5.5C5.5 7.05964 6.05964 6.5 6.75 6.5V5Z" fill="#f94449"></path>
        <path d="M5.99693 5.93825C5.68669 5.66381 5.2127 5.69283 4.93825 6.00307C4.66381 6.31331 4.69283 6.7873 5.00307 7.06175L5.99693 5.93825ZM12 12.25L11.5031 12.8117C11.7868 13.0628 12.2132 13.0628 12.4969 12.8117L12 12.25ZM18.9969 7.06175C19.3072 6.7873 19.3362 6.31331 19.0617 6.00307C18.7873 5.69283 18.3133 5.66381 18.0031 5.93825L18.9969 7.06175ZM5.00307 7.06175L11.5031 12.8117L12.4969 11.6883L5.99693 5.93825L5.00307 7.06175ZM12.4969 12.8117L18.9969 7.06175L18.0031 5.93825L11.5031 11.6883L12.4969 12.8117Z" fill="#f94449"></path>
        <path d="M18.75 13.75C18.75 13.3358 18.4142 13 18 13C17.5858 13 17.25 13.3358 17.25 13.75H18.75ZM17.25 16.25C17.25 16.6642 17.5858 17 18 17C18.4142 17 18.75 16.6642 18.75 16.25H17.25ZM17.25 13.75V16.25H18.75V13.75H17.25Z" fill="#f94449"></path>
        <path d="M18 19V20C18.5523 20 19 19.5523 19 19H18ZM18 19H17C17 19.5523 17.4477 20 18 20V19ZM18 19V18C17.4477 18 17 18.4477 17 19H18ZM18 19H19C19 18.4477 18.5523 18 18 18V19Z" fill="#f94449"></path>
    </svg>
    `,
    msg: "Pogrešna e-mail adresa."
};

const SUCCESS = {
    status: "success",
    icon: `
    <svg width="90" height="90" fill="none" viewBox="0 0 24 24">
        <path stroke="#3eb489" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.75 7.75C4.75 6.64543 5.64543 5.75 6.75 5.75H17.25C18.3546 5.75 19.25 6.64543 19.25 7.75V16.25C19.25 17.3546 18.3546 18.25 17.25 18.25H6.75C5.64543 18.25 4.75 17.3546 4.75 16.25V7.75Z"></path>
        <path stroke="#3eb489" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.5 6.5L12 12.25L18.5 6.5"></path>
    </svg>
    `,
    msg: "E-mail za oporavak lozinke poslan."
};

// Dobivanje i pohrana vrijednosti parametra status iz URL-a
const status = paramValue("status");

// Uspoređivanje status varijable sa preddefiniranim vrijednostima
// Prikazivanje korisniku poruke na temelju statusa (parametra) u URL-u 
if(status === FAIL.status) {
    displayPopupMessage(FAIL.icon, FAIL.msg);
} 
else if(status === FAIL_EMAIL.status) {
    displayPopupMessage(FAIL_EMAIL.icon, FAIL_EMAIL.msg);
}
else if(status === SUCCESS.status) {
    displayPopupMessage(SUCCESS.icon, SUCCESS.msg);
}