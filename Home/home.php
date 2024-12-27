<?php
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;700&
    display=swap" rel="stylesheet">

    <!-- Feather Icons -->
     <script src="https://unpkg.com/feather-icons"></script>

    <link rel="stylesheet" href="style.css?v=1.1">
    <link rel="stylesheet" href="contact.css?v=1.1">
    <link rel="stylesheet" href="footer.css?v=1.1">
   
</head>
<body>

<!-- Feather Icons -->
<script>
    feather.replace()
</script>


<div class="hero-image">
    <section class="hero" id="home">
    <main class="text">
    <p>let's make the best investments</p>
    <h1>There is no</h1>
    <h1>friend as loyal</h1>
    <h1>as a book</h1>
    <p1>Read. Listen. Learn. Repeat</p1>
</main>
</section>
</div>



    <div class="content">
        <section id="collectin" class="collection">
            <h2>Collections</h2>
        </section>
        <div class="card-container">
            <div class="card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="bintang">
                <h3>Bintang</h3>
                <p>Tereliye</p>
                <button>Show Details</button>
            </div>
            <div class="card">
                <img src="/E-Library/Home/img/bulan.jpg" alt="Book Cover">
                <h3>Bulan</h3>
                <p>Tereliye</p>
                <button><a href="NavContent/resources/detail_buku.php?id=1" class="button">Show Details</a></button>
            </div>
            <div class="card">
                <img src="/E-Library/Home/img/halfblood.jpg" alt="Book Cover">
                <h3>Halfblood</h3>
                <p>J.K Rowling></p>
                <button>Show Details</button>
            </div>
            <div class="card">
                <img src="/E-Library/Home/img/why.jpg" alt="Book Cover">
                <h3>Why</h3>
                <p>Wilson</p>
                <button>Show Details</button>
            </div>
            <div class="card">
                <img src="/E-Library/Home/img/matahari.jpg" alt="bintang">
                <h3>Matahari</h3>
                <p>Tereliye</p>
                <button>Show Details</button>
            </div>
            <div class="card">
                <img src="/E-Library/Home/img/why.jpg" alt="Book Cover">
                <h3>Why</h3>
                <p>Wilson</p>
                <button>Show Details</button>
            </div>
            <div class="card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="Book Cover">
                <h3>The Hidden Mystery Behind</h3>
                <p>Wilson</p>
                <button>Show Details</button>
            </div>
        </div>
    </div>
    </div>

    <div class="category-book">
        <div class="top-container">
            <h2>Top Categories Book</h2>
            <div class="categories">
                <div class="category">
                    <div class="circle">
                        <img src="/E-Library/Home/img/bintang.jpg" alt="Grow Flower">
                        <div class="dashed-circle"></div>
                    </div>
                    <p class="category-name">Grow Flower (7)</p>
                </div>
                <div class="category">
                    <div class="circle">
                        <img src="/E-Library/Home/img/bintang.jpg" alt="Adventure Book">
                        <div class="dashed-circle"></div>
                    </div>
                    <p class="category-name">Adventure Book (4)</p>
                </div>
                <div class="category">
                    <div class="circle">
                        <img src="/E-Library/Home/img/bintang.jpg" alt="Romance Books">
                        <div class="dashed-circle"></div>
                    </div>
                    <p class="category-name">Romance Books (80)</p>
                </div>
                <div class="category">
                    <div class="circle">
                        <img src="/E-Library/Home/img/bintang.jpg" alt="Design Low Book">
                        <div class="dashed-circle"></div>
                    </div>
                    <p class="category-name">Design Low Book (6)</p>
                </div>
                <div class="category">
                    <div class="circle">
                        <img src="/E-Library/Home/img/bintang.jpg" alt="Safe Home">
                        <div class="dashed-circle"></div>
                    </div>
                    <p class="category-name">Safe Home (5)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="recomen">
        <h1>Book Recommendations</h1>
        <div class="book-grid">
            <div class="book-card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="Book Cover">
                <p class="book-title">Simple Things You To Save BOOK</p>
                <button>Show Details</button>
            </div>
            <div class="book-card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="Book Cover">
                <p class="book-title hidden">Placeholder</p>
                <button>Show Details</button>
            </div>
            <div class="book-card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="Book Cover">
                <p class="book-title hidden">Placeholder</p>
                <button>Show Details</button>
            </div>
            <div class="book-card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="Book Cover">
                <p class="book-title hidden">Placeholder</p>
                <button>Show Details</button>
            </div>
            <div class="book-card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="Book Cover">
                <p class="book-title hidden">Placeholder</p>
                <button>Show Details</button>
            </div>
            <div class="book-card">
                <img src="/E-Library/Home/img/bintang.jpg" alt="Book Cover">
                <p class="book-title hidden">Placeholder</p>
                <button>Show Details</button>
            </div>
        </div>
        </div>
        </div>

    <div class="contact-page">
        <!-- Peta Lokasi -->
        <h2>Our Location</h2>
        <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.8710037644193!2d110.357049!3d-7.749987!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59b3a4b6186f%3A0x1209ae547c8f3d0d!2sUniversitas%20Islam%20Indonesia!5e0!3m2!1sid!2sid!4v1702657000000!5m2!1sid!2sid"
        width="600"
        height="450"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
        </iframe>
        <!-- Contact -->

    </div>
      
    </div> <!-- Close your main content div -->
    


<?php include '../includes/footer.php'; ?>

<script>
// Add this to your existing script
function showContactPage() {
    document.getElementById('contact-section').style.display = 'block';
    window.scrollTo({
        top: document.getElementById('contact-section').offsetTop,
        behavior: 'smooth'
    });
}

// Check URL parameter on page load
if (window.location.search.includes('page=contact')) {
    showContactPage();
}

document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('contactform.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('form-message');
        messageDiv.className = data.status;
        messageDiv.textContent = data.message;
        
        if (data.status === 'success') {
            this.reset();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('form-message').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
    });
});
</script>
</body>
</html>