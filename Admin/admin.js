// Import Swal from SweetAlert2 library
const Swal = window.Swal

// Produtos
function openProductModal() {
  document.getElementById("productModal").classList.add("active")
  document.getElementById("modalTitle").textContent = "Novo Produto"
  document.getElementById("productForm").reset()
  document.getElementById("productId").value = ""
}

function closeProductModal() {
  document.getElementById("productModal").classList.remove("active")
}

function editProduct(product) {
  document.getElementById("productModal").classList.add("active")
  document.getElementById("modalTitle").textContent = "Editar Produto"
  document.getElementById("productId").value = product.idProduto
  document.getElementById("nomeProduto").value = product.nomeProduto
  document.getElementById("precoProduto").value = product.valorProduto
  document.getElementById("estoqueProduto").value = product.quantidadeProduto
  document.getElementById("categoriaProduto").value = product.tipoProduto
  document.getElementById("descricaoProduto").value = product.descricaoProduto || ""
  document.getElementById("linhaProduto").value = product.linhaProduto || ""
  document.getElementById("tagsProduto").value = product.tagsProduto || ""

  // Set image URL
  document.getElementById("imagemProdutoUrl").value = product.imagem
  document.getElementById("imagemProduto").value = product.imagem

  // Show URL input by default
  toggleImageInput("url")
}

// Form submit para produtos
if (document.getElementById("productForm")) {
  document.getElementById("productForm").addEventListener("submit", async (e) => {
    e.preventDefault()

    const formData = new FormData(e.target)
    const productId = formData.get("idProduto")

    // Handle image upload if file is selected
    const imageFile = document.getElementById("imagemProdutoFile").files[0]
    const imageUrl = document.getElementById("imagemProdutoUrl").value

    if (imageFile) {
      // Upload image first
      const uploadFormData = new FormData()
      uploadFormData.append("image", imageFile)

      try {
        const uploadResponse = await fetch("uploadProductImage.php", {
          method: "POST",
          body: uploadFormData,
        })

        const uploadResult = await uploadResponse.json()

        if (uploadResult.success) {
          formData.set("imagemProduto", uploadResult.imagePath)
        } else {
          Swal.fire("Erro!", uploadResult.error, "error")
          return
        }
      } catch (error) {
        Swal.fire("Erro!", "Erro ao fazer upload da imagem", "error")
        return
      }
    } else if (imageUrl) {
      formData.set("imagemProduto", imageUrl)
    }

    formData.append("action", productId ? "updateProduct" : "addProduct")

    try {
      const response = await fetch("adminAPI.php", {
        method: "POST",
        body: formData,
      })

      const result = await response.json()

      if (result.success) {
        Swal.fire("Sucesso!", result.message, "success").then(() => {
          location.reload()
        })
      } else {
        Swal.fire("Erro!", result.message, "error")
      }
    } catch (error) {
      Swal.fire("Erro!", "Erro ao processar requisição", "error")
    }
  })
}

function toggleImageInput(type) {
  const urlInput = document.getElementById("urlImageInput")
  const fileInput = document.getElementById("fileImageInput")
  const btnUrl = document.getElementById("btnUrlInput")
  const btnFile = document.getElementById("btnFileInput")

  if (type === "url") {
    urlInput.style.display = "block"
    fileInput.style.display = "none"
    btnUrl.classList.add("active")
    btnFile.classList.remove("active")
  } else {
    urlInput.style.display = "none"
    fileInput.style.display = "block"
    btnUrl.classList.remove("active")
    btnFile.classList.add("active")
  }
}

async function deleteProduct(id) {
  const result = await Swal.fire({
    title: "Tem certeza?",
    text: "Esta ação não pode ser desfeita!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
  })

  if (result.isConfirmed) {
    const formData = new FormData()
    formData.append("action", "deleteProduct")
    formData.append("idProduto", id)

    try {
      const response = await fetch("adminAPI.php", {
        method: "POST",
        body: formData,
      })

      const data = await response.json()

      if (data.success) {
        Swal.fire("Excluído!", data.message, "success").then(() => {
          location.reload()
        })
      } else {
        Swal.fire("Erro!", data.message, "error")
      }
    } catch (error) {
      Swal.fire("Erro!", "Erro ao processar requisição", "error")
    }
  }
}

// Pedidos
async function updateOrderStatus(idPedido, status) {
  const formData = new FormData()
  formData.append("action", "updateOrderStatus")
  formData.append("idPedido", idPedido)
  formData.append("status", status)

  try {
    const response = await fetch("adminAPI.php", {
      method: "POST",
      body: formData,
    })

    const result = await response.json()

    if (result.success) {
      Swal.fire("Sucesso!", result.message, "success")
    } else {
      Swal.fire("Erro!", result.message, "error")
    }
  } catch (error) {
    Swal.fire("Erro!", "Erro ao atualizar status", "error")
  }
}

