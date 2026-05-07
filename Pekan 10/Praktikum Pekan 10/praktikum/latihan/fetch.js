

fetch("https://jsonplaceholder.typicode.com/users/1")
  .then((response) => response.json())
  .then((data) => {
    console.log("Praktikum 19 - Nama:", data.name);
    console.log("Praktikum 19 - Email:", data.email);
  });

