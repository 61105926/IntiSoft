<template>
    <multiselect
        v-model="selected"
        :options="options"
        label="nombre"
        track-by="id"
        placeholder="Selecciona o escribe un producto"
        :taggable="true"
        @tag="addTag"
    ></multiselect>
</template>

<script setup>
import { ref, watch } from 'vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';

// Props (en caso de que quieras recibir productos por props)
const options = ref(window.productos || []);
const selected = ref(null);

// Emitir a Livewire
watch(selected, (newVal) => {
    if (newVal) {
        Livewire.emit('productoSeleccionado', typeof newVal === 'string' ? newVal : newVal.id);
    }
});

const addTag = (newTag) => {
    const tagObject = { id: newTag, nombre: newTag };
    options.value.push(tagObject);
    selected.value = tagObject;
    Livewire.emit('productoSeleccionado', newTag);
};
</script>
