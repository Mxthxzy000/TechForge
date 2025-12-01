// Garantir que Swal está disponível
const getSwal = () => window.Swal;

// Produtos
function openProductModal() {
  document.getElementById("productModal").classList.add("active")
  document.getElementById("modalTitle").textContent = "Novo Produto"
  document.getElementById("productForm").reset()
  document.getElementById("productId").value = ""
  toggleImageInput("url")
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
          Swal.fire({
            icon: "error",
            title: "Erro!",
            text: uploadResult.error || "Erro ao fazer upload da imagem",
            confirmButtonColor: "#2563eb"
          })
          return
        }
      } catch (error) {
        console.error("Erro no upload:", error)
        Swal.fire({
          icon: "error",
          title: "Erro!",
          text: "Erro ao fazer upload da imagem",
          confirmButtonColor: "#2563eb"
        })
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
      
      console.log("Resposta da API:", result)

      if (result.success) {
        closeProductModal()
        
        Swal.fire({
          icon: "success",
          title: "Sucesso!",
          text: result.message,
          confirmButtonColor: "#16a34a",
          timer: 2000,
          timerProgressBar: true
        }).then(() => {
          location.reload()
        })
      } else {
        Swal.fire({
          icon: "error",
          title: "Erro!",
          text: result.message || "Erro desconhecido",
          confirmButtonColor: "#dc2626"
        })
      }
    } catch (error) {
      console.error("Erro na requisição:", error)
      Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Erro ao processar requisição",
        confirmButtonColor: "#dc2626"
      })
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
  const Swal = getSwal();
  
  if (!Swal) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
      performDelete('deleteProduct', 'idProduto', id);
    }
    return;
  }

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
    performDelete('deleteProduct', 'idProduto', id);
  }
}

// Função auxiliar para realizar exclusão
async function performDelete(action, idField, id) {
  const Swal = getSwal();
  const formData = new FormData()
  formData.append("action", action)
  formData.append(idField, id)

  try {
    const response = await fetch("adminAPI.php", {
      method: "POST",
      body: formData,
    })

    const data = await response.json()

    if (data.success) {
      if (Swal) {
        Swal.fire({
          icon: "success",
          title: "Excluído!",
          text: data.message,
          confirmButtonColor: "#16a34a",
          timer: 2000,
          timerProgressBar: true
        }).then(() => {
          location.reload()
        })
      } else {
        alert(data.message);
        location.reload();
      }
    } else {
      if (Swal) {
        Swal.fire({
          icon: "error",
          title: "Erro!",
          text: data.message,
          confirmButtonColor: "#dc2626"
        })
      } else {
        alert('Erro: ' + data.message);
      }
    }
  } catch (error) {
    console.error("Erro ao deletar:", error)
    if (Swal) {
      Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Erro ao processar requisição",
        confirmButtonColor: "#dc2626"
      })
    } else {
      alert('Erro ao processar requisição');
    }
  }
}

// Pedidos
async function updateOrderStatus(idPedido, status) {
  const Swal = getSwal();
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
      if (Swal) {
        Swal.fire({
          icon: "success",
          title: "Sucesso!",
          text: result.message,
          confirmButtonColor: "#16a34a",
          timer: 1500,
          timerProgressBar: true
        })
      } else {
        alert(result.message);
      }
    } else {
      if (Swal) {
        Swal.fire({
          icon: "error",
          title: "Erro!",
          text: result.message,
          confirmButtonColor: "#dc2626"
        })
      } else {
        alert('Erro: ' + result.message);
      }
    }
  } catch (error) {
    if (Swal) {
      Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Erro ao atualizar status",
        confirmButtonColor: "#dc2626"
      })
    } else {
      alert('Erro ao atualizar status');
    }
  }
}

