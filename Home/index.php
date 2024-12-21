<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library Kampus</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <h1>E-Library</h1>
            </div>

            <!-- Navbar -->

            <nav class="navContent">
                <i class="bi bi-list" onclick="openmenu()"></i>
                <ul class="main-menu">
                    <i class="bi bi-x" onclick="closemenu()"></i>
                    <li>
                        <form method="post"><button type="submit" name="page" value="home">Home</button></form>
                    </li>
                    <li class="dropdown">
                        <form method="post"><button type="submit" name="page" value="collections" class="dropbtn">Collections</button></form>
                        <div class="dropdown-content">
                            <form method="post"><button type="submit" name="page" value="catalog">Book Catalog</button></form>
                            <form method="post"><button type="submit" name="page" value="new-books">New Books</button></form>
                            <form method="post"><button type="submit" name="page" value="popular-books">Popular Books</button></form>
                            <form method="post"><button type="submit" name="page" value="recommendations">Recommendations</button></form>
                        </div>
                    </li>
                    <li class="dropdown">
                        <form method="post"><button type="submit" name="page" value="categories" class="dropbtn">Categories</button></form>
                        <div class="dropdown-content">
                            <form method="post"><button type="submit" name="page" value="journals">Journals</button></form>
                            <form method="post"><button type="submit" name="page" value="scientific-papers">Scientific Papers</button></form>
                            <form method="post"><button type="submit" name="page" value="thesis">Thesis/Dissertation</button></form>
                        </div>
                    </li>
                    <li class="dropdown">
                        <form method="post"><button type="submit" name="page" value="services" class="dropbtn">Services</button></form>
                        <div class="dropdown-content">
                            <form method="post"><button type="submit" name="page" value="borrowing">Borrow</button></form>
                            <form method="post"><button type="submit" name="page" value="returning">Return</button></form>
                            <form method="post"><button type="submit" name="page" value="reading-room">Reading Room</button></form>
                        </div>
                    </li>
                    <li>
                        <form method="post"><button type="submit" name="page" value="account">My Account</button></form>
                    </li>
                </ul>   
            </nav>
        </div>

        <?php
$page = isset($_POST['page']) ? $_POST['page'] : 'home';

switch ($page) {
    // Home
    case 'home':
        include 'home.php';
        break;

    // Collections
    case 'collections':
        include 'resources/catalog.php';
        break;
    case 'catalog':
        include 'catalog.php';
        break;
    case 'new-books':
        include 'resources/new-arrivals.php';
        break;
    case 'popular-books':
        include 'resources/popular-books.php';
        break;
    case 'recommendations':
        include 'resources/recommended.php';
        break;
    case 'journals':
        include 'resources/journals.php';
        break;
    case 'scientific-papers':
        include 'resources/research-papers.php';
        break;
    case 'thesis':
        include 'resources/theses.php';
        break;

    // Services
    case 'borrowing':
        include 'services/borrowing.php';
        break;
    case 'returning':
        include 'services/returning.php';
        break;
    case 'reading-room':
        include 'services/reading-room.php';
        break;

    // Account
    case 'account':
        include 'services/account.php';
        break;

    // Register/Login
    case 'login':
        include 'Register-Login/Login.php';
        break;
    case 'signup':
        include 'Register-Login/Signup.php';
        break;

    // Default page
    default:
        include 'services/home.php';
        break;
}
?>