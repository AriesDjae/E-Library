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