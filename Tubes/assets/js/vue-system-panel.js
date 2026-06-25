(function () {
    const mount = document.getElementById("vueSystemPanel");
    if (!mount || !window.Vue) return;

    const { createApp } = window.Vue;

    createApp({
        data() {
            return {
                user: {},
                reviews: []
            };
        },
        computed: {
            displayName() {
                return this.user.nama_lengkap || this.user.username || "Pengguna";
            },
            totalReviews() {
                return this.reviews.length;
            },
            averageRating() {
                if (!this.reviews.length) return "-";
                const total = this.reviews.reduce((sum, review) => sum + Number(review.rating || 0), 0);
                return (total / this.reviews.length).toFixed(1);
            },
            accessLabel() {
                return this.user.role === "admin" ? "Admin" : "User";
            },
            accessDescription() {
                return this.user.role === "admin"
                    ? "Dapat mengakses mode pengelolaan lokasi."
                    : "Dapat menjelajah peta, profil, dan review.";
            }
        },
        async mounted() {
            await Promise.all([this.loadUser(), this.loadReviews()]);
        },
        methods: {
            async loadUser() {
                const response = await fetch("api/auth.php?action=me");
                const payload = await response.json();
                this.user = payload.user || {};
            },
            async loadReviews() {
                const response = await fetch("api/reviews.php");
                const payload = await response.json();
                this.reviews = Array.isArray(payload.data) ? payload.data : [];
            }
        }
    }).mount(mount);
})();
