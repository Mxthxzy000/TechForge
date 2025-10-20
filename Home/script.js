/**
 * Slider automático de banners
 */
let counter = 1
const totalSlides = 4

// Marca o primeiro slide como ativo
const radio1 = document.getElementById("radio1")
if (radio1) {
  radio1.checked = true
}

/**
 * Avança para próxima imagem do slider
 */
function nextImage() {
  counter++
  if (counter > totalSlides) {
    counter = 1
  }
  const radioBtn = document.getElementById("radio" + counter)
  if (radioBtn) {
    radioBtn.checked = true
  }
}

// Troca de slide a cada 6 segundos
setInterval(nextImage, 6000)
