/**
 * Notificação Centralizada
 * Importa o sistema de notificações padronizado
 */
const notificationScript = document.createElement("script")
notificationScript.src = "../js/notification.js"
document.head.appendChild(notificationScript)

/**
 * Menu Hamburguer - Usado em todas as páginas
 */
const hamburguer = document.querySelector(".hamburguer-menu")
const nav = document.querySelector("nav")

if (hamburguer && nav) {
  hamburguer.addEventListener("click", () => {
    hamburguer.classList.toggle("open")
    nav.classList.toggle("open")
  })
}

/**
 * Dropdown do usuário - Usado em todas as páginas
 */
const userMenu = document.querySelector(".usuario-menu")
const dropUser = document.querySelector(".dropdown-user")

if (userMenu && dropUser) {
  userMenu.addEventListener("click", () => {
    dropUser.classList.toggle("open")
  })
}

/**
 * Fecha dropdown ao clicar fora
 */
document.addEventListener("click", (e) => {
  if (dropUser && userMenu) {
    if (!userMenu.contains(e.target) && !dropUser.contains(e.target)) {
      dropUser.classList.remove("open")
    }
  }
})

const carrinho = document.getElementById("carrinho")

if (carrinho) {
  carrinho.addEventListener("click", () => {
    window.location.href = "../Carrinho/carrinho.php" // link da página do carrinho
  })
}
