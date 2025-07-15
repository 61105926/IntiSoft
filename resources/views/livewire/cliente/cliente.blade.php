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
    @if (session()->has('message'))
        <div class="alert alert-success mt-3">{{ session('message') }}</div>
    @endif
    <!-- Tabla de Clientes -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Clientes ({{ $clientes->total() }})</h3>
            <div class="text-muted small">Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}</div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre/Razón Social</th>
                            <th>Identificación</th>
                            <th>Contacto</th>
                            <th>Dirección</th>
                            <th>Tipo</th>
                            <th>Registro</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td class="fw-semibold">
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
                                        CI: {{ $cliente->carnet_identidad }}
                                    @elseif ($cliente->tipo_cliente == 'EMPRESA')
                                        NIT: {{ $cliente->clienteEmpresa->nit ?? '-' }}
                                    @elseif ($cliente->tipo_cliente == 'UNIDAD_EDUCATIVA')
                                        CÓD: {{ $cliente->unidadEducativa->codigo ?? '-' }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $tel1 =
                                            $cliente->clienteEmpresa->telefono_principal ??
                                            ($cliente->unidadEducativa->telefono ?? ($cliente->telefono ?? null));
                                        $tel2 = $cliente->clienteEmpresa->telefono_secundario ?? null;
                                        $mail =
                                            $cliente->clienteEmpresa->email ??
                                            ($cliente->unidadEducativa->email ?? ($cliente->email ?? null));
                                    @endphp

                                    @if ($tel1)
                                        <div>{{ $tel1 }}</div>
                                    @endif
                                    @if ($tel2)
                                        <div>{{ $tel2 }}</div>
                                    @endif
                                    @if ($mail)
                                        <div class="text-muted small">{{ Str::limit($mail, 30) }}</div>
                                    @endif
                                </td>
                                <td>{{ Str::limit($cliente->direccion, 40) }}</td>
                                <td><span class="badge bg-secondary">{{ $cliente->tipo_cliente }}</span></td>
                                <td>
                                    <div>{{ $cliente->created_at->format('Y-m-d') }}</div>
                                    <div class="text-muted small">{{ $cliente->sucursal->nombre ?? '-' }}</div>
                                    <div class="text-muted small">Por: {{ $cliente->usuario->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $cliente->activo ? 'bg-success' : 'bg-danger' }}">
                                        {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button wire:click="verCliente({{ $cliente->id }})"
                                            class="btn btn-sm btn-outline-primary" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button wire:click="editarCliente({{ $cliente->id }})"
                                            class="btn btn-sm btn-outline-secondary" title="Editar"><i
                                                class="fas fa-pen"></i></button>
                                        <button wire:click="eliminarCliente({{ $cliente->id }})"
                                            class="btn btn-sm btn-danger" title="Eliminar"
                                            onclick="return confirm('¿Seguro que quieres eliminar este cliente?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">No hay clientes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $clientes->links() }}
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
