<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="shortcut icon" href="../img/favicon2.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Editor bilješke</title>
</head>
<body>
    <?php include_once("../../src/note controller/load_note_editor_form.php"); ?>
    <a href="../" id="back">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.25 4.75L4.75 9L9.25 13.25"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.5 9H15.25C17.4591 9 19.25 10.7909 19.25 13V19.25"></path>
        </svg>
        <span>Natrag</span>
    </a>
</body>
</html>