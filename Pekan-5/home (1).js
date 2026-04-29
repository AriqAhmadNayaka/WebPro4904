document.addEventListener("DOMContentLoaded", function () {
  const currentUser = localStorage.getItem("currentUser");
  const user = JSON.parse(currentUser);
  if (currentUser) {
    const userName = document.querySelector(".greeting-text h3");
    userName.textContent = user.name;
  } else {
    window.location.href = "/index.html";
  }

  const isFirtTimeUser = () => {
    const user = JSON.parse(localStorage.getItem("currentUser"));
    return user && !localStorage.getItem("visited_" + user.email);
  };

  if (isFirtTimeUser()) {
    //Animasi Sebelum Ke Halaman Utama
    const splashScreen = document.createElement("div");
    splashScreen.style.position = "fixed";
    splashScreen.style.top = "0";
    splashScreen.style.left = "0";
    splashScreen.style.width = "100%";
    splashScreen.style.height = "100%";
    splashScreen.style.backgroundColor = "#ffffff";
    splashScreen.style.display = "flex";
    splashScreen.style.justifyContent = "center";
    splashScreen.style.alignItems = "center";
    splashScreen.style.zIndex = "9999";
    splashScreen.style.transition = "opacity 0.5s ease";
    splashScreen.classList.add("splash-screen");
    splashScreen.innerHTML = `
    <div class="splash-content">
      <h1 class="splash-title"></h1>
    </div>
  `;
    const splashTitle = splashScreen.querySelector(".splash-title");
    splashTitle.style.fontSize = "2rem";
    splashTitle.style.color = "#333333";
    splashTitle.style.transition = "opacity 0.5s ease";
    splashTitle.style.opacity = "0";

    setTimeout(() => {
      splashTitle.textContent = "Hi...";
      splashTitle.style.opacity = "1";
      setTimeout(() => {
        splashTitle.style.opacity = "0";
      }, 1500);
    }, 1500);
    setTimeout(() => {
      splashTitle.textContent = "Selamat Datang di Nuids!";
      splashTitle.style.opacity = "1";
      setTimeout(() => {
        splashTitle.style.opacity = "0";
      }, 4500);
    }, 4500);
    setTimeout(() => {
      splashScreen.style.opacity = "0";
    }, 6000);
    setTimeout(() => {
      splashScreen.style.display = "none";
      localStorage.setItem("visited_" + user.email, "true");
    }, 6500);

    splashScreen.remove();

    document.body.appendChild(splashScreen);
  }
});

const banner = document.querySelectorAll(".slide");
banner.forEach((e) => {
  e.addEventListener("click", () => {
    location.href = "/src/pages/user/(features)/articles.html";
  });
});
