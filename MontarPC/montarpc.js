document.addEventListener("DOMContentLoaded", () => {
  const components = {
    cpu: [],
    gpu: [],
    placaMae: [],
    ram: [],
    armazenamento: [],
    fonte: [],
    gabinete: [],
    cooler: [],
  }

  const selectedComponents = {
    cpu: null,
    gpu: null,
    placaMae: null,
    ram: null,
    armazenamento: null,
    fonte: null,
    gabinete: null,
    cooler: null,
  }

  // Load components from database
  function loadComponents() {
    fetch("../Catalogo/funcionalidadesCatalogo.php?action=getAll")
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error("Erro:", data.error)
          return
        }

        // Categorize products by type
        data.produtos.forEach((product) => {
          const tipo = product.tipoProduto.toLowerCase()

          if (tipo.includes("processador")) {
            components.cpu.push(product)
          } else if (tipo.includes("placa de vídeo") || tipo.includes("placa de video")) {
            components.gpu.push(product)
          } else if (tipo.includes("placa-mãe") || tipo.includes("placa-mae")) {
            components.placaMae.push(product)
          } else if (tipo.includes("memória") || tipo.includes("memoria") || tipo.includes("ram")) {
            components.ram.push(product)
          } else if (tipo.includes("ssd") || tipo.includes("armazenamento")) {
            components.armazenamento.push(product)
          } else if (tipo.includes("fonte")) {
            components.fonte.push(product)
          } else if (tipo.includes("gabinete")) {
            components.gabinete.push(product)
          } else if (tipo.includes("cooler")) {
            components.cooler.push(product)
          }
        })

        populateSelects()
      })
      .catch((error) => {
        console.error("Erro ao carregar componentes:", error)
      })
  }

  function populateSelects() {
    Object.keys(components).forEach((key) => {
      const select = document.getElementById(key)
      const products = components[key]

      products.forEach((product) => {
        const option = document.createElement("option")
        option.value = product.idProduto
        option.textContent = `${product.nomeProduto} - R$ ${formatPrice(product.precoProduto)}`
        option.dataset.price = product.precoProduto
        option.dataset.name = product.nomeProduto
        select.appendChild(option)
      })

      select.addEventListener("change", (e) => {
        handleComponentChange(key, e.target)
      })
    })
  }

  function handleComponentChange(componentType, selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex]

    if (selectedOption.value === "") {
      selectedComponents[componentType] = null
    } else {
      selectedComponents[componentType] = {
        id: selectedOption.value,
        name: selectedOption.dataset.name,
        price: Number.parseFloat(selectedOption.dataset.price),
      }
    }

    updateComponentPrice(componentType)
    updateSummary()
  }

  function updateComponentPrice(componentType) {
    const priceElement = document.getElementById(`${componentType}-price`)
    const component = selectedComponents[componentType]

    if (component) {
      priceElement.textContent = `R$ ${formatPrice(component.price)}`
    } else {
      priceElement.textContent = "R$ 0,00"
    }
  }

  function updateSummary() {
    const summaryList = document.getElementById("summaryList")
    const selectedItems = Object.entries(selectedComponents).filter(([key, value]) => value !== null)

    if (selectedItems.length === 0) {
      summaryList.innerHTML =
        '<p style="color: #666; text-align: center; padding: 20px;">Nenhum componente selecionado</p>'
      document.getElementById("totalPrice").textContent = "R$ 0,00"
      return
    }

    const componentLabels = {
      cpu: "Processador",
      gpu: "Placa de Vídeo",
      placaMae: "Placa-Mãe",
      ram: "Memória RAM",
      armazenamento: "Armazenamento",
      fonte: "Fonte",
      gabinete: "Gabinete",
      cooler: "Cooler",
    }

    summaryList.innerHTML = selectedItems
      .map(
        ([key, component]) => `
      <div class="summary-item">
        <span class="summary-item-name">${componentLabels[key]}</span>
        <span class="summary-item-value">R$ ${formatPrice(component.price)}</span>
      </div>
    `,
      )
      .join("")

    const total = selectedItems.reduce((sum, [key, component]) => sum + component.price, 0)
    document.getElementById("totalPrice").textContent = `R$ ${formatPrice(total)}`
  }

  function formatPrice(price) {
    return Number.parseFloat(price).toFixed(2).replace(".", ",")
  }

  document.getElementById("saveBuildBtn").addEventListener("click", () => {
    const nomeSetup = document.getElementById("nomeSetup").value.trim()
    const observacoes = document.getElementById("observacoes").value.trim()

    if (!nomeSetup) {
      alert("Por favor, dê um nome para sua montagem")
      return
    }

    const selectedItems = Object.entries(selectedComponents).filter(([key, value]) => value !== null)

    if (selectedItems.length === 0) {
      alert("Selecione pelo menos um componente para salvar a montagem")
      return
    }

    const total = selectedItems.reduce((sum, [key, component]) => sum + component.price, 0)

    const buildData = {
      nomeSetup: nomeSetup,
      cpu: selectedComponents.cpu?.name || "",
      gpu: selectedComponents.gpu?.name || "",
      placaMae: selectedComponents.placaMae?.name || "",
      ram: selectedComponents.ram?.name || "",
      armazenamento: selectedComponents.armazenamento?.name || "",
      fonte: selectedComponents.fonte?.name || "",
      gabinete: selectedComponents.gabinete?.name || "",
      cooler: selectedComponents.cooler?.name || "",
      observacoes: observacoes,
      precoEstimado: total,
    }

    const formData = new FormData()
    Object.keys(buildData).forEach((key) => {
      formData.append(key, buildData[key])
    })

    const saveBtn = document.getElementById("saveBuildBtn")
    saveBtn.disabled = true
    saveBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Salvando...'

    fetch("montarpcAPI.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.needsLogin) {
          alert("Você precisa fazer login para salvar sua montagem")
          window.location.href = "../login/login.php"
          return
        }

        if (data.error) {
          alert(data.error)
          saveBtn.disabled = false
          saveBtn.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Salvar Montagem'
          return
        }

        if (data.success) {
          alert(data.message)
          window.location.href = "../Perfil/perfil.php"
        }
      })
      .catch((error) => {
        console.error("Erro ao salvar montagem:", error)
        alert("Erro ao salvar montagem")
        saveBtn.disabled = false
        saveBtn.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Salvar Montagem'
      })
  })

  loadComponents()
})
