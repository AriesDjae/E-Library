// Menu toggle functionality
const mainMenu = document.querySelector('.main-menu');
const menuBtn = document.querySelector('.bi-list');
const closeBtn = document.querySelector('.bi-x');
const dropdowns = document.querySelectorAll('.dropdown');

// Toggle menu
function openmenu() {
    mainMenu.style.right = "0";
}

function closemenu() {
    mainMenu.style.right = "-250px";
}

// Handle dropdown clicks on mobile
dropdowns.forEach(dropdown => {
    const dropbtn = dropdown.querySelector('.dropbtn');
    dropbtn.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            dropdown.classList.toggle('active');
            
            // Close other dropdowns
            dropdowns.forEach(other => {
                if (other !== dropdown) {
                    other.classList.remove('active');
                }
            });
        }
    });
});

// Close menu when clicking outside
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768) {
        if (!mainMenu.contains(e.target) && !menuBtn.contains(e.target)) {
            closemenu();
        }
    }
});