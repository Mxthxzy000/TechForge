const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav")

hamburguer.addEventListener("click", () => {
    hamburguer.classList.toggle("open");
    nav.classList.toggle("open")
});
