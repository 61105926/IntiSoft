<div>
  <!-- Encabezado y botón Nueva Entrada -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3">Entradas Folklóricas</h1>
      <p class="text-muted">Gestión de eventos y participantes</p>
    </div>
    <button class="btn btn-warning d-flex align-items-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus me-2" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
      </svg>
      Nueva Entrada
    </button>
  </div>

  <!-- Estadísticas -->
  <div class="row mb-4">
    <div class="col-md-3 mb-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <p class="text-muted mb-1">Total Eventos</p>
          <h3 class="card-title">4</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <p class="text-muted mb-1">Programadas</p>
          <h3 class="text-primary">3</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <p class="text-muted mb-1">Participantes</p>
          <h3 class="text-success">630</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <p class="text-muted mb-1">Ingresos</p>
          <h3 class="text-purple">Bs. 32,150</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Buscador -->
  <div class="mb-4">
    <input type="text" class="form-control" placeholder="Buscar eventos...">
  </div>

  <!-- Eventos -->
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <p class="text-muted mb-0">ENT-2024-001</p>
              <h5 class="card-title">Gran Entrada Folklórica Estudiantil</h5>
              <p class="text-muted">Reserva: RES-2024-001 (Colegio San Andrés)</p>
            </div>
            <span class="badge bg-primary">Programada</span>
          </div>
          <div class="mb-3">
            <p class="mb-1"><strong>Fecha:</strong> 2024-08-15 14:00</p>
            <p class="mb-1"><strong>Lugar:</strong> Plaza Murillo</p>
            <p class="mb-0"><strong>Precio:</strong> Bs. 50 por entrada</p>
          </div>
          <div class="text-end mb-2">
            <p class="h5 text-success mb-1">Bs. 9,250</p>
            <p class="text-muted mb-0">Ingresos actuales</p>
            <p class="text-muted small">Creado por: Luis Pérez (Sucursal Centro)</p>
          </div>
          <div class="mb-3">
            <p class="d-flex justify-content-between mb-1"><span>Ocupación</span><span>93%</span></p>
            <div class="progress">
              <div class="progress-bar bg-warning" role="progressbar" style="width: 93%;"></div>
            </div>
            <p class="text-muted small mt-1">185 / 200 participantes</p>
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button class="btn btn-outline-secondary btn-sm">Ver Detalles</button>
            <button class="btn btn-outline-secondary btn-sm">Editar</button>
            <button class="btn btn-success btn-sm">Registrar Participante</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Repetir la estructura para los demás eventos -->
  </div>

  <!-- Participantes recientes -->
  <div class="card shadow-sm mt-5">
    <div class="card-body">
      <h5 class="card-title d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
          <path d="M5 3a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM0 8s3-7 8-7 8 7 8 7-3 7-8 7-8-7-8-7z"/>
        </svg>
        Participantes Recientes
      </h5>
      <div class="mt-3">
        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <p class="mb-0 fw-medium">María Elena Quispe</p>
                <p class="text-muted mb-0">CI: 8765432</p>
                <p class="text-muted mb-0">70123456</p>
              </div>
              <span class="badge bg-primary">Confirmado</span>
            </div>
            <p class="mb-1"><strong>Evento:</strong> Gran Entrada Folklórica Estudiantil</p>
            <p class="mb-1"><strong>Fecha:</strong> 2024-08-15 14:00</p>
            <div class="d-flex justify-content-between">
              <span>Garantía: GAR-001</span>
              <span>Registrado: 2024-07-15</span>
            </div>
            <p class="text-muted small">Registrado por: Luis Pérez</p>
            <div class="d-flex justify-content-end gap-2 mt-2">
              <button class="btn btn-outline-secondary btn-sm">Ver</button>
              <button class="btn btn-primary btn-sm">Devolver Ropa</button>
            </div>
          </div>
        </div>
        <!-- Repetir para más participantes -->
      </div>
    </div>
  </div>
</div>
