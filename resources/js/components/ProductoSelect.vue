<template>
    <div>
        <label class="block mb-1 font-bold">Producto</label>
        <Multiselect
            v-model="selected"
            :options="productos"
            placeholder="Selecciona o escribe un producto"
            :taggable="true"
            @update:modelValue="emitir"
            class="w-full"
            label="label"
            track-by="value"
            @input="window.Livewire.emit('productoSeleccionadoActualizado', selected)"
        />
    </div>
</template>

<script lang="ts" setup>
import { ref, defineEmits, defineProps } from 'vue';
import Multiselect from 'vue-multiselect';

interface ProductoOption {
    label: string;
    value: string | number;
}

const props = defineProps<{
    productos: ProductoOption[];
}>();

const selected = ref<ProductoOption | null>(null);

const emit = defineEmits<{
    (e: 'cambio', valor: string | number): void;
}>();

function emitir() {
    if (selected.value) {
        emit('cambio', selected.value.value);
    }
}
</script>

<style scoped>
/* Estilos opcionales */
</style>
