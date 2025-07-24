import { createApp } from "vue";
import ProductoSelect from "./components/ProductoSelect.vue";

const app = createApp({});
app.component("producto-select", ProductoSelect);
app.mount("#vue-app");
