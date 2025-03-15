<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="shortcut icon" href="../img/favicon2.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Autentikacija</title>
</head>
<body>
    <div id="forms-container">
        <div id="anim1"></div>
        <div id="anim2"></div>
        <div id="anim3"></div>
        <form class="signup-form" action="../../src/authentication/signup.php" method="POST">
                <h1>Registracija</h1>
                <div class="grp-inpt">
                    <div class="input-info-container">
                        <input class="name-input" type="text" name="name" id="name" autocomplete="on" placeholder="Ime">
                        <span>Dopuštena samo slova</span>
                    </div>
                    <div class="input-info-container">
                        <input class="name-input" type="text" name="surname" id="surname" autocomplete="on" placeholder="Prezime">
                        <span>Dopuštena samo slova</span>
                    </div>
                </div>
                <div class="grp-inpt">
                    <input type="text" name="user" id="user" autocomplete="on" placeholder="Korisničko ime">
                    <input type="email" name="email" id="email" autocomplete="on" placeholder="E-mail">
                </div>
                <div class="grp-inpt">
                    <div class="input-info-container">
                        <input type="password" name="password" id="password" placeholder="Lozinka">
                        <span>Najmanje 8 znakova</span>
                    </div>
                    <input type="password" name="confirm_password" id="confirm-password" placeholder="Ponovite lozinku">
                </div>
                <div class="input-info-container">
                    <input type="submit" disabled value="Registracija">
                    <span>Sva polja su obvezna</span>
                </div>
        </form>
        <form class="login-form" action="../../src/authentication/login.php" method="POST">
            <h1>Prijava</h1>
            <input type="text" name="user_email" id="user_email" autocomplete="on" placeholder="Korisničko ime / E-mail">
            <input type="password" name="password" id="password2" placeholder="Lozinka">
            <input type="submit" value="Prijava">
        </form>
        <div id="additional-options">
            <div id="change-form">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="3.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></circle>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12.25 19.25H6.94953C5.77004 19.25 4.88989 18.2103 5.49085 17.1954C6.36247 15.7234 8.23935 14 12.25 14"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 14.75V19.25"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 17L14.75 17"></path>
                </svg>
                <span>Izradi račun</span>
            </div>
            <a id="password-recovery" href="password recovery/">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13.25C17.3472 13.25 19.25 11.3472 19.25 9C19.25 6.65279 17.3472 4.75 15 4.75C12.6528 4.75 10.75 6.65279 10.75 9C10.75 9.31012 10.7832 9.61248 10.8463 9.90372L4.75 16V19.25H8L8.75 18.5V16.75H10.5L11.75 15.5V13.75H13.5L14.0963 13.1537C14.3875 13.2168 14.6899 13.25 15 13.25Z"></path>
                    <path stroke="currentColor" d="M16.5 8C16.5 8.27614 16.2761 8.5 16 8.5C15.7239 8.5 15.5 8.27614 15.5 8C15.5 7.72386 15.7239 7.5 16 7.5C16.2761 7.5 16.5 7.72386 16.5 8Z"></path>
                </svg>
                <span>Zaboravio sam lozinku</span>
            </a>
        </div>
    </div>

    <script src="../../src/authentication/form_validation.js"></script>
    <script src="JS/auth_forms_display.js" type="module"></script>
    <script src="JS/form_focus.js"></script>
    <script src="JS/display_user_msg.js" type="module"></script>
</body>
</html>