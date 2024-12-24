// nav small screen

var sidemeu = document.getElementById("sidemenu");

function openmenu(){
    sidemeu.style.right = "0";
}

function closemenu(){
    sidemeu.style.right = "-200px";
}

// Message

const scriptURL = 'https://script.google.com/macros/s/AKfycbyz-0Qfi0-N3R6ZiJa70kPh3sZzJ2w8fspQiRJxpJsmF4xChrnkSrzuYDkAbAjRB-zN/exec'
  const form = document.forms['submit-to-google-sheet']
  const msg = document.getElementById("msg");

  form.addEventListener('submit', e => {
    e.preventDefault()
    fetch(scriptURL, { method: 'POST', body: new FormData(form)})
      .then(response => {
        msg.innerHTML = "Message sent successfully"
        setTimeout(function(){
            msg.innerHTML = "";
        },5000);
        form.reset()
    })
      .catch(error => console.error('Error!', error.message));
  })

// Remove or comment out the smooth scroll code
/* 
document.querySelector('.contact-float-button').addEventListener('click', function(e) {
    e.preventDefault();
    const contactSection = document.querySelector('#contact');
    contactSection.scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
});
*/

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