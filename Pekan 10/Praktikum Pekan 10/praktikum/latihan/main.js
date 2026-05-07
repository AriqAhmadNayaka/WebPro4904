
import { tambah, kurang, PI } from "./utils.js";
console.log("Praktikum 21 - tambah(3,4):", tambah(3, 4));
console.log("Praktikum 21 - kurang(10,5):", kurang(10, 5));
console.log("Praktikum 21 - PI:", PI);


import { CounterComponent } from "./counterComponent.js";

const app = document.getElementById("app");


const counter1 = CounterComponent();
app.appendChild(counter1);


const counter2 = CounterComponent({ title: "Counter Pertama" });
const counter3 = CounterComponent({ title: "Counter Kedua" });
app.appendChild(counter2);
app.appendChild(counter3);
