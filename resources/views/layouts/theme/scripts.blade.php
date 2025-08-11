<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
<script src="../src/plugins/src/waves/waves.min.js"></script>
<script src="../layouts/semi-dark-menu/app.js"></script>

<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
{{-- <script src="../src/assets/js/scrollspyNav.js"></script> --}}
{{-- <script src="../src/plugins/src/tomSelect/tom-select.base.js"></script> --}}
{{-- <script src="../src/plugins/src/tomSelect/custom-tom-select.js"></script> --}}
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
{{-- <script src="../src/plugins/src/apex/apexcharts.min.js"></script> --}}
<script src="../src/assets/js/dashboard/dash_2.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script>
    var currentURL = window.location.href;

    // Obtén todos los enlaces dentro de la lista
    var menuLinks = document.querySelectorAll('.menu a');

    // Recorre los enlaces y verifica si la URL coincide

    menuLinks.forEach(function(link) {
        if (link.href === currentURL) {
            // Agrega la clase "active" al elemento padre del enlace
            var parentListItem = link.closest('.menu');
            parentListItem.classList.add('active');

            // Verifica si el enlace es un subelemento
            if (link.parentNode.parentNode.classList.contains('submenu')) {
                // Agrega la clase "show" al elemento "collapse" correspondiente
                link.parentNode.parentNode.classList.add('show');

                // Agrega la clase "active" al elemento padre del submenú
                var parentListItem = link.closest('.menu').parentNode.closest('.menu');
                parentListItem.classList.add('active');

                // Abre el elemento padre del submenú solo si no está abierto por defecto
                var parentCollapse = link.closest('.menu').parentNode.closest('.menu').querySelector(
                    '.collapse');
                if (!parentCollapse.classList.contains('show')) {
                    parentCollapse.classList.add('show');
                }
            }
        }
    });
</script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
{{-- <script src="../src/plugins/src/flatpickr/flatpickr.js"></script>

<script src="../src/plugins/src/flatpickr/custom-flatpickr.js"></script> --}}
<!-- END PAGE LEVEL SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="../src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
{{-- <script src="../src/plugins/src/sweetalerts2/custom-sweetalert.js"></script> --}}




<!-- BEGIN PAGE LEVEL SCRIPTS -->
{{-- <script src="../src/plugins/src/stepper/bsStepper.min.js"></script>
<script src="../src/plugins/src/stepper/custom-bsStepper.min.js"></script> --}}

{{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@2"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"></script> --}}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->


<!-- END GLOBAL MANDATORY SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
{{-- <script src="../src/plugins/src/fullcalendar/fullcalendar.min.js"></script>
<script src="../src/plugins/src/uuid/uuid4.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<!--  BEGIN CUSTOM SCRIPTS FILE  -->
<script src="../src/plugins/src/fullcalendar/custom-fullcalendar.js"></script>
<script src="../src/plugins/src/fullcalendar/locale/es.js"></script> --}}

<!--  END CUSTOM SCRIPTS FILE  -->


<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
{{-- <script src="../src/plugins/src/apex/apexcharts.min.js"></script> --}}
{{-- <script src="../src/plugins/src/apex/custom-apexcharts.js"></script> --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#companySelect').select2({
            theme: 'bootstrap',
            placeholder: 'Seleccione la Empresa',
            dropdownParent: $('#exampleModal')
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#companySelect').select2({
            theme: 'bootstrap',
            placeholder: 'Seleccione la Empresa',
            dropdownParent: $('#nuevoModal')
        });
        window.livewire.on('show-modal', modalId => {
            $('#' + modalId).modal('show');
        });
        window.livewire.on('user-added', modalId => {
            $('#' + modalId).modal('hide'); // Cerrar el modal después de agregar usuario
        });
        window.livewire.on('user-updated', modalId => {
            $('#' + modalId).modal('hide'); // Cerrar el modal después de agregar usuario
        });
        window.livewire.on('show-modal-historic', msg => {
            $('#historic').modal('show');
        });
        window.livewire.on('hide-modal-historic', msg => {
            $('#historic').modal('hide');
        });
        window.livewire.on('hide-modal-aprobacion', msg => {
            $('#aprobacion' + msg).modal('hide');
        });
        window.livewire.on('show-modal-aprobacion', msg => {
            $('#aprobacion' + msg).modal('show');
        });
        window.livewire.on('hide-modal-aceptacion', msg => {
            $('#aceptacion' + msg).modal('hide');
        });
        window.livewire.on('show-modal-aceptacion', msg => {
            $('#aceptacion' + msg).modal('show');
        });
        document.addEventListener('livewire:load', function() {
            Livewire.on('mostrarAlertaSuccess', function(accion, codigo) {
                var mensaje = accion + '<br>' + codigo;

                Swal.fire({
                    position: 'top-center',
                    icon: 'success',
                    title: mensaje,
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        });

    });
  
</script>

<script></script>
<script>
    // JavaScript para filtrar el select según la búsqueda
    const selectInput = document.querySelector('select');

    selectInput.addEventListener('input', function() {
        const searchValue = this.previousElementSibling.value.toLowerCase();

        const options = this.querySelectorAll('option');
        options.forEach(option => {
            const text = option.innerText.toLowerCase();
            if (text.includes(searchValue)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    });

    selectInput.addEventListener('change', function(event) {
        var selectedValue = event.target.value;
        Livewire.emit('updateCompany', selectedValue);
    });
</script>


@livewireScripts
