import { createApp } from 'vue';
// Importa la función principal de Vue 3 para crear una nueva aplicación.

import ProductoSelect from './components/ProductoSelect.vue';
// Importa tu componente Vue personalizado (por ejemplo, con vue-multiselect).

function mountVueComponent(id: string, component: any, name: string) {
    // Función reutilizable que monta un componente Vue dinámicamente.

    const el = document.getElementById(id);
    // Busca el contenedor en el DOM con el ID especificado.

    if (el && !el.dataset.vueMounted) {
        // Si existe y aún no se ha montado Vue (usa atributo personalizado para evitar doble montaje).

        const app = createApp({});
        // Crea una nueva instancia de aplicación Vue (sin opciones globales).

        app.component(name, component);
        // Registra el componente con el nombre personalizado (por ejemplo, 'producto-select').

        app.mount(el);
        // Monta la aplicación Vue dentro del elemento HTML encontrado.

        el.dataset.vueMounted = 'true';
        // Marca el elemento como montado usando dataset, para evitar montarlo de nuevo.
    }
}

const mountAll = () => {
    // Función que se llama cuando queremos montar todos los componentes necesarios.

    mountVueComponent('vue-app', ProductoSelect, 'producto-select');
    // Monta el componente ProductoSelect dentro del div con id="vue-app".
};

['DOMContentLoaded', 'livewire:load', 'livewire:update', 'shown.bs.modal'].forEach((evt) =>
    document.addEventListener(evt, mountAll),
);
// Escucha varios eventos clave:
// - DOMContentLoaded: cuando carga la página inicialmente.
// - livewire:load y livewire:update: cuando Livewire carga o actualiza el DOM.
// - shown.bs.modal: cuando se abre un modal de Bootstrap que podría contener Vue.