async function viewOrderDetails(idPedido) {
  try {
    const response = await fetch(`adminAPI.php?action=getOrderDetails&idPedido=${idPedido}`)
    const result = await response.json()

    if (result.success) {
      const { pedido, itens } = result

      let html = `
                <div style="text-align: left;">
                    <h3>Informações do Cliente</h3>
                    <p><strong>Nome:</strong> ${pedido.nomeUsuario}</p>
                    <p><strong>E-mail:</strong> ${pedido.emailUsuario}</p>
                    <p><strong>Telefone:</strong> ${pedido.telefoneUsuario || "N/A"}</p>
                    
                    <h3 style="margin-top: 20px;">Endereço de Entrega</h3>
                    <p>${pedido.ruaEndereco}, ${pedido.numeroEndereco}</p>
                    <p>${pedido.bairroEndereco} - ${pedido.cidadeEndereco}/${pedido.estadoEndereco}</p>
                    <p>CEP: ${pedido.cepEndereco}</p>
                    
                    <h3 style="margin-top: 20px;">Itens do Pedido</h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 8px; text-align: left;">Produto</th>
                                <th style="padding: 8px; text-align: center;">Qtd</th>
                                <th style="padding: 8px; text-align: right;">Preço</th>
                            </tr>
                        </thead>
                        <tbody>
            `

      itens.forEach((item) => {
        html += `
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 8px;">${item.nomeProduto}</td>
                        <td style="padding: 8px; text-align: center;">${item.quantidade}</td>
                        <td style="padding: 8px; text-align: right;">R$ ${Number.parseFloat(item.precoUnitario).toFixed(2)}</td>
                    </tr>
                `
      })

      html += `
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 20px; text-align: right;">
                        <h3>Total: R$ ${Number.parseFloat(pedido.valorTotal).toFixed(2)}</h3>
                    </div>
                </div>
            `

      document.getElementById("orderDetails").innerHTML = html
      document.getElementById("orderModal").classList.add("active")
    }
  } catch (error) {
    Swal.fire("Erro!", "Erro ao carregar detalhes", "error")
  }
}

function closeOrderModal() {
  document.getElementById("orderModal").classList.remove("active")
}

// Usuários
async function deleteUser(id) {
  const result = await Swal.fire({
    title: "Tem certeza?",
    text: "Esta ação não pode ser desfeita!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
  })

  if (result.isConfirmed) {
    const formData = new FormData()
    formData.append("action", "deleteUser")
    formData.append("idUsuario", id)

    try {
      const response = await fetch("adminAPI.php", {
        method: "POST",
        body: formData,
      })

      const data = await response.json()

      if (data.success) {
        Swal.fire("Excluído!", data.message, "success").then(() => {
          location.reload()
        })
      } else {
        Swal.fire("Erro!", data.message, "error")
      }
    } catch (error) {
      Swal.fire("Erro!", "Erro ao processar requisição", "error")
    }
  }
}

async function viewUserDetails(idUsuario) {
  try {
    const response = await fetch(`adminAPI.php?action=getUserDetails&idUsuario=${idUsuario}`)
    const result = await response.json()

    if (result.success) {
      const user = result.usuario
      Swal.fire({
        title: "Detalhes do Usuário",
        html: `
                    <div style="text-align: left;">
                        <p><strong>Nome:</strong> ${user.nomeUsuario} ${user.sobrenomeUsuario || ""}</p>
                        <p><strong>E-mail:</strong> ${user.emailUsuario}</p>
                        <p><strong>CPF:</strong> ${user.cpfUsuario || "N/A"}</p>
                        <p><strong>Telefone:</strong> ${user.celularUsuario || "N/A"}</p>
                        <p><strong>Data de Cadastro:</strong> ${new Date(user.dataCadastro).toLocaleDateString("pt-BR")}</p>
                        <p><strong>Total de Pedidos:</strong> ${user.totalPedidos || 0}</p>
                        <p><strong>Total Gasto:</strong> R$ ${Number.parseFloat(user.totalGasto || 0).toFixed(2)}</p>
                    </div>
                `,
        width: 600,
      })
    }
  } catch (error) {
    Swal.fire("Erro!", "Erro ao carregar detalhes", "error")
  }
}

