/**
 * Sistema de tabs do perfil
 */
const tabs = document.querySelectorAll(".tab")
const sections = {
  pedidos: document.querySelector(".orders-section"),
  dados: document.querySelector(".user-data-section"),
  endereco: document.querySelector(".addresses-section"),
  favoritos: null,
}

if (tabs.length > 0) {
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      tabs.forEach((t) => t.classList.remove("active"))
      tab.classList.add("active")
      const tabName = tab.getAttribute("data-tab")
      console.log("Tab selecionada:", tabName)
    })
  })
}

const editButton = document.querySelector(".btn-secondary")
const modalOverlay = document.createElement("div")
modalOverlay.className = "modal-overlay"

// Create edit profile modal
modalOverlay.innerHTML = `
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Editar Perfil</h2>
      <button class="modal-close">&times;</button>
    </div>
    <form id="editProfileForm">
      <div class="form-group">
        <label class="form-label">Nome *</label>
        <input type="text" name="nomeUsuario" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Sobrenome *</label>
        <input type="text" name="sobrenomeUsuario" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Email *</label>
        <input type="email" name="emailUsuario" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Celular</label>
        <input type="text" name="celularUsuario" class="form-input">
      </div>
      <div class="form-group">
        <label class="form-label">Data de Nascimento</label>
        <input type="date" name="nascimentoUsuario" class="form-input">
      </div>
      <div class="form-actions">
        <button type="button" class="btn-cancel">Cancelar</button>
        <button type="submit" class="btn-save">Salvar Alterações</button>
      </div>
    </form>
  </div>
`

document.body.appendChild(modalOverlay)

// Open modal
if (editButton) {
  editButton.addEventListener("click", () => {
    // Load current user data
    fetch("updateProfile.php?action=getUserData")
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          showNotification(data.error, "error")
          return
        }

        // Fill form with current data
        const form = document.getElementById("editProfileForm")
        form.querySelector('[name="nomeUsuario"]').value = data.nomeUsuario || ""
        form.querySelector('[name="sobrenomeUsuario"]').value = data.sobrenomeUsuario || ""
        form.querySelector('[name="emailUsuario"]').value = data.emailUsuario || ""
        form.querySelector('[name="celularUsuario"]').value = data.celularUsuario || ""
        form.querySelector('[name="nascimentoUsuario"]').value = data.nascimentoUsuario || ""

        modalOverlay.classList.add("active")
      })
      .catch((error) => {
        console.error("Erro ao carregar dados:", error)
        showNotification("Erro ao carregar dados do perfil", "error")
      })
  })
}

// Close modal
const closeButton = modalOverlay.querySelector(".modal-close")
const cancelButton = modalOverlay.querySelector(".btn-cancel")

closeButton.addEventListener("click", () => {
  modalOverlay.classList.remove("active")
})

cancelButton.addEventListener("click", () => {
  modalOverlay.classList.remove("active")
})

modalOverlay.addEventListener("click", (e) => {
  if (e.target === modalOverlay) {
    modalOverlay.classList.remove("active")
  }
})

// Handle form submission
const editForm = document.getElementById("editProfileForm")
editForm.addEventListener("submit", (e) => {
  e.preventDefault()

  const formData = new FormData(editForm)
  formData.append("action", "updateProfile")

  const saveButton = editForm.querySelector(".btn-save")
  saveButton.disabled = true
  saveButton.textContent = "Salvando..."

  fetch("updateProfile.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        showNotification(data.error, "error")
        saveButton.disabled = false
        saveButton.textContent = "Salvar Alterações"
        return
      }

      if (data.success) {
        showNotification(data.message, "success")
        modalOverlay.classList.remove("active")
        location.reload()
      }
    })
    .catch((error) => {
      console.error("Erro ao atualizar perfil:", error)
      showNotification("Erro ao atualizar perfil", "error")
      saveButton.disabled = false
      saveButton.textContent = "Salvar Alterações"
    })
})

