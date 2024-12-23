<footer class="footer">
    <div class="footer-content">
        <div class="footer-section about-section">
            <h3>About E-Library</h3>
            <p>Your digital gateway to knowledge and learning. Explore our vast collection of books and resources.</p>
        </div>
        
        <div class="footer-section connect-section">
            <h3>Connect With Us</h3>
            <div class="social-links">
                <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" title="Twitter"><i class="bi bi-twitter-x"></i></a>
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
        <p>&copy; <?php echo date('Y'); ?> E-Library. All rights reserved.</p>
    </div>
</footer>

<div id="footerContactModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Contact Us</h2>
        <form id="footerContactForm" class="contact-form">
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
function openContactForm() {
    document.getElementById('footerContactModal').style.display = 'block';
}

document.querySelector('.close-modal').onclick = function() {
    document.getElementById('footerContactModal').style.display = 'none';
}

document.getElementById('footerContactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Your existing form submission code
});
</script>
