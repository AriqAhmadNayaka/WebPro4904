function showPopup(options) {
  const defaults = {
    title: "Pemberitahuan",
    message: "",
    icon: "🔔",
    type: "info",
    buttonText: "OK",
    onClose: () => {},
  };

  const settings = { ...defaults, ...options };

  if (!options.icon) {
    if (settings.type === "success") settings.icon = "✅";
    if (settings.type === "error") settings.icon = "❌";
    if (settings.type === "info") settings.icon = "ℹ️";
  }

  let overlay = document.querySelector(".popup-overlay");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.className = "popup-overlay";
    overlay.innerHTML = `
            <div class="popup-card">
                <div class="popup-icon"></div>
                <div class="popup-title"></div>
                <div class="popup-message"></div>
                <button class="popup-button"></button>
            </div>
        `;
    document.body.appendChild(overlay);
  }

  const card = overlay.querySelector(".popup-card");
  card.className = "popup-card popup-" + settings.type;
  overlay.querySelector(".popup-icon").innerText = settings.icon;
  overlay.querySelector(".popup-title").innerText = settings.title;
  overlay.querySelector(".popup-message").innerText = settings.message;

  const btn = overlay.querySelector(".popup-button");
  btn.innerText = settings.buttonText;

  setTimeout(() => {
    overlay.classList.add("active");
  }, 10);

  const closePopup = () => {
    overlay.classList.remove("active");
    setTimeout(() => {
      if (typeof settings.onClose === "function") {
        settings.onClose();
      }
    }, 300);
  };

  btn.onclick = closePopup;
  overlay.onclick = (e) => {
    if (e.target === overlay) closePopup();
  };
}

window.customAlert = (
  message,
  type = "info",
  title = "Pemberitahuan",
  onClose,
) => {
  showPopup({
    message: message,
    type: type,
    title: title,
    onClose: onClose,
  });
};

window.customConfirm = (message, onConfirm, onCancel) => {
  const overlay = document.createElement("div");
  overlay.className = "popup-overlay";
  overlay.innerHTML = `
        <div class="popup-card popup-info">
            <div class="popup-icon">❓</div>
            <div class="popup-title">Konfirmasi</div>
            <div class="popup-message">${message}</div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="popup-button cancel" style="background: #e2e8f0; color: #475569;">Batal</button>
                <button class="popup-button confirm">Lanjutkan</button>
            </div>
        </div>
    `;
  document.body.appendChild(overlay);

  setTimeout(() => overlay.classList.add("active"), 10);

  const close = (callback) => {
    overlay.classList.remove("active");
    setTimeout(() => {
      document.body.removeChild(overlay);
      if (callback) callback();
    }, 300);
  };

  overlay.querySelector(".confirm").onclick = () => close(onConfirm);
  overlay.querySelector(".cancel").onclick = () => close(onCancel);
  overlay.onclick = (e) => {
    if (e.target === overlay) close(onCancel);
  };
};
