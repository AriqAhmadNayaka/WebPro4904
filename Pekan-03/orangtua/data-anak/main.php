const fields = [
    "nama",
    "gender",
    "tanggalLahir",
    "kelas",
    "alamat",
    "catatan"
];

const btn = document.getElementById("toggleBtn");

// ===== LOAD DATA =====
let dataAnak = JSON.parse(localStorage.getItem("dataAnak"));

if (dataAnak) {
    fields.forEach(id => {
        document.getElementById(id).value = dataAnak[id];
    });
    setFormLocked(true);
    setButtonEdit();
}
const fotoInput = document.getElementById("fotoInput");
const fotoProfil = document.getElementById("fotoProfil");

fotoInput.addEventListener("change", () => {
    const file = fotoInput.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        fotoProfil.src = e.target.result;
        localStorage.setItem("fotoProfil", e.target.result);
    };
    reader.readAsDataURL(file);
});

const savedFoto = localStorage.getItem("fotoProfil");
if (savedFoto) {
    fotoProfil.src = savedFoto;
}


// ===== LOCK / UNLOCK =====
function setFormLocked(lock) {
    fields.forEach(id => {
        const el = document.getElementById(id);
        if (el.tagName === "SELECT" || el.tagName === "INPUT") {
            el.disabled = lock;
        } else {
            el.readOnly = lock;
        }
    });
}


// ===== BUTTON STATE =====
function setButtonEdit() {
    btn.innerHTML = `<i class="ri-edit-line"></i> Edit Data`;
    btn.dataset.mode = "edit";
}

function setButtonSave() {
    btn.innerHTML = `<i class="ri-save-line"></i> Simpan Data`;
    btn.dataset.mode = "save";
}

// ===== CLICK BUTTON =====
btn.addEventListener("click", () => {

    // MODE SIMPAN
    if (btn.dataset.mode !== "edit") {

        const newData = {};
        fields.forEach(id => {
            newData[id] = document.getElementById(id).value;
        });

        localStorage.setItem("dataAnak", JSON.stringify(newData));

        setFormLocked(true);
        setButtonEdit();

    } 
    // MODE EDIT
    else {
        setFormLocked(false);
        setButtonSave();
    }
});
