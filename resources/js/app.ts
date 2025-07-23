import { createApp, ref, watch } from "vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

const App = {
  components: { Multiselect },
  setup() {
    const options = ref(window.productos || []);
    const selected = ref(null);

    // Escuchar cambios en la selección y emitir a Livewire
    watch(selected, (newVal) => {
      if (newVal) {
        Livewire.emit(
          "productoSeleccionado",
          typeof newVal === "string" ? newVal : newVal.id
        );
      }
    });

    // Manejar tag (texto nuevo)
    const addTag = (newTag) => {
      const tagObject = { id: newTag, nombre: newTag };
      options.value.push(tagObject);
      selected.value = tagObject;
      Livewire.emit("productoSeleccionado", newTag);
    };

    return { selected, options, addTag };
  },
  template: `
<multiselect v-model="selected" :options="options" label="nombre" track-by="id"
    placeholder="Selecciona o escribe un producto" :taggable="true" @tag="addTag"></multiselect>
`,
};

createApp(App).mount("#vue-app");
