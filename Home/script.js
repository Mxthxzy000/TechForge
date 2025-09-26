const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav")


hamburguer.addEventListener("click", () => {
    hamburguer.classList.toggle("open");
    nav.classList.toggle("open")
});

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

