<div>


    @include('livewire.cliente.form')



    <div class="row row-cols-1 row-cols-md-4 g-4 mb-4 mt-2">
        @php
            $estadisticas = $estadisticas ?? [
                'total' => 0,
                'persona_natural' => 0,
                'empresa' => 0,
                'unidad_educativa' => 0,
            ];
        @endphp
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Clientes</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['total'] }}</p>
                    </div>
                    <i class="fas fa-users text-primary fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Personas Naturales</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['persona_natural'] }}</p>
                    </div>
                    <i class="fas fa-user text-success fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Empresas</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['empresa'] }}</p>
                    </div>
                    <i class="fas fa-building text-purple fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Unidades Educativas</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['unidad_educativa'] }}</p>
                    </div>
                    <i class="fas fa-school text-warning fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row gap-3">
                <div class="flex-grow-1 position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" class="form-control ps-5"
                        placeholder="Buscar por nombre, CI, NIT, teléfono o email..." id="filtroBusqueda">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-user-tag text-muted"></i>
                    <select class="form-select" id="filtroTipoCliente">
                        <option value="TODOS">TODOS</option>
                        <option value="INDIVIDUAL">PERSONA NATURAL</option>
                        <option value="EMPRESA">EMPRESA</option>
                        <option value="UNIDAD_EDUCATIVA">UNIDAD EDUCATIVA</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Clientes -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="mb-0">Clientes ({{ count($clientes) }})</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tablaClientes">
                    <thead>
                        <tr>
                            <th>Nombre/Razón Social</th>
                            <th>Identificación</th>
                            <th>Contacto</th>
                            <th>Dirección</th>
                            <th>Tipo</th>
                            <th>Registro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr data-tipo="{{ $cliente->tipo_cliente }}">
                                <td class="fw-medium">
                                    @if ($cliente->tipo_cliente == 'INDIVIDUAL')
                                        {{ $cliente->nombres }} {{ $cliente->apellidos }}
                                    @elseif ($cliente->tipo_cliente == 'EMPRESA')
                                        {{ $cliente->clienteEmpresa->razon_social ?? '-' }}
                                    @elseif ($cliente->tipo_cliente == 'UNIDAD_EDUCATIVA')
                                        {{ $cliente->unidadEducativa->nombre ?? '-' }}
                                    @endif
                                </td>
                                <td>
                                    @if ($cliente->tipo_cliente == 'INDIVIDUAL')
                                        CI: {{ $cliente->ci }}
                                    @elseif ($cliente->tipo_cliente == 'EMPRESA')
                                        NIT: {{ $cliente->clienteEmpresa->nit ?? '-' }}
                                    @elseif ($cliente->tipo_cliente == 'UNIDAD_EDUCATIVA')
                                        NIT: {{ $cliente->unidadEducativa->nit ?? '-' }}
                                    @endif
                                </td>
                                <td>
                                    <p>{{ $cliente->telefono_principal }}</p>
                                    @if ($cliente->telefono_secundario)
                                        <p>{{ $cliente->telefono_secundario }}</p>
                                    @endif
                                    @if ($cliente->email)
                                        <p class="text-muted">{{ $cliente->email }}</p>
                                    @endif
                                </td>
                                <td>{{ $cliente->direccion }}</td>
                                <td><span class="badge bg-secondary">{{ $cliente->tipo_cliente }}</span></td>
                                <td>
                                    <p>{{ $cliente->created_at->format('Y-m-d') }}</p>
                                    <p class="text-muted">{{ $cliente->sucursal->nombre ?? '-' }}</p>
                                    <p class="text-muted">Por: {{ $cliente->usuario->name ?? '-' }}</p>
                                </td>
                                <td>
                                    <span class="badge {{ $cliente->activo ? 'bg-success' : 'bg-danger' }}">
                                        {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button wire:click="verCliente({{ $cliente->id }})"
                                            class="btn btn-outline-secondary btn-sm" title="Ver"><i
                                                class="fas fa-eye"></i></button>
                                        <button wire:click="editarCliente({{ $cliente->id }})"
                                            class="btn btn-outline-secondary btn-sm" title="Editar"><i
                                                class="fas fa-pen"></i></button>
                                        <button wire:click="eliminarCliente({{ $cliente->id }})"
                                            class="btn btn-danger btn-sm" title="Eliminar"
                                            onclick="return confirm('¿Seguro que quieres eliminar este cliente?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay clientes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filtroBusqueda = document.getElementById('filtroBusqueda');
            const filtroTipoCliente = document.getElementById('filtroTipoCliente');
            const tablaClientes = document.getElementById('tablaClientes').getElementsByTagName('tbody')[0];

            function filtrar() {
                const texto = filtroBusqueda.value.toLowerCase();
                const tipo = filtroTipoCliente.value;

                for (let fila of tablaClientes.rows) {
                    const nombre = fila.cells[0].textContent.toLowerCase();
                    const identificacion = fila.cells[1].textContent.toLowerCase();
                    const contacto = fila.cells[2].textContent.toLowerCase();
                    const direccion = fila.cells[3].textContent.toLowerCase();
                    const filaTipo = fila.getAttribute('data-tipo');

                    const matchesTexto = nombre.includes(texto) || identificacion.includes(texto) || contacto
                        .includes(texto) || direccion.includes(texto);
                    const matchesTipo = (tipo === 'TODOS' || filaTipo === tipo);

                    fila.style.display = (matchesTexto && matchesTipo) ? '' : 'none';
                }
            }

            filtroBusqueda.addEventListener('input', filtrar);
            filtroTipoCliente.addEventListener('change', filtrar);
        });
    </script>
</div>
