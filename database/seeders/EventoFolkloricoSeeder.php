<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventoFolklorico;
use App\Models\EventoParticipante;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\User;
use Carbon\Carbon;

class EventoFolkloricoSeeder extends Seeder
{
    public function run()
    {
        // Obtener datos existentes
        $sucursal = Sucursal::first();
        $usuario = User::first();
        $clientes = Cliente::take(20)->get();

        if (!$sucursal || !$usuario || $clientes->count() == 0) {
            $this->command->warn('No hay datos suficientes para crear eventos. Asegúrese de tener sucursales, usuarios y clientes.');
            return;
        }

        // Crear eventos folklóricos de ejemplo
        $eventos = [
            [
                'numero_evento' => 'EVT-2025-0001',
                'nombre_evento' => 'Festival de Danzas Autóctonas La Paz',
                'descripcion' => 'Evento folklórico que celebra las tradiciones ancestrales de La Paz con danzas autóctonas y trajes típicos de diferentes regiones.',
                'tipo_evento' => 'FESTIVAL',
                'institucion_organizadora' => 'Colegio San Patricio',
                'fecha_evento' => Carbon::now()->addDays(15),
                'hora_evento' => '10:00:00',
                'lugar_evento' => 'Teatro Municipal Alberto Saavedra Pérez',
                'direccion_evento' => 'Calle Indaburo esquina Loayza, Centro de La Paz',
                'numero_participantes' => 30,
                'costo_por_participante' => 150.00,
                'total_estimado' => 4500.00,
                'requiere_transporte' => true,
                'observaciones' => 'Evento escolar anual. Requiere trajes de diferentes regiones: Paceña, Cochabambina, Cruceña.',
                'estado' => 'PLANIFICADO',
            ],
            [
                'numero_evento' => 'EVT-2025-0002',
                'nombre_evento' => 'Concurso Universitario de Folklore',
                'descripcion' => 'Competencia anual entre universidades de Bolivia con representaciones folklóricas de alta calidad.',
                'tipo_evento' => 'CONCURSO',
                'institucion_organizadora' => 'Universidad Mayor de San Andrés',
                'fecha_evento' => Carbon::now()->addDays(25),
                'hora_evento' => '19:00:00',
                'lugar_evento' => 'Morenada Central UMSA',
                'direccion_evento' => 'Av. Villazón, Universidad Mayor de San Andrés',
                'numero_participantes' => 50,
                'costo_por_participante' => 200.00,
                'total_estimado' => 10000.00,
                'requiere_transporte' => false,
                'observaciones' => 'Evento universitario competitivo. Se requieren trajes de alta calidad para Morenada, Diablada, Caporal.',
                'estado' => 'CONFIRMADO',
            ],
            [
                'numero_evento' => 'EVT-2025-0003',
                'nombre_evento' => 'Desfile del Día del Folklor',
                'descripcion' => 'Desfile tradicional celebrando el Día Nacional del Folklor Boliviano.',
                'tipo_evento' => 'DESFILE',
                'institucion_organizadora' => 'Alcaldía de La Paz',
                'fecha_evento' => Carbon::now()->subDays(5),
                'hora_evento' => '14:00:00',
                'lugar_evento' => 'Avenida El Prado',
                'direccion_evento' => 'Av. 16 de Julio (El Prado), desde Plaza San Francisco hasta Plaza del Estudiante',
                'numero_participantes' => 80,
                'costo_por_participante' => 120.00,
                'total_estimado' => 9600.00,
                'total_real' => 9240.00,
                'requiere_transporte' => true,
                'observaciones' => 'Desfile masivo con múltiples danzas. Participación de colegios y universidades.',
                'estado' => 'FINALIZADO',
            ],
        ];

        foreach ($eventos as $eventoData) {
            $evento = EventoFolklorico::create([
                'numero_evento' => $eventoData['numero_evento'],
                'nombre_evento' => $eventoData['nombre_evento'],
                'descripcion' => $eventoData['descripcion'],
                'tipo_evento' => $eventoData['tipo_evento'],
                'institucion_organizadora' => $eventoData['institucion_organizadora'],
                'fecha_evento' => $eventoData['fecha_evento'],
                'hora_evento' => $eventoData['hora_evento'],
                'lugar_evento' => $eventoData['lugar_evento'],
                'direccion_evento' => $eventoData['direccion_evento'],
                'numero_participantes' => $eventoData['numero_participantes'],
                'costo_por_participante' => $eventoData['costo_por_participante'],
                'total_estimado' => $eventoData['total_estimado'],
                'total_real' => $eventoData['total_real'] ?? 0,
                'requiere_transporte' => $eventoData['requiere_transporte'],
                'observaciones' => $eventoData['observaciones'],
                'estado' => $eventoData['estado'],
                'sucursal_id' => $sucursal->id,
                'usuario_creacion_id' => $usuario->id,
            ]);

            // Crear participantes para cada evento
            $numParticipantes = min($eventoData['numero_participantes'], $clientes->count());
            $clientesParaEvento = $clientes->random($numParticipantes);

            foreach ($clientesParaEvento as $index => $cliente) {
                $numeroParticipante = $evento->numero_evento . '-P' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

                $participante = EventoParticipante::create([
                    'evento_id' => $evento->id,
                    'cliente_id' => $cliente->id,
                    'numero_participante' => $numeroParticipante,
                    'nombre_completo' => $cliente->nombres . ' ' . $cliente->apellidos,
                    'cedula' => $cliente->cedula,
                    'telefono' => $cliente->telefono,
                    'email' => $cliente->email,
                    'edad' => rand(16, 60),
                    'talla_general' => collect(['S', 'M', 'L', 'XL'])->random(),
                    'observaciones_especiales' => $index % 5 == 0 ? 'Requiere talla especial por altura' : null,
                    'monto_garantia' => 200.00,
                    'monto_participacion' => $eventoData['costo_por_participante'],
                    'estado_pago' => collect(['PENDIENTE', 'PARCIAL', 'PAGADO'])->random(),
                    'estado_participante' => $evento->estado === 'FINALIZADO' ? 'FINALIZADO' :
                                           ($evento->estado === 'CONFIRMADO' ? 'CONFIRMADO' : 'REGISTRADO'),
                    'fecha_registro' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }

            $this->command->info("Creado evento: {$evento->nombre_evento} con {$numParticipantes} participantes");
        }

        $this->command->info('Seeder de eventos folklóricos completado exitosamente.');
    }
}