async function viewOrderDetails(idPedido) {
  const Swal = getSwal();
  
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
          <p><strong>Telefone:</strong> ${pedido.celularUsuario || "N/A"}</p>
          
          <h3 style="margin-top: 20px;">Endereço de Entrega</h3>
          <p>${pedido.rua}, ${pedido.numero}</p>
          <p>${pedido.bairro} - ${pedido.cidade}/${pedido.estado}</p>
          <p>CEP: ${pedido.cep}</p>
          
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
            <h3>Total: R$ ${Number.parseFloat(pedido.total).toFixed(2)}</h3>
          </div>
        </div>
      `

      document.getElementById("orderDetails").innerHTML = html
      document.getElementById("orderModal").classList.add("active")
    }
  } catch (error) {
    if (Swal) {
      Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Erro ao carregar detalhes",
        confirmButtonColor: "#dc2626"
      })
    } else {
      alert('Erro ao carregar detalhes');
    }
  }
}

function closeOrderModal() {
  document.getElementById("orderModal").classList.remove("active")
}

// Usuários
async function deleteUser(id) {
  const Swal = getSwal();
  
  if (!Swal) {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
      performDelete('deleteUser', 'idUsuario', id);
    }
    return;
  }

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
    performDelete('deleteUser', 'idUsuario', id);
  }
}

async function viewUserDetails(idUsuario) {
  const Swal = getSwal();
  
  try {
    const response = await fetch(`adminAPI.php?action=getUserDetails&idUsuario=${idUsuario}`)
    const result = await response.json()

    if (result.success) {
      const user = result.usuario
      if (Swal) {
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
          confirmButtonColor: "#2563eb"
        })
      } else {
        alert(`
Nome: ${user.nomeUsuario} ${user.sobrenomeUsuario || ""}
E-mail: ${user.emailUsuario}
CPF: ${user.cpfUsuario || "N/A"}
Telefone: ${user.celularUsuario || "N/A"}
Data de Cadastro: ${new Date(user.dataCadastro).toLocaleDateString("pt-BR")}
Total de Pedidos: ${user.totalPedidos || 0}
Total Gasto: R$ ${Number.parseFloat(user.totalGasto || 0).toFixed(2)}
        `);
      }
    }
  } catch (error) {
    if (Swal) {
      Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Erro ao carregar detalhes",
        confirmButtonColor: "#dc2626"
      })
    } else {
      alert('Erro ao carregar detalhes');
    }
  }
}

// Montagens
async function deleteBuild(id) {
  const Swal = getSwal();
  
  if (!Swal) {
    if (confirm('Tem certeza que deseja excluir esta montagem?')) {
      performDelete('deleteBuild', 'idMontagem', id);
    }
    return;
  }

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
    performDelete('deleteBuild', 'idMontagem', id);
  }
}

async function viewBuildDetails(idMontagem) {
  const Swal = getSwal();
  
  try {
    const response = await fetch(`adminAPI.php?action=getBuildDetails&idMontagem=${idMontagem}`)
    const result = await response.json()

    if (result.success) {
      const build = result.build

      const html = `
        <div style="text-align: left;">
          <h3>Informações do Cliente</h3>
          <p><strong>Nome:</strong> ${build.nomeUsuario}</p>
          <p><strong>E-mail:</strong> ${build.emailUsuario}</p>
          <p><strong>Telefone:</strong> ${build.celularUsuario || "N/A"}</p>
          
          <h3 style="margin-top: 20px;">Detalhes do Build</h3>
          <p><strong>Nome:</strong> ${build.nomeSetup}</p>
          <p><strong>Data:</strong> ${new Date(build.dataSolicitacao).toLocaleString("pt-BR")}</p>
          <p><strong>Observações:</strong> ${build.observacoes || "Nenhuma"}</p>
          
          <h3 style="margin-top: 20px;">Componentes</h3>
          <p><strong>Processador:</strong> ${build.cpu || "Não selecionado"}</p>
          <p><strong>Placa de Vídeo:</strong> ${build.gpu || "Não selecionado"}</p>
          <p><strong>Placa-Mãe:</strong> ${build.placaMae || "Não selecionado"}</p>
          <p><strong>Memória RAM:</strong> ${build.ram || "Não selecionado"}</p>
          <p><strong>Armazenamento:</strong> ${build.armazenamento || "Não selecionado"}</p>
          <p><strong>Fonte:</strong> ${build.fonte || "Não selecionado"}</p>
          <p><strong>Gabinete:</strong> ${build.gabinete || "Não selecionado"}</p>
          <p><strong>Cooler:</strong> ${build.cooler || "Não selecionado"}</p>
          
          <div style="margin-top: 20px; text-align: right;">
            <h3>Valor Total: R$ ${Number.parseFloat(build.precoEstimado).toFixed(2)}</h3>
          </div>
        </div>
      `

      document.getElementById("buildDetails").innerHTML = html
      document.getElementById("buildModal").classList.add("active")
    }
  } catch (error) {
    if (Swal) {
      Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Erro ao carregar detalhes",
        confirmButtonColor: "#dc2626"
      })
    } else {
      alert('Erro ao carregar detalhes');
    }
  }
}

function closeBuildModal() {
  document.getElementById("buildModal").classList.remove("active")
}

// Contatos
async function deleteMessage(id) {
  const Swal = getSwal();
  
  if (!Swal) {
    if (confirm('Tem certeza que deseja excluir esta mensagem?')) {
      performDelete('deleteMessage', 'id', id);
    }
    return;
  }

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
    performDelete('deleteMessage', 'id', id);
  }
}

function viewMessage(contato) {
  const html = `
    <div style="text-align: left;">
      <p><strong>Nome:</strong> ${contato.nome}</p>
      <p><strong>E-mail:</strong> ${contato.email}</p>
      <p><strong>Assunto:</strong> ${contato.assunto}</p>
      <p><strong>Data:</strong> ${new Date(contato.data_envio).toLocaleString("pt-BR")}</p>
      <hr style="margin: 20px 0;">
      <p><strong>Mensagem:</strong></p>
      <p style="white-space: pre-wrap;">${contato.mensagem}</p>
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

// Log para debug
console.log('Admin.js carregado com sucesso!')
console.log('SweetAlert2 disponível:', !!getSwal())