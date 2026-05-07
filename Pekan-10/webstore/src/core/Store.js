import { deepClone } from "./utils.js";

let state = {
  products: [],
  cart: [],
};

export const getState = () => deepClone(state);

export const setState = (newState) => {
  state = {
    ...state,
    ...newState,
  };

  console.log("[Store] State updated:", state);
};
