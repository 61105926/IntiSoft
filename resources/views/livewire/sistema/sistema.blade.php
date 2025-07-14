<div>
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fas fa-cogs fa-lg me-2 text-primary"></i>
            <h3 class="mb-0">Parámetros del Sistema</h3>
        </div>
        <button class="btn btn-warning text-dark">
            <i class="fas fa-plus me-2"></i>
            Nuevo Parámetro
        </button>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-medium">TASA IVA</td>
                        <td>13</td>
                        <td><span class="badge bg-secondary">NUMÉRICO</span></td>
                        <td>Tasa de Impuesto al Valor Agregado</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-medium">DIAS ALERTA ALQUILER</td>
                        <td>3</td>
                        <td><span class="badge bg-secondary">NUMÉRICO</span></td>
                        <td>Días antes para alertar vencimiento de alquiler</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-medium">EMAIL NOTIFICACIONES</td>
                        <td>Verdadero</td>
                        <td><span class="badge bg-secondary">BOOLEANO</span></td>
                        <td>Habilitar envío de notificaciones por email</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-medium">LIMITE CREDITO DEFECTO</td>
                        <td>500</td>
                        <td><span class="badge bg-secondary">NUMÉRICO</span></td>
                        <td>Límite de crédito por defecto para nuevos clientes</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-medium">MENSAJE BIENVENIDA</td>
                        <td>Bienvenido a Folklóricas Andinas!</td>
                        <td><span class="badge bg-secondary">TEXTO</span></td>
                        <td>Mensaje de bienvenida en el sistema</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
