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