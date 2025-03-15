<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="details.css">
    <link rel="shortcut icon" href="../../img/favicon2.png" type="image/x-icon">
    <title>Zaboravio sam lozinku</title>
</head>
<body>
    <?php include_once("pass_recover_forms_display.php"); ?>
    <form id="frmEmail" action="../../../src/authentication/password_recovery.php" method="post">
        <input type="email" name="email" id="email" placeholder="E-mail">
        <input type="submit" value="Šalji e-mail za oporavak lozinke">
        <div id="additional-option">
            <a href="../" id="back">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.25 4.75L4.75 9L9.25 13.25"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.5 9H15.25C17.4591 9 19.25 10.7909 19.25 13V19.25"></path>
                </svg>
                <span>Natrag</span>
            </a>
        </div>
    </form>
    <form id="frmPass" action="../../src/authentication/" method="post">
        <input type="password" name="password" id="password" placeholder="Nova lozinka">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Potvrdite lozinku">
        <input type="submit" value="Promjeni lozinku">
        <div id="back">
            <a href="../">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.25 4.75L4.75 9L9.25 13.25"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.5 9H15.25C17.4591 9 19.25 10.7909 19.25 13V19.25"></path>
                </svg>
                <span>Natrag</span>
            </a>
        </div>
    </form>

    <script src="display_user_msg.js" type="module"></script>
</body>
</html>