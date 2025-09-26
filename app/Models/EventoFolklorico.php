<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventoFolklorico extends Model
{
    use HasFactory;

    protected $table = 'eventos_folkloricos';

    protected $fillable = [
        'numero_evento',
        'nombre_evento',
        'descripcion',
        'tipo_evento',
        'institucion_organizadora',
        'fecha_evento',
        'hora_evento',
        'lugar_evento',
        'direccion_evento',
        'numero_participantes',
        'costo_por_participante',
        'total_estimado',
        'total_real',
        'estado',
        'requiere_transporte',
        'observaciones',
        'sucursal_id',
        'usuario_creacion_id',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
        'hora_evento' => 'datetime:H:i',
        'requiere_transporte' => 'boolean',
        'costo_por_participante' => 'decimal:2',
        'total_estimado' => 'decimal:2',
        'total_real' => 'decimal:2',
    ];

    // Estados del evento
    const ESTADO_PLANIFICADO = 'PLANIFICADO';
    const ESTADO_CONFIRMADO = 'CONFIRMADO';
    const ESTADO_EN_CURSO = 'EN_CURSO';
    const ESTADO_FINALIZADO = 'FINALIZADO';
    const ESTADO_CANCELADO = 'CANCELADO';

    // Tipos de evento
    const TIPO_FESTIVAL = 'FESTIVAL';
    const TIPO_CONCURSO = 'CONCURSO';
    const TIPO_PRESENTACION = 'PRESENTACION';
    const TIPO_DESFILE = 'DESFILE';
    const TIPO_ESCOLAR = 'ESCOLAR';
    const TIPO_UNIVERSITARIO = 'UNIVERSITARIO';

    // Relaciones
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function participantes()
    {
        return $this->hasMany(EventoParticipante::class, 'evento_id');
    }

    public function vestimentas()
    {
        return $this->hasMany(EventoVestimenta::class, 'evento_id');
    }

    public function alquileres()
    {
        return $this->hasMany(Alquiler::class, 'evento_folklorico_id');
    }

    public function garantiasIndividuales()
    {
        return $this->hasMany(GarantiaIndividual::class, 'evento_id');
    }

    public function fletes()
    {
        return $this->hasMany(FleteProgramado::class, 'evento_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PLANIFICADO, self::ESTADO_CONFIRMADO, self::ESTADO_EN_CURSO]);
    }

    public function scopeProximos($query)
    {
        return $query->where('fecha_evento', '>=', Carbon::today())
                    ->where('estado', '!=', self::ESTADO_CANCELADO);
    }

    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    // Métodos de negocio
    public function puedeEditarse()
    {
        return in_array($this->estado, [self::ESTADO_PLANIFICADO, self::ESTADO_CONFIRMADO]);
    }

    public function puedeCancelarse()
    {
        return in_array($this->estado, [self::ESTADO_PLANIFICADO, self::ESTADO_CONFIRMADO]);
    }

    public function puedeFinalizarse()
    {
        return $this->estado === self::ESTADO_EN_CURSO;
    }

    public function calcularTotalEstimado()
    {
        return $this->numero_participantes * $this->costo_por_participante;
    }

    public function obtenerParticipantesConfirmados()
    {
        return $this->participantes()->where('estado_participante', '!=', EventoParticipante::ESTADO_CANCELADO)->count();
    }

    public function obtenerTotalRecaudado()
    {
        return $this->participantes()
                   ->where('estado_pago', EventoParticipante::PAGO_PAGADO)
                   ->sum('monto_participacion');
    }

    public function generarNumeroEvento()
    {
        $year = Carbon::now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'EVT-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function estaProximoAVencer($dias = 7)
    {
        return $this->fecha_evento <= Carbon::today()->addDays($dias) &&
               $this->fecha_evento >= Carbon::today();
    }

    public function obtenerProgreso()
    {
        $totalParticipantes = $this->numero_participantes;
        $participantesConfirmados = $this->obtenerParticipantesConfirmados();

        if ($totalParticipantes == 0) return 0;

        return round(($participantesConfirmados / $totalParticipantes) * 100, 2);
    }

    public function obtenerEstadisticas()
    {
        return [
            'total_participantes' => $this->numero_participantes,
            'participantes_confirmados' => $this->obtenerParticipantesConfirmados(),
            'participantes_pendientes' => $this->numero_participantes - $this->obtenerParticipantesConfirmados(),
            'total_recaudado' => $this->obtenerTotalRecaudado(),
            'total_estimado' => $this->calcularTotalEstimado(),
            'progreso_porcentaje' => $this->obtenerProgreso(),
            'vestimentas_asignadas' => $this->vestimentas()->count(),
            'vestimentas_entregadas' => $this->vestimentas()->where('estado_vestimenta', 'ENTREGADA')->count(),
        ];
    }
}