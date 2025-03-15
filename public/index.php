<?php session_start(); ?> 
<?php include_once("../src/db connection/db_connection.php"); ?>
<?php date_default_timezone_set('Europe/Zagreb'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="shortcut icon" href="img/favicon2.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/style.css">
    <title>To-Do</title>
</head>
<body>
    <nav>
        <div class="nav-heading">
            <img src="img/favicon2.png" alt="logo">
            <span>To-Do</span>
        </div>
        <div class="nav-body">
            <div onclick="window.location.href = '?';">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.2502 18.25H7.75C6.64543 18.25 5.75 17.3546 5.75 16.25V6.75C5.75 5.64543 6.64543 4.75 7.75 4.75H16.2502C17.3548 4.75 18.2502 5.64543 18.2502 6.75V16.25C18.2502 17.3546 17.3548 18.25 16.2502 18.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M14.25 8.75L13.75 8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M14.25 11.75L13.75 11.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9.75 4.75V19.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span>Sve bilješke</span>
            </div>
            <div id="filter-star-btn">
                <svg width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.75L13.75 10.25H19.25L14.75 13.75L16.25 19.25L12 15.75L7.75 19.25L9.25 13.75L4.75 10.25H10.25L12 4.75Z"></path>
                </svg>
                <span>Bilješke sa zvjezdicom</span>
            </div>
            <div id="filter-completion1-btn">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.2502 19.25H6.75C5.64543 19.25 4.75 18.3546 4.75 17.25V6.75C4.75 5.64543 5.64543 4.75 6.75 4.75H17.2502C18.3548 4.75 19.2502 5.64543 19.2502 6.75V17.25C19.2502 18.3546 18.3548 19.25 17.2502 19.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M8.75 12L11 14.25L15.25 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span>Izvršene bilješke</span>
            </div>
            <div id="filter-completion0-btn">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.2502 19.25H6.75C5.64543 19.25 4.75 18.3546 4.75 17.25V6.75C4.75 5.64543 5.64543 4.75 6.75 4.75H17.2502C18.3548 4.75 19.2502 5.64543 19.2502 6.75V17.25C19.2502 18.3546 18.3548 19.25 17.2502 19.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9.75 14.25L14.25 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M14.25 14.25L9.75 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>    
                <span>Neizvršene bilješke</span>
            </div>
            <div id="category-statistics">
                <?php include_once("../src/statistics/note_cat_stat.php"); ?>
            </div>
        </div>
        <div class="nav-footer">
            <div>
                <span onclick="window.location.href='#';">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 64 40">
                        <path fill="rgb(144, 96, 76)" fill-rule="evenodd" d="M31,9.5c12.131,0,22,9.869,22,22	c0,11.113-8.284,20.322-19,21.79V38h4l2-5.029L34,33v-4.73c0-1.254,1.016-2.27,2.27-2.27c0.902,0,1.73,0,1.73,0v-4h-4.255	c-1.524,0-2.985,0.605-4.062,1.683C28.605,24.76,28,26.221,28,27.745c0,2.653,0,5.226,0,5.226h-4V38h4v15.29	C17.284,51.822,9,42.613,9,31.5C9,19.369,18.869,9.5,31,9.5z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span onclick="window.location.href='#';">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 64 40">
                        <path fill="rgb(144, 96, 76)" d="M 31.820312 12 C 13.438312 12 12 13.439312 12 31.820312 L 12 32.179688 C 12 50.560688 13.438313 52 31.820312 52 L 32.179688 52 C 50.561688 52 52 50.560688 52 32.179688 L 52 32 C 52 13.452 50.548 12 32 12 L 31.820312 12 z M 43.994141 18 C 45.099141 17.997 45.997 18.889141 46 19.994141 C 46.003 21.099141 45.110859 21.997 44.005859 22 C 42.900859 22.003 42.003 21.110859 42 20.005859 C 41.997 18.900859 42.889141 18.003 43.994141 18 z M 31.976562 22 C 37.498562 21.987 41.987 26.454563 42 31.976562 C 42.013 37.498562 37.545437 41.987 32.023438 42 C 26.501437 42.013 22.013 37.545437 22 32.023438 C 21.987 26.501437 26.454563 22.013 31.976562 22 z M 31.986328 26 C 28.672328 26.008 25.992 28.701625 26 32.015625 C 26.008 35.328625 28.700672 38.008 32.013672 38 C 35.327672 37.992 38.008 35.299328 38 31.986328 C 37.992 28.672328 35.299328 25.992 31.986328 26 z"></path>
                    </svg>
                </span>
                <span onclick="window.location.href='#';">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 64 40">
                        <path fill="rgb(144, 96, 76)" d="M48.584,16.847c0,0,7.416,8.589,7.416,24.061v3.244c0,0-9.366,6.103-12.22,6.103l-2.769-4.026	c1.721-0.771,4.068-2.109,4.068-2.109l-0.858-0.664c0,0-5.487,2.542-12.221,2.542s-12.221-2.542-12.221-2.542l-0.858,0.664	c0,0,2.347,1.338,4.068,2.109l-2.769,4.026C17.366,50.255,8,44.152,8,44.152v-3.244c0-15.472,7.416-24.061,7.416-24.061	s5.073-2.456,9.757-3.102l1.454,2.755c0,0,2.31-0.535,5.373-0.535s5.373,0.535,5.373,0.535l1.453-2.755	C43.511,14.391,48.584,16.847,48.584,16.847z M24.009,38.647c2.36,0,4.274-2.149,4.274-4.801c0-2.651-1.913-4.801-4.274-4.801	s-4.274,2.149-4.274,4.801C19.735,36.498,21.648,38.647,24.009,38.647z M39.991,38.647c2.36,0,4.274-2.149,4.274-4.801	c0-2.651-1.914-4.801-4.274-4.801s-4.274,2.149-4.274,4.801C35.717,36.498,37.631,38.647,39.991,38.647z"></path>
                    </svg>
                </span>
            </div>
            <div>
                Sva prava pridržana
            </div>
        </div>
    </nav>
    <div class="main">
        <div class="main-header">
            <div id="profile"></div>
            <div id="profile-settings"></div>
            <?php include_once("../src/authentication/check_login_status.php"); ?>
            <div class="search-container">
                <input type="text" id="search" name="search" placeholder="Pretražite ovdje...">
                <svg id="search-notes-btn" width="28" height="28" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 19.25L15.5 15.5M4.75 11C4.75 7.54822 7.54822 4.75 11 4.75C14.4518 4.75 17.25 7.54822 17.25 11C17.25 14.4518 14.4518 17.25 11 17.25C7.54822 17.25 4.75 14.4518 4.75 11Z"></path>
                </svg>
            </div>
        </div>
        <div class="section">
            <h2 class="section-heading">BILJEŠKE</h2>
            <a href="note editor/">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5.75V18.25"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.25 12L5.75 12"></path>
                </svg>
                <span>Dodaj novu bilješku</span>
            </a>
            <div id="notes-container">
                <?php include_once("../src/note controller/display_notes.php"); ?>
            </div> 
        </div>
        <form id="logout" action="../src/authentication/logout.php" method="GET" onclick="this.submit();">
            <span>
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 8.75L19.25 12L15.75 15.25"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 12H10.75"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.25 4.75H6.75C5.64543 4.75 4.75 5.64543 4.75 6.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H15.25"></path>
                </svg>
            </span>
            <button type="submit">Odjavi me</button>
        </form>
        <div id="hide-background"></div>
        <div id="note-view">    
        </div>
    </div>

    <script src="JS/display_user_msg.js" type="module"></script>
    <script src="JS/expiring_deadlines.js"></script>
    <script src="JS/profile_settings.js"></script>
    <script src="../src/user profile/fetch_user_info.js"></script>
    <script src="../src/note controller/fetch_display_note.js"></script>
    <script src="../src/note controller/fetch_note_star.js"></script>
    <script src="../src/note controller/fetch_note_completion.js"></script>
    <script src="../src/filter notes/fetch_filter_star.js"></script>
    <script src="../src/filter notes/fetch_filter_completion1.js"></script>
    <script src="../src/filter notes/fetch_filter_completion0.js"></script> 
    <script src="../src/filter notes/fetch_search.js"></script> 
</body>
</html>