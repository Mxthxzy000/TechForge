// -----------------------------
// Seletores principais - Apenas Navbar e Footer
// -----------------------------
const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav");

// -----------------------------
// Funções - Apenas Navbar
// -----------------------------

/**
 * Alterna o menu hamburguer
 */
const toggleHamburguerMenu = () => {
  hamburguer.classList.toggle("open");
  nav.classList.toggle("open");
};

// -----------------------------
// Event Listeners - Apenas Navbar
// -----------------------------

// Menu hamburguer
if (hamburguer && nav) {
  hamburguer.addEventListener("click", toggleHamburguerMenu);
}

const User = document.querySelector(".usuario-menu");
const dropUser = document.querySelector(".dropdown-user")


User.addEventListener("click", () => {
    dropUser.classList.toggle("open")
});

let counter = 1;
document.getElementById("radio1").checked = true;

function nextimage() {
    counter++;
    if(counter > 4){
        counter = 1;
    }
    document.getElementById("radio" + counter).checked = true;
}

// chama a função a cada 2 segundos
setInterval(nextimage, 6000);