// Montagens
async function deleteBuild(id) {
  const result = await Swal.fire({
    title: "Tem certeza?",
    text: "Esta ação não pode ser desfeita!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
  })

  if (result.isConfirmed) {
    const formData = new FormData()
    formData.append("action", "deleteBuild")
    formData.append("idServico", id)

    try {
      const response = await fetch("adminAPI.php", {
        method: "POST",
        body: formData,
      })

      const data = await response.json()

      if (data.success) {
        Swal.fire("Excluído!", data.message, "success").then(() => {
          location.reload()
        })
      } else {
        Swal.fire("Erro!", data.message, "error")
      }
    } catch (error) {
      Swal.fire("Erro!", "Erro ao processar requisição", "error")
    }
  }
}

async function viewBuildDetails(idServico) {
  try {
    const response = await fetch(`adminAPI.php?action=getBuildDetails&idServico=${idServico}`)
    const result = await response.json()

    if (result.success) {
      const build = result.build

      const html = `
                <div style="text-align: left;">
                    <h3>Informações do Cliente</h3>
                    <p><strong>Nome:</strong> ${build.nomeUsuario}</p>
                    <p><strong>E-mail:</strong> ${build.emailUsuario}</p>
                    <p><strong>Telefone:</strong> ${build.telefoneUsuario || "N/A"}</p>
                    
                    <h3 style="margin-top: 20px;">Detalhes do Build</h3>
                    <p><strong>Nome:</strong> ${build.nomeBuild}</p>
                    <p><strong>Data:</strong> ${new Date(build.dataSolicitacao).toLocaleString("pt-BR")}</p>
                    <p><strong>Observações:</strong> ${build.observacoes || "Nenhuma"}</p>
                    
                    <h3 style="margin-top: 20px;">Componentes</h3>
                    <p><strong>Processador:</strong> ${build.processador || "Não selecionado"}</p>
                    <p><strong>Placa de Vídeo:</strong> ${build.placaVideo || "Não selecionado"}</p>
                    <p><strong>Placa-Mãe:</strong> ${build.placaMae || "Não selecionado"}</p>
                    <p><strong>Memória RAM:</strong> ${build.memoriaRam || "Não selecionado"}</p>
                    <p><strong>Armazenamento:</strong> ${build.armazenamento || "Não selecionado"}</p>
                    <p><strong>Fonte:</strong> ${build.fonte || "Não selecionado"}</p>
                    <p><strong>Gabinete:</strong> ${build.gabinete || "Não selecionado"}</p>
                    <p><strong>Cooler:</strong> ${build.cooler || "Não selecionado"}</p>
                    
                    <div style="margin-top: 20px; text-align: right;">
                        <h3>Valor Total: R$ ${Number.parseFloat(build.valorTotal).toFixed(2)}</h3>
                    </div>
                </div>
            `

      document.getElementById("buildDetails").innerHTML = html
      document.getElementById("buildModal").classList.add("active")
    }
  } catch (error) {
    Swal.fire("Erro!", "Erro ao carregar detalhes", "error")
  }
}

function closeBuildModal() {
  document.getElementById("buildModal").classList.remove("active")
}

// Contatos
async function deleteMessage(id) {
  const result = await Swal.fire({
    title: "Tem certeza?",
    text: "Esta ação não pode ser desfeita!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
  })

  if (result.isConfirmed) {
    const formData = new FormData()
    formData.append("action", "deleteMessage")
    formData.append("idContato", id)

    try {
      const response = await fetch("adminAPI.php", {
        method: "POST",
        body: formData,
      })

      const data = await response.json()

      if (data.success) {
        Swal.fire("Excluído!", data.message, "success").then(() => {
          location.reload()
        })
      } else {
        Swal.fire("Erro!", data.message, "error")
      }
    } catch (error) {
      Swal.fire("Erro!", "Erro ao processar requisição", "error")
    }
  }
}

function viewMessage(contato) {
  const html = `
        <div style="text-align: left;">
            <p><strong>Nome:</strong> ${contato.nomeContato}</p>
            <p><strong>E-mail:</strong> ${contato.emailContato}</p>
            <p><strong>Assunto:</strong> ${contato.assuntoContato}</p>
            <p><strong>Data:</strong> ${new Date(contato.dataContato).toLocaleString("pt-BR")}</p>
            <hr style="margin: 20px 0;">
            <p><strong>Mensagem:</strong></p>
            <p style="white-space: pre-wrap;">${contato.mensagemContato}</p>
        </div>
    `

  document.getElementById("messageContent").innerHTML = html
  document.getElementById("messageModal").classList.add("active")
}

function closeMessageModal() {
  document.getElementById("messageModal").classList.remove("active")
}

// Fechar modais ao clicar fora
window.onclick = (event) => {
  if (event.target.classList.contains("modal")) {
    event.target.classList.remove("active")
  }
}
