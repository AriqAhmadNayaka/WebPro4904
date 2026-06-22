<section id="wasteApp" class="panel panel-pad">
	<h2 class="h4 fw-bold mb-4">Formulir Pelaporan Pembuangan Limbah</h2>

	<!-- Pesan sukses/error dari proses tambah, edit, atau hapus laporan. -->
	<div v-if="message" class="alert py-2" :class="messageClass">{{ message }}</div>

	<!-- Form CRUD laporan limbah. Jika editingId terisi, form menjadi mode edit. -->
	<form @submit.prevent="save" class="row g-3 align-items-end mb-4">
		<div class="col-lg-2">
			<label class="form-label">Tanggal</label>
			<input v-model="form.report_date" type="date" class="form-control" required>
		</div>
		<div class="col-lg-2">
			<label class="form-label">Jenis Limbah</label>
			<select v-model="form.waste_type" class="form-select" required>
				<option value="Organik">Organik</option>
				<option value="Plastik">Plastik</option>
				<option value="Minyak jelantah">Minyak jelantah</option>
				<option value="Kertas">Kertas</option>
				<option value="Campuran">Campuran</option>
			</select>
		</div>
		<div class="col-lg-2">
			<label class="form-label">Berat (kg)</label>
			<input v-model.number="form.weight_kg" type="number" min="0.1" step="0.1" class="form-control" required>
		</div>
		<div class="col-lg-3">
			<label class="form-label">Metode Pembuangan</label>
			<input v-model.trim="form.disposal_method" class="form-control" placeholder="Kompos, daur ulang, vendor" required>
		</div>
		<div class="col-lg-2">
			<label class="form-label">Catatan</label>
			<input v-model.trim="form.notes" class="form-control" placeholder="Opsional">
		</div>
		<div class="col-lg-1 d-flex gap-2">
			<button class="btn btn-eco w-100" type="submit" :disabled="loading">{{ loading ? '...' : (editingId ? 'Update' : 'Simpan') }}</button>
			<button v-if="editingId" class="btn btn-outline-secondary" type="button" @click="resetForm">Batal</button>
		</div>
	</form>

	<h2 class="h4 fw-bold mb-3">Daftar Laporan Limbah</h2>
	<!-- Tabel menampilkan laporan limbah yang diambil dari REST API. -->
	<div class="table-responsive">
		<table class="table mb-0">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<th>Jenis</th>
					<th>Berat</th>
					<th>Metode</th>
					<th>Status</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<tr v-if="!reports.length">
					<td colspan="7" class="text-center text-secondary py-4">Belum ada laporan.</td>
				</tr>
				<tr v-for="(item, index) in reports" :key="item.id">
					<td>{{ index + 1 }}</td>
					<td>{{ item.report_date }}</td>
					<td class="fw-semibold">{{ item.waste_type }}</td>
					<td>{{ Number(item.weight_kg).toFixed(1) }} kg</td>
					<td>
						<div>{{ item.disposal_method }}</div>
						<div class="small text-secondary">{{ item.notes }}</div>
					</td>
					<td>Tercatat</td>
					<td>
						<button class="action-link action-edit" @click="edit(item)">Edit</button>
						<span>|</span>
						<button class="action-link action-delete" @click="remove(item.id)">Hapus</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</section>

<script>
const { createApp } = Vue;

// Vue digunakan agar CRUD berjalan async tanpa reload halaman.
createApp({
	data() {
		// State utama halaman laporan limbah.
		return {
			apiUrl: '<?php echo site_url('api/waste-reports'); ?>',
			reports: [],
			form: { report_date: new Date().toISOString().slice(0, 10), waste_type: 'Organik', weight_kg: 1, disposal_method: '', notes: '' },
			editingId: null,
			loading: false,
			message: '',
			messageType: 'success'
		};
	},
	computed: {
		messageClass() {
			// Menentukan warna alert berdasarkan hasil request.
			return this.messageType === 'success' ? 'alert-success' : 'alert-danger';
		}
	},
	mounted() {
		// Saat halaman pertama kali dibuka, langsung ambil data dari API.
		this.fetchReports();
	},
	methods: {
		async fetchReports() {
			// GET data laporan limbah dari REST API.
			const response = await fetch(this.apiUrl);
			this.reports = await response.json();
		},
		async save() {
			// Menyimpan laporan baru atau update laporan lama sesuai editingId.
			this.loading = true;
			this.message = '';
			const url = this.editingId ? `${this.apiUrl}/${this.editingId}` : this.apiUrl;
			const method = this.editingId ? 'PUT' : 'POST';

			try {
				// Mengirim data form ke API dalam format JSON.
				const response = await fetch(url, {
					method,
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(this.form)
				});
				const result = await response.json();
				this.messageType = response.ok ? 'success' : 'error';
				this.message = result.message || 'Request selesai.';
				if (response.ok) {
					// Setelah berhasil, kosongkan form dan refresh tabel.
					this.resetForm();
					await this.fetchReports();
				}
			} finally {
				this.loading = false;
			}
		},
		edit(item) {
			// Memasukkan data tabel ke form agar bisa diedit.
			this.editingId = item.id;
			this.form = {
				report_date: item.report_date,
				waste_type: item.waste_type,
				weight_kg: Number(item.weight_kg),
				disposal_method: item.disposal_method,
				notes: item.notes || ''
			};
		},
		async remove(id) {
			// Menghapus laporan setelah user mengonfirmasi.
			if (!confirm('Hapus laporan ini?')) return;
			const response = await fetch(`${this.apiUrl}/${id}`, { method: 'DELETE' });
			const result = await response.json();
			this.messageType = response.ok ? 'success' : 'error';
			this.message = result.message || 'Request selesai.';
			await this.fetchReports();
		},
		resetForm() {
			// Mengembalikan form ke mode tambah data.
			this.editingId = null;
			this.form = { report_date: new Date().toISOString().slice(0, 10), waste_type: 'Organik', weight_kg: 1, disposal_method: '', notes: '' };
		}
	}
}).mount('#wasteApp');
</script>