const addressModalOverlay = document.createElement("div")
addressModalOverlay.className = "modal-overlay"
addressModalOverlay.id = "addressModal"

addressModalOverlay.innerHTML = `
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title" id="addressModalTitle">Adicionar Endereço</h2>
      <button class="modal-close" id="closeAddressModal">&times;</button>
    </div>
    <form id="addressForm">
      <input type="hidden" name="idEndereco" id="idEndereco">
      <div class="form-group">
        <label class="form-label">CEP *</label>
        <div style="display: flex; gap: 10px;">
          <input type="text" name="cep" id="cep" class="form-input" required maxlength="9" placeholder="00000-000">
          <button type="button" id="searchCEP" class="btn-save" style="padding: 10px 15px;">Buscar</button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Rua *</label>
        <input type="text" name="rua" id="rua" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Número</label>
        <input type="text" name="numero" id="numero" class="form-input">
      </div>
      <div class="form-group">
        <label class="form-label">Complemento</label>
        <input type="text" name="complemento" id="complemento" class="form-input">
      </div>
      <div class="form-group">
        <label class="form-label">Bairro *</label>
        <input type="text" name="bairro" id="bairro" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Cidade *</label>
        <input type="text" name="cidade" id="cidade" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Estado *</label>
        <input type="text" name="estado" id="estado" class="form-input" required maxlength="2" placeholder="SP">
      </div>
      <div class="form-group">
        <label class="form-label">Tipo de Endereço</label>
        <select name="tipoEndereco" id="tipoEndereco" class="form-input">
          <option value="entrega">Entrega</option>
          <option value="cobranca">Cobrança</option>
          <option value="outro">Outro</option>
        </select>
      </div>
      <div class="form-actions">
        <button type="button" class="btn-cancel" id="cancelAddress">Cancelar</button>
        <button type="submit" class="btn-save" id="saveAddress">Salvar Endereço</button>
      </div>
    </form>
  </div>
`

document.body.appendChild(addressModalOverlay)

function loadAddresses() {
  fetch("loadAddressesAPI.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        showNotification(data.error, "error")
        return
      }

      const container = document.getElementById("addressesContainer")
      if (data.addresses && data.addresses.length > 0) {
        container.innerHTML = data.addresses
          .map(
            (addr) => `
          <div class="address-card">
            <div class="address-title">${getTipoLabel(addr.tipoEndereco)}</div>
            <p style="margin: 10px 0; font-size: 14px; color: #1e293b;">
              ${addr.rua}${addr.numero ? ", " + addr.numero : ""}
              ${addr.complemento ? " - " + addr.complemento : ""}<br>
              ${addr.bairro}<br>
              ${addr.cidade} - ${addr.estado}<br>
              CEP: ${addr.cep}
            </p>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
              <button class="btn-cancel" style="flex: 1; padding: 8px;" onclick="editAddress(${addr.idEndereco})">Editar</button>
              <button class="btn-cancel" style="flex: 1; padding: 8px; border-color: #ef4444; color: #ef4444;" onclick="deleteAddress(${addr.idEndereco})">Excluir</button>
            </div>
          </div>
        `,
          )
          .join("")
      } else {
        container.innerHTML = `
          <div class="address-card">
            <div class="address-title">Nenhum endereço cadastrado</div>
            <p style="color:#666;font-size:14px;margin-top:10px;">Clique em "+ Adicionar" para cadastrar um endereço</p>
          </div>
        `
      }
    })
    .catch((error) => {
      showNotification("Erro ao carregar dados do perfil", "error")
    })
}

function getTipoLabel(tipo) {
  const labels = {
    entrega: "Endereço de Entrega",
    cobranca: "Endereço de Cobrança",
    outro: "Outro Endereço",
  }
  return labels[tipo] || "Endereço"
}

// Add address button
document.getElementById("addAddressBtn").addEventListener("click", () => {
  document.getElementById("addressModalTitle").textContent = "Adicionar Endereço"
  document.getElementById("addressForm").reset()
  document.getElementById("idEndereco").value = ""
  addressModalOverlay.classList.add("active")
})

