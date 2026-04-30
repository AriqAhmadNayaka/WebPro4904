const modal = document.getElementById('modalForm');
const openModalButton = document.getElementById('openModal');
const closeModalButton = document.getElementById('closeModal');
const cancelModalButton = document.getElementById('cancelModal');
const searchInput = document.getElementById('searchPeserta');
const form = document.getElementById('pesertaForm');
const modalTitle = document.getElementById('modalTitle');
const idInput = document.getElementById('idInput');
const fotoLamaInput = document.getElementById('fotoLamaInput');
const namaInput = document.getElementById('namaInput');
const umurInput = document.getElementById('umurInput');
const genderInput = document.getElementById('genderInput');
const pelatihanInput = document.getElementById('pelatihanInput');
const fotoInput = document.getElementById('fotoInput');
const fotoInfo = document.getElementById('fotoInfo');

function resetForm() {
    if (!form) {
        return;
    }

    form.reset();
    idInput.value = '';
    fotoLamaInput.value = '';
    modalTitle.textContent = 'Tambah Peserta';
    fotoInput.required = true;
    fotoInfo.textContent = 'Foto peserta wajib saat tambah data.';
}

function openModal() {
    if (modal) {
        modal.classList.add('show');
    }
}

function closeModal() {
    if (modal) {
        modal.classList.remove('show');
    }
}

if (openModalButton) {
    openModalButton.addEventListener('click', () => {
        resetForm();
        openModal();
    });
}

if (closeModalButton) {
    closeModalButton.addEventListener('click', closeModal);
}

if (cancelModalButton) {
    cancelModalButton.addEventListener('click', closeModal);
}

if (modal) {
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
}

window.editPeserta = function (button) {
    idInput.value = button.dataset.id || '';
    fotoLamaInput.value = button.dataset.foto || '';
    namaInput.value = button.dataset.nama || '';
    umurInput.value = button.dataset.umur || '';
    genderInput.value = button.dataset.jk || 'Laki-laki';
    pelatihanInput.value = button.dataset.pelatihan || '';
    fotoInput.value = '';
    fotoInput.required = false;
    modalTitle.textContent = 'Edit Peserta';
    fotoInfo.textContent = button.dataset.foto
        ? `Foto saat ini: ${button.dataset.foto}. Upload lagi jika ingin mengganti.`
        : 'Belum ada foto. Upload foto baru untuk melengkapinya.';
    openModal();
};

if (searchInput) {
    searchInput.addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#pesertaTable tr');

        rows.forEach((row) => {
            const targetCell = row.children[1];
            if (!targetCell) {
                return;
            }

            row.style.display = targetCell.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
}
