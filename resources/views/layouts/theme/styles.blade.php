<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet" />

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700"
    rel="stylesheet">
<link href="../src/bootstrap/css/bootstrap.min.css"
    rel="stylesheet"
    type="text/css" />
<link href="../layouts/semi-dark-menu/css/light/plugins.css"
    rel="stylesheet"
    type="text/css" />
<link href="../layouts/semi-dark-menu/css/dark/plugins.css"
    rel="stylesheet"
    type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
{{-- <link href="../src/plugins/src/apex/apexcharts.css"
    rel="stylesheet"
    type="text/css"> --}}
<link href="../src/assets/css/light/components/list-group.css"
    rel="stylesheet"
    type="text/css">
<link href="../src/assets/css/light/dashboard/dash_2.css"
    rel="stylesheet"
    type="text/css" />

<link href="../src/assets/css/dark/components/list-group.css"
    rel="stylesheet"
    type="text/css">
<link href="../src/assets/css/dark/dashboard/dash_2.css"
    rel="stylesheet"
    type="text/css" />
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
{{-- fontawasome --}}
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />
{{-- end --}}
<!-- BEGIN THEME GLOBAL STYLES -->
{{-- <link href="../src/plugins/src/flatpickr/flatpickr.css"
    rel="stylesheet"
    type="text/css"> --}}
<link href="../src/plugins/src/noUiSlider/nouislider.min.css"
    rel="stylesheet"
    type="text/css">
<!-- END THEME GLOBAL STYLES -->

<!--  BEGIN CUSTOM STYLE FILE  -->
{{-- <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css"
    rel="stylesheet"
    type="text/css"> --}}

{{-- <link href="../src/assets/css/dark/scrollspyNav.css"
    rel="stylesheet"
    type="text/css" /> --}}
{{-- <link href="../src/plugins/css/dark/flatpickr/custom-flatpickr.css"
    rel="stylesheet"
    type="text/css"> --}}
<!--  END CUSTOM STYLE FILE  -->
<link href="../src/assets/css/light/elements/custom-pagination.css"
    rel="stylesheet"
    type="text/css" />
<link href="../src/assets/css/dark/elements/custom-pagination.css"
    rel="stylesheet"
    type="text/css" />

<link href="../src/assets/css/light/components/tabs.css"
    rel="stylesheet"
    type="text/css" />
<link href="../src/assets/css/dark/components/tabs.css"
    rel="stylesheet"
    type="text/css" />

{{-- <link rel="stylesheet"
    type="text/css"
    href="../src/plugins/src/stepper/bsStepper.min.css"> --}}

{{-- <link rel="stylesheet"
    type="text/css"
    href="../src/assets/css/light/scrollspyNav.css" /> --}}
{{-- <link rel="stylesheet"
    type="text/css"
    href="../src/plugins/css/light/stepper/custom-bsStepper.css"> --}}

{{-- <link rel="stylesheet"
    type="text/css"
    href="../src/assets/css/dark/scrollspyNav.css" /> --}}
{{-- <link rel="stylesheet"
    type="text/css"
    href="../src/plugins/css/dark/stepper/custom-bsStepper.css"> --}}

{{-- <link href="../src/plugins/src/fullcalendar/fullcalendar.min.css"
    rel="stylesheet"
    type="text/css" />

<link href="../src/plugins/css/light/fullcalendar/custom-fullcalendar.css"
    rel="stylesheet"
    type="text/css" /> --}}
<link href="../src/assets/css/light/components/modal.css"
    rel="stylesheet"
    type="text/css">

{{-- <link href="../src/plugins/css/dark/fullcalendar/custom-fullcalendar.css"
    rel="stylesheet"
    type="text/css" /> --}}
<link href="../src/assets/css/dark/components/modal.css"
    rel="stylesheet"
    type="text/css">




{{-- edit app --}}
<link href="../src/plugins/src/flatpickr/flatpickr.css"
    rel="stylesheet"
    type="text/css">