// Close address modal
document.getElementById("closeAddressModal").addEventListener("click", () => {
  addressModalOverlay.classList.remove("active")
})

document.getElementById("cancelAddress").addEventListener("click", () => {
  addressModalOverlay.classList.remove("active")
})

// Search CEP
document.getElementById("searchCEP").addEventListener("click", () => {
  const cep = document.getElementById("cep").value.replace(/\D/g, "")

  if (cep.length !== 8) {
    showNotification("Digite um CEP válido com 8 dígitos", "error")
    return
  }

  const btn = document.getElementById("searchCEP")
  btn.textContent = "Buscando..."
  btn.disabled = true

  fetch(`addressAPI.php?action=searchCEP&cep=${cep}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        showNotification(data.error, "error")
      } else {
        document.getElementById("rua").value = data.rua
        document.getElementById("bairro").value = data.bairro
        document.getElementById("cidade").value = data.cidade
        document.getElementById("estado").value = data.estado
      }
      btn.textContent = "Buscar"
      btn.disabled = false
    })
    .catch((error) => {
      console.error("Erro ao buscar CEP:", error)
      showNotification("Erro ao buscar CEP", "error")
      btn.textContent = "Buscar"
      btn.disabled = false
    })
})

// Format CEP input
document.getElementById("cep").addEventListener("input", (e) => {
  let value = e.target.value.replace(/\D/g, "")
  if (value.length > 5) {
    value = value.replace(/^(\d{5})(\d)/, "$1-$2")
  }
  e.target.value = value
})

// Submit address form
document.getElementById("addressForm").addEventListener("submit", (e) => {
  e.preventDefault()

  const formData = new FormData(e.target)
  const idEndereco = document.getElementById("idEndereco").value

  if (idEndereco) {
    formData.append("action", "updateAddress")
  } else {
    formData.append("action", "addAddress")
  }

  const saveBtn = document.getElementById("saveAddress")
  saveBtn.disabled = true
  saveBtn.textContent = "Salvando..."

  fetch("addressAPI.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        showNotification(data.error, "error")
        saveBtn.disabled = false
        saveBtn.textContent = "Salvar Endereço"
        return
      }

      if (data.success) {
        showNotification(data.message, "success")
        addressModalOverlay.classList.remove("active")
        loadAddresses()
      }
    })
    .catch((error) => {
      console.error("Erro ao salvar endereço:", error)
      showNotification("Erro ao salvar endereço", "error")
      saveBtn.disabled = false
      saveBtn.textContent = "Salvar Endereço"
    })
})

// Edit address
window.editAddress = (idEndereco) => {
  fetch(`addressAPI.php?action=getAddresses`)
    .then((response) => response.json())
    .then((data) => {
      const address = data.addresses.find((a) => a.idEndereco == idEndereco)

      if (address) {
        document.getElementById("addressModalTitle").textContent = "Editar Endereço"
        document.getElementById("idEndereco").value = address.idEndereco
        document.getElementById("cep").value = address.cep
        document.getElementById("rua").value = address.rua
        document.getElementById("numero").value = address.numero
        document.getElementById("complemento").value = address.complemento
        document.getElementById("bairro").value = address.bairro
        document.getElementById("cidade").value = address.cidade
        document.getElementById("estado").value = address.estado
        document.getElementById("tipoEndereco").value = address.tipoEndereco

        addressModalOverlay.classList.add("active")
      }
    })
}

// Delete address
window.deleteAddress = (idEndereco) => {
  if (!confirm("Tem certeza que deseja excluir este endereço?")) {
    return
  }

  const formData = new FormData()
  formData.append("action", "deleteAddress")
  formData.append("idEndereco", idEndereco)

  fetch("addressAPI.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        showNotification(data.error, "error")
        return
      }

      if (data.success) {
        showNotification(data.message, "success")
        loadAddresses()
      }
    })
    .catch((error) => {
      console.error("Erro ao excluir endereço:", error)
      showNotification("Erro ao excluir endereço", "error")
    })
}

function loadOrders() {
  fetch("ordersAPI.php?action=getOrders")
    .then((response) => response.json())
    .then((data) => {
      const ordersSection = document.querySelector(".orders-section")
      const sectionTitle = ordersSection.querySelector(".section-title")

      if (data.orders && data.orders.length > 0) {
        ordersSection.innerHTML = `
          <div class="section-title">Últimos Pedidos</div>
          ${data.orders
            .map(
              (order) => `
            <div class="order-card" style="cursor: pointer;" onclick="window.location.href='detalhes-pedido.php?id=${order.idPedido}'">
              <div class="order-header">
                <div class="order-field">
                  <div class="order-label">Pedido</div>
                  <div class="order-value">#${order.idPedido}</div>
                </div>
                <div class="order-field">
                  <div class="order-label">Data</div>
                  <div class="order-value">${formatDate(order.dataPedido)}</div>
                </div>
                <div class="order-field">
                  <div class="order-label">Total</div>
                  <div class="order-value">R$ ${formatPrice(order.total)}</div>
                </div>
                <div class="order-field">
                  <div class="order-label">Pagamento</div>
                  <div class="order-value">${getPaymentLabel(order.metodoPagamento)}</div>
                </div>
              </div>
              <div class="order-footer">
                <div class="order-badges">
                  <span class="badge ${getStatusClass(order.status)}">${getStatusLabel(order.status)}</span>
                </div>
                ${order.cidade ? `<div class="order-location"><span class="location-icon">📍</span>${order.cidade} - ${order.estado}</div>` : ""}
              </div>
            </div>
          `,
            )
            .join("")}
        `
      } else {
        ordersSection.innerHTML = `
          <div class="section-title">Últimos Pedidos</div>
          <div class="order-card">
            <p style="text-align:center;padding:20px;color:#666;">Você ainda não fez nenhum pedido.</p>
          </div>
        `
      }

      loadBuilds()
    })
    .catch((error) => {
      showNotification("Erro ao carregar pedidos", "error")
    })
}

function loadBuilds() {
  fetch("ordersAPI.php?action=getBuilds")
    .then((response) => response.json())
    .then((data) => {
      if (data.builds && data.builds.length > 0) {
        const ordersSection = document.querySelector(".orders-section")

        const buildsHTML = `
          <div class="section-title" style="margin-top: 30px;">Minhas Montagens de PC</div>
          ${data.builds
            .map(
              (build) => `
            <div class="order-card">
              <div class="order-header">
                <div class="order-field">
                  <div class="order-label">Nome do Setup</div>
                  <div class="order-value">${build.nomeSetup}</div>
                </div>
                <div class="order-field">
                  <div class="order-label">Data</div>
                  <div class="order-value">${formatDate(build.dataSolicitacao)}</div>
                </div>
                <div class="order-field">
                  <div class="order-label">Preço Estimado</div>
                  <div class="order-value">R$ ${formatPrice(build.precoEstimado)}</div>
                </div>
                <div class="order-field">
                  <div class="order-label">Status</div>
                  <div class="order-value">${getBuildStatusLabel(build.status)}</div>
                </div>
              </div>
              <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 13px;">
                  ${build.cpu ? `<div><strong>CPU:</strong> ${build.cpu}</div>` : ""}
                  ${build.gpu ? `<div><strong>GPU:</strong> ${build.gpu}</div>` : ""}
                  ${build.placaMae ? `<div><strong>Placa-Mãe:</strong> ${build.placaMae}</div>` : ""}
                  ${build.ram ? `<div><strong>RAM:</strong> ${build.ram}</div>` : ""}
                  ${build.armazenamento ? `<div><strong>Armazenamento:</strong> ${build.armazenamento}</div>` : ""}
                  ${build.fonte ? `<div><strong>Fonte:</strong> ${build.fonte}</div>` : ""}
                </div>
                ${build.observacoes ? `<div style="margin-top: 10px; font-size: 13px; color: #64748b;"><strong>Observações:</strong> ${build.observacoes}</div>` : ""}
              </div>
            </div>
          `,
            )
            .join("")}
        `

        ordersSection.insertAdjacentHTML("beforeend", buildsHTML)
      }
    })
    .catch((error) => {
      showNotification("Erro ao carregar montagens", "error")
    })
}

function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString("pt-BR")
}

function formatPrice(price) {
  return Number.parseFloat(price).toFixed(2).replace(".", ",")
}

function getStatusLabel(status) {
  const labels = {
    pendente: "Pendente",
    pago: "Pago",
    enviado: "Enviado",
    concluido: "Concluído",
    cancelado: "Cancelado",
  }
  return labels[status] || status
}

function getStatusClass(status) {
  const classes = {
    pendente: "badge-warning",
    pago: "badge-success",
    enviado: "badge-success",
    concluido: "badge-success",
    cancelado: "badge-warning",
  }
  return classes[status] || "badge-warning"
}

function getPaymentLabel(method) {
  const labels = {
    cartao: "Cartão",
    pix: "PIX",
    boleto: "Boleto",
  }
  return labels[method] || method
}

function getBuildStatusLabel(status) {
  const labels = {
    "em análise": "Em Análise",
    "em montagem": "Em Montagem",
    finalizado: "Finalizado",
  }
  return labels[status] || status
}

const profilePhotoCircle = document.getElementById("profilePhotoCircle")
const photoInput = document.getElementById("photoInput")

if (profilePhotoCircle && photoInput) {
  profilePhotoCircle.addEventListener("click", () => {
    photoInput.click()
  })

  photoInput.addEventListener("change", (e) => {
    const file = e.target.files[0]
    if (!file) return

    // Validate file type
    if (!file.type.startsWith("image/")) {
      showNotification("Por favor, selecione uma imagem válida", "error")
      return
    }

    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
      showNotification("A imagem deve ter no máximo 5MB", "error")
      return
    }

    // Upload photo
    const formData = new FormData()
    formData.append("photo", file)

    // Show loading state
    profilePhotoCircle.style.opacity = "0.5"
    profilePhotoCircle.style.pointerEvents = "none"

    fetch("uploadPhoto.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          showNotification(data.error, "error")
          return
        }

        if (data.success) {
          showNotification(data.message, "success")
          // Reload page to show new photo
          location.reload()
        }
      })
      .catch((error) => {
        console.error("Erro ao fazer upload da foto:", error)
        showNotification("Erro ao fazer upload da foto", "error")
      })
      .finally(() => {
        profilePhotoCircle.style.opacity = "1"
        profilePhotoCircle.style.pointerEvents = "auto"
      })
  })
}

const paymentModalOverlay = document.createElement("div")
paymentModalOverlay.className = "modal-overlay"
paymentModalOverlay.id = "paymentModal"

paymentModalOverlay.innerHTML = `
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title" id="paymentModalTitle">Adicionar Forma de Pagamento</h2>
      <button class="modal-close" id="closePaymentModal">&times;</button>
    </div>
    <form id="paymentForm">
      <input type="hidden" name="idFormaPagamento" id="idFormaPagamento">
      <div class="form-group">
        <label class="form-label">Tipo de Pagamento *</label>
        <select name="tipoPagamento" id="tipoPagamento" class="form-input" required>
          <option value="">Selecione...</option>
          <option value="cartao_credito">Cartão de Crédito</option>
          <option value="pix">PIX</option>
          <option value="boleto">Boleto</option>
          <option value="transferencia">Transferência</option>
        </select>
      </div>
      
      <div id="cartaoFields" style="display: none;">
        <div class="form-group">
          <label class="form-label">Nome do Titular *</label>
          <input type="text" name="nomeTitular" id="nomeTitular" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Número do Cartão *</label>
          <input type="text" name="numeroCartao" id="numeroCartao" class="form-input" maxlength="19" placeholder="0000 0000 0000 0000">
        </div>
        <div class="form-group">
          <label class="form-label">Validade *</label>
          <input type="text" name="validadeCartao" id="validadeCartao" class="form-input" maxlength="5" placeholder="MM/AA">
        </div>
        <div class="form-group">
          <label class="form-label">Bandeira *</label>
          <select name="bandeiraCartao" id="bandeiraCartao" class="form-input">
            <option value="">Selecione...</option>
            <option value="Visa">Visa</option>
            <option value="Mastercard">Mastercard</option>
            <option value="Elo">Elo</option>
            <option value="American Express">American Express</option>
          </select>
        </div>
      </div>
      
      <div id="pixFields" style="display: none;">
        <div class="form-group">
          <label class="form-label">Chave PIX *</label>
          <input type="text" name="chavePix" id="chavePix" class="form-input" placeholder="E-mail, CPF, telefone ou chave aleatória">
        </div>
        <div class="form-group">
          <label class="form-label">CPF do Titular</label>
          <input type="text" name="cpfTitular" id="cpfTitular" class="form-input" maxlength="14" placeholder="000.000.000-00">
        </div>
      </div>
      
      <div class="form-actions">
        <button type="button" class="btn-cancel" id="cancelPayment">Cancelar</button>
        <button type="submit" class="btn-save" id="savePayment">Salvar</button>
      </div>
    </form>
  </div>
`

document.body.appendChild(paymentModalOverlay)

// Show/hide fields based on payment type
document.getElementById("tipoPagamento").addEventListener("change", (e) => {
  const cartaoFields = document.getElementById("cartaoFields")
  const pixFields = document.getElementById("pixFields")

  cartaoFields.style.display = "none"
  pixFields.style.display = "none"

  if (e.target.value === "cartao_credito") {
    cartaoFields.style.display = "block"
  } else if (e.target.value === "pix") {
    pixFields.style.display = "block"
  }
})

// Format card number
document.getElementById("numeroCartao").addEventListener("input", (e) => {
  let value = e.target.value.replace(/\D/g, "")
  value = value.replace(/(\d{4})(?=\d)/g, "$1 ")
  e.target.value = value
})

// Format card expiry
document.getElementById("validadeCartao").addEventListener("input", (e) => {
  let value = e.target.value.replace(/\D/g, "")
  if (value.length >= 2) {
    value = value.substring(0, 2) + "/" + value.substring(2, 4)
  }
  e.target.value = value
})

// Format CPF
document.getElementById("cpfTitular").addEventListener("input", (e) => {
  let value = e.target.value.replace(/\D/g, "")
  value = value.replace(/(\d{3})(\d)/, "$1.$2")
  value = value.replace(/(\d{3})(\d)/, "$1.$2")
  value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
  e.target.value = value
})

// Add payment method button
document.getElementById("addPaymentBtn").addEventListener("click", () => {
  document.getElementById("paymentModalTitle").textContent = "Adicionar Forma de Pagamento"
  document.getElementById("paymentForm").reset()
  document.getElementById("idFormaPagamento").value = ""
  document.getElementById("cartaoFields").style.display = "none"
  document.getElementById("pixFields").style.display = "none"
  paymentModalOverlay.classList.add("active")
})

// Close payment modal
document.getElementById("closePaymentModal").addEventListener("click", () => {
  paymentModalOverlay.classList.remove("active")
})

document.getElementById("cancelPayment").addEventListener("click", () => {
  paymentModalOverlay.classList.remove("active")
})

// Submit payment form
document.getElementById("paymentForm").addEventListener("submit", (e) => {
  e.preventDefault()

  const formData = new FormData(e.target)
  const idFormaPagamento = document.getElementById("idFormaPagamento").value

  if (idFormaPagamento) {
    formData.append("action", "updatePaymentMethod")
  } else {
    formData.append("action", "addPaymentMethod")
  }

  const saveBtn = document.getElementById("savePayment")
  saveBtn.disabled = true
  saveBtn.textContent = "Salvando..."

  fetch("paymentAPI.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      saveBtn.disabled = false
      saveBtn.textContent = "Salvar"

      if (data.error) {
        showNotification(data.error, "error")
        return
      }

      if (data.success) {
        showNotification(data.message, "success")
        paymentModalOverlay.classList.remove("active")
        loadPaymentMethods()
      }
    })
    .catch((error) => {
      console.error("Erro ao salvar forma de pagamento:", error)
      showNotification("Erro ao salvar forma de pagamento", "error")
      saveBtn.disabled = false
      saveBtn.textContent = "Salvar"
    })
})

function loadPaymentMethods() {
  fetch("paymentAPI.php?action=getPaymentMethods")
    .then((response) => response.json())
    .then((data) => {
      const container = document.getElementById("paymentMethodsContainer")

      if (data.paymentMethods && data.paymentMethods.length > 0) {
        container.innerHTML = data.paymentMethods
          .map((payment) => {
            let icon = "💳"
            let info = ""

            if (payment.tipoPagamento === "cartao_credito") {
              icon = "💳"
              info = `
              <div class="payment-info"><strong>${payment.bandeiraCartao}</strong></div>
              <div class="payment-info">${payment.numeroCartao}</div>
              <div class="payment-info">Validade: ${payment.validadeCartao}</div>
              <div class="payment-info">${payment.nomeTitular}</div>
            `
            } else if (payment.tipoPagamento === "pix") {
              icon = "🔑"
              info = `
              <div class="payment-info"><strong>Chave PIX</strong></div>
              <div class="payment-info">${payment.chavePix}</div>
            `
            } else if (payment.tipoPagamento === "boleto") {
              icon = "📄"
              info = `<div class="payment-info"><strong>Boleto Bancário</strong></div>`
            } else if (payment.tipoPagamento === "transferencia") {
              icon = "🏦"
              info = `<div class="payment-info"><strong>Transferência Bancária</strong></div>`
            }

            return `
            <div class="payment-card">
              <div class="payment-type-icon">${icon}</div>
              <div class="payment-title">${getPaymentTypeLabel(payment.tipoPagamento)}</div>
              ${info}
              <div class="payment-actions">
                <button class="btn-cancel" style="flex: 1; padding: 8px; border-color: #ef4444; color: #ef4444;" onclick="deletePaymentMethod(${payment.idFormaPagamento})">Excluir</button>
              </div>
            </div>
          `
          })
          .join("")
      } else {
        container.innerHTML = `
          <div class="payment-card">
            <div class="payment-title">Nenhuma forma de pagamento cadastrada</div>
            <p style="color:#666;font-size:14px;margin-top:10px;">Clique em "+ Adicionar" para cadastrar</p>
          </div>
        `
      }
    })
    .catch((error) => {
      showNotification("Erro ao carregar formas de pagamento", "error")
    })
}

function getPaymentTypeLabel(tipo) {
  const labels = {
    cartao_credito: "Cartão de Crédito",
    pix: "PIX",
    boleto: "Boleto",
    transferencia: "Transferência",
  }
  return labels[tipo] || tipo
}

window.deletePaymentMethod = (idFormaPagamento) => {
  if (!confirm("Tem certeza que deseja excluir esta forma de pagamento?")) {
    return
  }

  const formData = new FormData()
  formData.append("action", "deletePaymentMethod")
  formData.append("idFormaPagamento", idFormaPagamento)

  fetch("paymentAPI.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        showNotification(data.error, "error")
        return
      }

      if (data.success) {
        showNotification(data.message, "success")
        loadPaymentMethods()
      }
    })
    .catch((error) => {
      console.error("Erro ao excluir forma de pagamento:", error)
      showNotification("Erro ao excluir forma de pagamento", "error")
    })
}

// Load payment methods on page load
loadPaymentMethods()

loadAddresses()
loadOrders()

// Function to show notifications
function showNotification(message, type) {
  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.textContent = message
  document.body.appendChild(notification)

  setTimeout(() => {
    document.body.removeChild(notification)
  }, 3000)
}
