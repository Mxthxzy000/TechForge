/**
 * Sistema centralizado de notificações
 * Fornece uma função padrão para exibir notificações em toda a aplicação
 * Usa SweetAlert2 quando disponível, fallback para alert() nativo
 */

/**
 * Exibe uma notificação padronizada
 * @param {string} message - Mensagem a ser exibida
 * @param {string} type - Tipo de notificação: 'success', 'error', 'warning', 'info'
 * @param {number} timer - Tempo em ms antes de fechar (0 = manual)
 * @param {string} title - Título da notificação (opcional)
 */
function showNotification(message, type = "info", timer = 2000, title = "") {
  const Swal = window.Swal

  if (Swal) {
    // Usar SweetAlert2 se disponível
    const config = {
      icon: type,
      title: title || message,
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer)
        toast.addEventListener("mouseleave", Swal.resumeTimer)
      },
    }

    if (timer > 0) {
      config.timer = timer
    } else {
      config.showConfirmButton = true
    }

    Swal.fire(config)
  } else {
    // Fallback para alert() nativo
    const prefix =
      type.toUpperCase() === "SUCCESS"
        ? "✓"
        : type.toUpperCase() === "ERROR"
          ? "✕"
          : type.toUpperCase() === "WARNING"
            ? "⚠"
            : "ℹ"
    alert(`${prefix} ${title ? title + ": " : ""}${message}`)
  }
}

/**
 * Exibe notificação de sucesso
 * @param {string} message - Mensagem
 * @param {number} timer - Tempo antes de fechar
 */
function showSuccess(message, timer = 2000) {
  showNotification(message, "success", timer)
}

/**
 * Exibe notificação de erro
 * @param {string} message - Mensagem
 * @param {number} timer - Tempo antes de fechar (0 = manual)
 */
function showError(message, timer = 0) {
  showNotification(message, "error", timer)
}

/**
 * Exibe notificação de aviso
 * @param {string} message - Mensagem
 * @param {number} timer - Tempo antes de fechar
 */
function showWarning(message, timer = 2000) {
  showNotification(message, "warning", timer)
}

/**
 * Exibe notificação informativa
 * @param {string} message - Mensagem
 * @param {number} timer - Tempo antes de fechar
 */
function showInfo(message, timer = 2000) {
  showNotification(message, "info", timer)
}

/**
 * Exibe modal de confirmação
 * @param {string} title - Título da confirmação
 * @param {string} message - Mensagem
 * @param {function} onConfirm - Callback ao confirmar
 * @param {function} onCancel - Callback ao cancelar
 * @param {string} confirmText - Texto do botão confirmar
 * @param {string} cancelText - Texto do botão cancelar
 */
function showConfirmation(title, message, onConfirm, onCancel, confirmText = "Confirmar", cancelText = "Cancelar") {
  const Swal = window.Swal

  if (Swal) {
    Swal.fire({
      title: title,
      html: message,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: confirmText,
      cancelButtonText: cancelText,
      confirmButtonColor: "#f8c10d",
      cancelButtonColor: "#6c757d",
    }).then((result) => {
      if (result.isConfirmed) {
        if (onConfirm) onConfirm()
      } else if (result.isDismissed) {
        if (onCancel) onCancel()
      }
    })
  } else {
    // Fallback para confirm() nativo
    if (confirm(`${title}\n\n${message}`)) {
      if (onConfirm) onConfirm()
    } else {
      if (onCancel) onCancel()
    }
  }
}

// Exportar funções globalmente
window.showNotification = showNotification
window.showSuccess = showSuccess
window.showError = showError
window.showWarning = showWarning
window.showInfo = showInfo
window.showConfirmation = showConfirmation
