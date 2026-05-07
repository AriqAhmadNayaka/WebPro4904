
const tasks = ["Belajar", "Ngoding"];
const newTasks = [...tasks, "Istirahat"];

console.log("Praktikum 8 - tasks asli:", tasks);
console.log("Praktikum 8 - newTasks:", newTasks);


let state = { count: 0 };

function increment() {

  state = { ...state, count: state.count + 1 };
  console.log("Praktikum 9 - State setelah increment:", state);
}

increment();
increment();
increment();
