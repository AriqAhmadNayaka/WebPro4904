// Static featured recipes
const staticRecipes = [
  {
    title: "Bubur Ayam MPASI 6 Bulan",
    image: "https://images.unsplash.com/photo-1516684732162-798a0062be99?w=400",
    source: "Resep Sehat",
    calories: 120,
    protein: 8,
    servings: 2,
    url: "/src/pages/user/(features)/recipes.html?q=baby%20food",
    badge: "🌱 Sehat",
  },
  {
    title: "Sup Ayam Sayuran",
    image: "https://images.unsplash.com/photo-1547592166-23ac45744acd?w=400",
    source: "Resep Keluarga",
    calories: 180,
    protein: 15,
    servings: 4,
    url: "/src/pages/user/(features)/recipes.html?q=chicken%20soup",
    badge: "💪 Tinggi Protein",
  },
  {
    title: "Smoothie Bowl Buah",
    image: "https://images.unsplash.com/photo-1590301157890-4810ed352733?w=400",
    source: "Healthy Kids",
    calories: 150,
    protein: 5,
    servings: 2,
    url: "/src/pages/user/(features)/recipes.html?q=smoothie",
    badge: "🍓 Segar",
  },
  {
    title: "Pasta Sayuran untuk Anak",
    image: "https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400",
    source: "Kids Meal",
    calories: 250,
    protein: 10,
    servings: 3,
    url: "/src/pages/user/(features)/recipes.html?q=pasta",
    badge: "🥗 Vegetarian",
  },
  {
    title: "Nugget Ikan Homemade",
    image: "https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400",
    source: "Mom's Recipe",
    calories: 200,
    protein: 18,
    servings: 6,
    url: "/src/pages/user/(features)/recipes.html?q=fish",
    badge: "🐟 Omega-3",
  },
  {
    title: "Pancake Pisang Sehat",
    image: "https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400",
    source: "Breakfast Ideas",
    calories: 160,
    protein: 6,
    servings: 4,
    url: "/src/pages/user/(features)/recipes.html?q=healthy%20snack",
    badge: "🍌 Tanpa Gula",
  },
];

// Display static recipes
function displayStaticRecipes() {
  const grid = document.getElementById("homeRecipeCards");
  if (!grid) return;

  staticRecipes.forEach((recipe, index) => {
    const card = document.createElement("div");
    card.className = "recipe-card-home";
    card.style.animationDelay = `${index * 0.1}s`;

    card.innerHTML = `
      <div class="recipe-card-img">
        <img src="${recipe.image}" alt="${recipe.title}" loading="lazy">
        <span class="recipe-badge">${recipe.badge}</span>
      </div>
      <div class="recipe-card-body">
        <h3>${recipe.title}</h3>
        <p class="recipe-card-source">
          <i class="fas fa-utensils"></i> ${recipe.source}
        </p>
        <div class="recipe-card-meta">
          <span><i class="fas fa-fire"></i> ${recipe.calories} kal</span>
          <span><i class="fas fa-drumstick-bite"></i> ${recipe.protein}g</span>
          <span><i class="fas fa-users"></i> ${recipe.servings} porsi</span>
        </div>
        <a href="${recipe.url}" class="recipe-card-btn">
          <i class="fas fa-book-open"></i>
          Lihat Resep
        </a>
      </div>
    `;

    grid.appendChild(card);
  });
}

document.addEventListener("DOMContentLoaded", displayStaticRecipes);
