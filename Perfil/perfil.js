/**
 * Sistema de tabs do perfil
 */
const tabs = document.querySelectorAll(".tab")
const sections = {
  pedidos: document.querySelector(".orders-section"),
  dados: document.querySelector(".user-data-section"),
  endereco: document.querySelector(".addresses-section"),
  favoritos: null, // Ainda não implementado
}

if (tabs.length > 0) {
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove active de todas as tabs
      tabs.forEach((t) => t.classList.remove("active"))

      // Adiciona active na tab clicada
      tab.classList.add("active")

      // Aqui você pode adicionar lógica para mostrar/esconder seções
      const tabName = tab.getAttribute("data-tab")
      console.log("Tab selecionada:", tabName)
    })
  })
}
