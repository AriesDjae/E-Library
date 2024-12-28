<footer class="footer">
    <div class="footer-content">
        <div class="footer-section about-section">
            <h3>About Librarylink</h3>
            <p>Your digital gateway to knowledge and learning. Explore our vast collection of books and resources.</p>
        </div>

        <div class="footer-section connect-section">
            <h3>Connect With Us</h3>
            <div class="social-links">
                <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>

        <div class="footer-section contact-section">
            <h3>Contact Us</h3>
            <ul class="contact-info">
                <li><i class="bi bi-geo-alt"></i> Jl. Contoh No. 123, Yogyakarta</li>
                <li><i class="bi bi-telephone"></i> (0274) 123456</li>
                <li><i class="bi bi-envelope"></i> info@e-library.ac.id</li>
                <li><i class="bi bi-clock"></i> Mon - Fri: 08:00 - 16:00</li>
            </ul>
            <button onclick="openContactForm()" class="footer-contact-btn">
                <i class="bi bi-envelope"></i> Send Message
            </button>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <span id="currentYear"></span> Librarylink. All rights reserved.</p>
    </div>
</footer>

<div id="footerContactModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Contact Us</h2>
        <p id="msg" style="color: green; font-weight: bold;"></p>
        <form id="footerContactForm" name="submit-to-google-sheet" class="contact-form">
            <div class="form-group">
                <input type="text" name="name" placeholder="Nama Lengkap" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="tel" name="phone" placeholder="Nomor Telepon" required>
            </div>
            <div class="form-group">
                <textarea name="message" rows="4" placeholder="Pesan Anda" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Kirim Pesan</button>
        </form>
    </div>
</div>

<script>
    // Set current year in footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // Google Apps Script submission
    const scriptURL = 'https://script.google.com/macros/s/AKfycbyz-0Qfi0-N3R6ZiJa70kPh3sZzJ2w8fspQiRJxpJsmF4xChrnkSrzuYDkAbAjRB-zN/exec';
    const form = document.forms['submit-to-google-sheet'];
    const msg = document.getElementById("msg");

    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch(scriptURL, { method: 'POST', body: new FormData(form) })
            .then(response => {
                msg.innerHTML = "Message sent successfully";
                setTimeout(() => { msg.innerHTML = ""; }, 5000);
                form.reset();
            })
            .catch(error => console.error('Error!', error.message));
    });

    // Open and close modal
    function openContactForm() {
        document.getElementById('footerContactModal').style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.close-modal').onclick = function() {
            document.getElementById('footerContactModal').style.display = 'none';
        };
    });
</script>