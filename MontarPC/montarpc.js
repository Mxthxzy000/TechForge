document.addEventListener("DOMContentLoaded", () => {
  // Save setup name and observations to session
  const nomeSetupInput = document.getElementById("nomeSetup")
  const observacoesInput = document.getElementById("observacoes")

  if (nomeSetupInput) {
    nomeSetupInput.addEventListener("blur", () => {
      fetch("save-build-info.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          nomeSetup: nomeSetupInput.value,
          observacoes: observacoesInput.value,
        }),
      })
    })
  }

  if (observacoesInput) {
    observacoesInput.addEventListener("blur", () => {
      fetch("save-build-info.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          nomeSetup: nomeSetupInput.value,
          observacoes: observacoesInput.value,
        }),
      })
    })
  }

  // Clear build button
  const clearBtn = document.getElementById("clearBuildBtn")
  if (clearBtn) {
    clearBtn.addEventListener("click", () => {
      if (confirm("Tem certeza que deseja limpar toda a montagem?")) {
        fetch("clear-build.php", {
          method: "POST",
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              window.location.reload()
            }
          })
      }
    })
  }

  // Save build button
  const saveBtn = document.getElementById("saveBuildBtn")
  if (saveBtn) {
    saveBtn.addEventListener("click", () => {
      const nomeSetup = nomeSetupInput.value.trim()

      if (!nomeSetup) {
        alert("Por favor, dê um nome para sua montagem")
        nomeSetupInput.focus()
        return
      }

      saveBtn.disabled = true
      saveBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Salvando...'

      const formData = new FormData()
      formData.append("nomeSetup", nomeSetup)
      formData.append("observacoes", observacoesInput.value)

      fetch("montarpcAPI.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.needsLogin) {
            alert("Você precisa fazer login para salvar sua montagem")
            window.location.href = "../Login/login.php"
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
            // Clear build after saving
            fetch("clear-build.php", { method: "POST" }).then(() => {
              window.location.href = "../Perfil/perfil.php"
            })
          }
        })
        .catch((error) => {
          console.error("Erro ao salvar montagem:", error)
          alert("Erro ao salvar montagem")
          saveBtn.disabled = false
          saveBtn.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Salvar Montagem'
        })
    })
  }
})
