<section id="ratingApp" class="panel panel-pad">
	<h2 class="h4 fw-bold mb-4">Formulir Rating Restoran</h2>

	<!-- Pesan sukses/error dari proses tambah, edit, atau hapus data. -->
	<div v-if="message" class="alert py-2" :class="messageClass">{{ message }}</div>

	<!-- Form CRUD rating restoran. Jika editingId terisi, form menjadi mode edit. -->
	<form @submit.prevent="save" class="row g-3 align-items-end mb-4">
		<div class="col-lg-4">
			<label class="form-label">Nama Restoran</label>
			<input v-model.trim="form.restaurant_name" class="form-control" placeholder="Contoh: Resto Saji Hijau" required>
		</div>
		<div class="col-lg-2">
			<label class="form-label">Rating</label>
			<select v-model.number="form.rating" class="form-select" required>
				<option :value="5">5</option>
				<option :value="4">4</option>
				<option :value="3">3</option>
				<option :value="2">2</option>
				<option :value="1">1</option>
			</select>
		</div>
		<div class="col-lg-4">
			<label class="form-label">Ulasan</label>
			<input v-model.trim="form.review" class="form-control" placeholder="Tulis ulasan singkat" required>
		</div>
		<div class="col-lg-2 d-flex gap-2">
			<button class="btn btn-eco w-100" type="submit" :disabled="loading">{{ loading ? 'Menyimpan...' : (editingId ? 'Update' : 'Simpan') }}</button>
			<button v-if="editingId" class="btn btn-outline-secondary" type="button" @click="resetForm">Batal</button>
		</div>
	</form>

	<h2 class="h4 fw-bold mb-3">Daftar Rating Restoran</h2>
	<!-- Tabel menampilkan data rating yang diambil dari REST API. -->
	<div class="table-responsive">
		<table class="table mb-0">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Restoran</th>
					<th>Rating</th>
					<th>Ulasan</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<tr v-if="!ratings.length">
					<td colspan="5" class="text-center text-secondary py-4">Belum ada rating.</td>
				</tr>
				<tr v-for="(item, index) in ratings" :key="item.id">
					<td>{{ index + 1 }}</td>
					<td class="fw-semibold">{{ item.restaurant_name }}</td>
					<td>{{ item.rating }}/5</td>
					<td>{{ item.review }}</td>
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
		// State utama halaman rating.
		return {
			apiUrl: '<?php echo site_url('api/ratings'); ?>',
			ratings: [],
			form: { restaurant_name: '', rating: 5, review: '' },
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
		this.fetchRatings();
	},
	methods: {
		async fetchRatings() {
			// GET data rating dari REST API.
			const response = await fetch(this.apiUrl);
			this.ratings = await response.json();
		},
		async save() {
			// Menyimpan data baru atau update data lama sesuai editingId.
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
					await this.fetchRatings();
				}
			} finally {
				this.loading = false;
			}
		},
		edit(item) {
			// Memasukkan data tabel ke form agar bisa diedit.
			this.editingId = item.id;
			this.form = {
				restaurant_name: item.restaurant_name,
				rating: Number(item.rating),
				review: item.review
			};
		},
		async remove(id) {
			// Menghapus data setelah user mengonfirmasi.
			if (!confirm('Hapus rating ini?')) return;
			const response = await fetch(`${this.apiUrl}/${id}`, { method: 'DELETE' });
			const result = await response.json();
			this.messageType = response.ok ? 'success' : 'error';
			this.message = result.message || 'Request selesai.';
			await this.fetchRatings();
		},
		resetForm() {
			// Mengembalikan form ke mode tambah data.
			this.editingId = null;
			this.form = { restaurant_name: '', rating: 5, review: '' };
		}
	}
}).mount('#ratingApp');
</script>