<link rel="stylesheet"
    href="../src/plugins/src/filepond/filepond.min.css">
<link rel="stylesheet"
    href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

<link href="../src/plugins/css/light/filepond/custom-filepond.css"
    rel="stylesheet"
    type="text/css" />
{{-- <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css"
    rel="stylesheet"
    type="text/css"> --}}
<link href="../src/assets/css/light/apps/invoice-edit.css"
    rel="stylesheet"
    type="text/css" />

<link href="../src/plugins/css/dark/filepond/custom-filepond.css"
    rel="stylesheet"
    type="text/css" />
{{-- <link href="../src/plugins/css/dark/flatpickr/custom-flatpickr.css"
    rel="stylesheet"
    type="text/css"> --}}
<link href="../src/assets/css/dark/apps/invoice-edit.css"
    rel="stylesheet"
    type="text/css" />
{{-- end --}}


{{-- tom select  --}}

   <!--  BEGIN CUSTOM STYLE FILE  -->
   {{-- <link href="../src/assets/css/light/scrollspyNav.css" rel="stylesheet" type="text/css" />
   <link href="../src/assets/css/dark/scrollspyNav.css" rel="stylesheet" type="text/css" /> --}}

   {{-- <link rel="stylesheet" type="text/css" href="../src/plugins/src/tomSelect/tom-select.default.min.css">
   <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/tomSelect/custom-tomSelect.css">
   <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/tomSelect/custom-tomSelect.css"> --}}
   
   <!--  END CUSTOM STYLE FILE  -->
@livewireStyles
<style>
    /* Estilos básicos para la fila */
    .fila {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .fila:hover {
        background-color: #f0f0f0;
    }

    /* Estilos para la fila expandida */
    .fila-expandida {
        background-color: #ddd;
    }

    /* Estilos para los detalles expandidos */
    .detalles-expandidos {
        display: none;
    }

    .detalles-expandidos.mostrar {
        display: table-row;
    }
</style>
<style>
    #elementoOculto {
        display: none;
    }

    .select2-container--bootstrap .select2-selection {
        border: 1px solid #bfc9d4;
        font-size: 15px;
        padding: 8px 10px;
        letter-spacing: 1px;
        border-radius: 6px;
        height: auto;
        transition: none;
    }
</style>
<style>
    .wizard-step {
        display: none;
    }

    .wizard-step:target {
        display: block;
    }
</style>

<style>
    /* Estilos base para el wizard */
    .bs-stepper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .bs-stepper-circle {
        width: 40px;
        height: 40px;
        font-size: 20px;
        line-height: 40px;
        background-color: #650abb;
        color: #fff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .bs-stepper-label {
        font-size: 16px;
        margin-top: 5px;
    }

    .lines {
        width: 100%;
        height: 4px;
        background-color: #650abb;
        /* Cambia el color a azul */
        position: relative;
    }

    /* Estilo para desactivar el icono en el paso 2 */
    .step.inactive .bs-stepper-circle {
        background-color: #ccc;
    }

    /* Estilo para activar la línea de progreso */
    .step.active~.lines {
        width: 50%;
        /* Cambia el ancho a 50% cuando el paso es activo */
    }

    /* Estilos responsivos */
    @media (max-width: 576px) {
        .bs-stepper-circle {
            width: 30px;
            height: 30px;
            font-size: 16px;
            line-height: 30px;
        }

        .bs-stepper-label {
            font-size: 14px;
        }

        .lines {
            height: 3px;
        }
    }

    /* Estilos para la lista de fichas técnicas */
    .ficha-tecnica-item {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .ficha-tecnica-item .row {
        align-items: center;
    }

    .ficha-tecnica-item .col-md-6 {
        padding: 5px;
    }

    .ficha-tecnica-checkbox {
        margin-right: 5px;
    }

    .ficha-tecnica-label {
        display: inline-block;
    }

    .details {
        font-size: 14px;
        color: #555;
    }

    .details-label {
        font-weight: bold;
        margin-right: 5px;
    }
</style>
