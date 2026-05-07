import { deepClone } from "./utils.js";

let state = {
  products: [],
  cart: [],
};

export const getState = () => deepClone(state);

export const setState = (newState) => {
  const nextState = { ...state, ...newState };

  state = nextState;

  console.log("[Store] State updated:", state);
};
