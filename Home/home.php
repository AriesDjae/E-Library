<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="footer.css">
    <style>
        body {
            margin-bottom: 0; /* Remove bottom margin */
            min-height: 100vh; /* Ensure minimum height */
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1; /* Make main content take available space */
        }

        .contact-float-button {
            position: fixed;
            top: 100px;
            right: 30px;
            background-color: #0A3697;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .contact-float-button:hover {
            background-color: #082a74;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="text">
    <div class="text-content">
        <p>let's make the best investments</p>
        <h1>There is no</h1>
        <h1>friend as loyal</h1>
        <h1>as a book</h1>
        <p>Read. Listen. Learn. Repeat</p>
    </div>     
</div>

<div class="search-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
            <input type="text" name="search" id="search" placeholder="Pencarian">
        </div>

        <div class="content">

            <section class="rekobuku">
                <h2>Rekomendasi Buku</h2>
            </section>
            <section class="buku">
                <h2>Koleksi Buku</h2>
            </section>
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

        <div id="contact">
        <div class="container-contact">
          <div class="row">
            <div class="contact-left">
              <h1 class="sub-title">Contact Me</h1>
              <p><i class="bi bi-send"></i>ariesdjaenuri24@gmail.com</p>
              <p><i class="bi bi-telephone"></i>085338557296</p>
              <div class="social-icons">
                <a href="https://facebook.com/"><i class="bi bi-facebook"></i></a>
                <a href="https://twitter.com/"><i class="bi bi-twitter-x"></i></a>
                <a href="https://instagram.com/"><i class="bi bi-instagram"></i></a>
                <a href="https://linkedin.com/"><i class="bi bi-linkedin"></i></a>
              </div>
              <a href="src/CV Aris sementara.pdf" download class="btn cv">Download CV</a>
            </div>
          </div>
        </div>
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