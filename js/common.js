/* Menu Hamburguer - Usado em todas as páginas */
const hamburguer = document.querySelector(".hamburguer-menu")
const nav = document.querySelector("nav")

if (hamburguer && nav) {
  hamburguer.addEventListener("click", () => {
    hamburguer.classList.toggle("open")
    nav.classList.toggle("open")
  })
}

/* Dropdown do usuário - Usado em todas as páginas */
const userMenu = document.querySelector(".usuario-menu")
const dropUser = document.querySelector(".dropdown-user")

if (userMenu && dropUser) {
  userMenu.addEventListener("click", () => {
    dropUser.classList.toggle("open")
  })
}

/* Fecha dropdown ao clicar fora */
document.addEventListener("click", (e) => {
  if (dropUser && userMenu) {
    if (!userMenu.contains(e.target) && !dropUser.contains(e.target)) {
      dropUser.classList.remove("open")
    }
  }
})
