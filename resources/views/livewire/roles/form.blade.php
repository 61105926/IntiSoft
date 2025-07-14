@include('common.modalhead')

<form class="mt-0"
    wire:submit.prevent="createRole">

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group mb-3">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-street-view"></i>
                    </span>
                    <input wire:model.lazy="roleName"
                        class="form-control"
                        type="text"
                        placeholder="Nombre del Rol">
                </div>
                @error('roleName')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h3>Permisos</h3>
                <div class="alert alert-danger">Selecciona al menos un permiso.</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th>Ver</th>
                        <th>Crear</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dashboard -->
                    <tr>
                        <td>Dashboard</td>
                        <td>
                            <input class="form-check-input" type="checkbox" value="dashboard.view" 
                                wire:model="selectedPermissions">
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <!-- Mascotas -->
                    <tr>
                        <td>Mascotas</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="pet.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td>
                            <input class="form-check-input" type="checkbox" 
                                value="pet.detail" 
                                wire:model="selectedPermissions">
                        </td>
                    </tr>

                    <!-- Razas -->
                    <tr>
                        <td>Razas</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="breed.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Especies -->
                    <tr>
                        <td>Especies</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="species.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Vacunas -->
                    <tr>
                        <td>Vacunas</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="vaccine.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Clientes -->
                    <tr>
                        <td>Clientes</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="client.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Inventario -->
                    <tr>
                        <td>Inventario</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="inventory.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Ventas -->
                    <tr>
                        <td>Ventas</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="sale.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Citas -->
                    <tr>
                        <td>Citas</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="appointment.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Proveedores -->
                    <tr>
                        <td>Proveedores</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="provider.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Compras -->
                    <tr>
                        <td>Compras</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="purchase.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>

                    <!-- Caja -->
                    <tr>
                        <td>Caja</td>
                        <td>
                            <input class="form-check-input" type="checkbox" 
                                value="cash.view" 
                                wire:model="selectedPermissions">
                        </td>
                        <td>
                            <input class="form-check-input" type="checkbox" 
                                value="cash.create" 
                                wire:model="selectedPermissions">
                        </td>
                        <td colspan="3"></td>
                    </tr>

                    <!-- Usuarios -->
                    <tr>
                        <td>Usuarios</td>
                        @foreach(['view', 'create', 'edit', 'delete'] as $action)
                            <td>
                                <input class="form-check-input" type="checkbox" 
                                    value="user.{{ $action }}" 
                                    wire:model="selectedPermissions">
                            </td>
                        @endforeach
                        <td></td>
                    </tr>
                </tbody>
            </table>
            @error('selectedPermissions')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</form>

@include('common.modalfooter')
