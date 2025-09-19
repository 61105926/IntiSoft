<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\StockPorSucursal;
use App\Models\Caja;
use App\Models\TipoGarantia;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Crear empresa principal
        $empresa = Empresa::firstOrCreate(
            ['nit' => '123456789'],
            [
                'razon_social' => 'IntiSoft Folklórico',
                'direccion' => 'Av. El Dorado #123, La Paz',
                'telefono' => '2-2345678',
                'email' => 'info@intisoft.bo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Crear sucursales
        $sucursalPrincipal = Sucursal::firstOrCreate(
            [
                'codigo' => 'SUC001',
                'empresa_id' => $empresa->id
            ],
            [
                'nombre' => 'Sucursal Principal',
                'direccion' => 'Av. El Dorado #123, La Paz',
                'telefono' => '2-2345678',
                'responsable' => 'Juan Pérez',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $sucursalSantaCruz = Sucursal::firstOrCreate(
            [
                'codigo' => 'SUC002',
                'empresa_id' => $empresa->id
            ],
            [
                'nombre' => 'Sucursal Santa Cruz',
                'direccion' => 'Av. Banzer #456, Santa Cruz',
                'telefono' => '3-3456789',
                'responsable' => 'Ana García',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Crear categorías de productos
        $categorias = [
            ['nombre' => 'Polleras', 'descripcion' => 'Polleras tradicionales'],
            ['nombre' => 'Blusas', 'descripcion' => 'Blusas folklóricas'],
            ['nombre' => 'Chalecos', 'descripcion' => 'Chalecos tradicionales'],
            ['nombre' => 'Sombreros', 'descripcion' => 'Sombreros típicos'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Accesorios diversos'],
            ['nombre' => 'Disfraces Niños', 'descripcion' => 'Disfraces para niños'],
        ];

        foreach ($categorias as $categoria) {
            CategoriaProducto::firstOrCreate(['nombre' => $categoria['nombre']], $categoria);
        }

        // Obtener el primer usuario para usar como usuario_creacion
        $usuario = \App\Models\User::first();

        // Crear productos
        $productos = [
            [
                'codigo' => 'POL001',
                'nombre' => 'Pollera Paceña Talla M',
                'descripcion' => 'Pollera tradicional paceña en talla M',
                'talla' => 'M',
                'color' => 'Azul marino',
                'material' => 'Terciopelo',
                'categoria_id' => 1,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
            [
                'codigo' => 'POL002',
                'nombre' => 'Pollera Cochabambina Talla L',
                'descripcion' => 'Pollera tradicional cochabambina en talla L',
                'talla' => 'L',
                'color' => 'Verde',
                'material' => 'Seda',
                'categoria_id' => 1,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
            [
                'codigo' => 'BLU001',
                'nombre' => 'Blusa Bordada Talla S',
                'descripcion' => 'Blusa con bordados tradicionales',
                'talla' => 'S',
                'color' => 'Blanco',
                'material' => 'Algodón',
                'categoria_id' => 2,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
            [
                'codigo' => 'CHA001',
                'nombre' => 'Chaleco Potosino',
                'descripcion' => 'Chaleco tradicional de Potosí',
                'talla' => 'M',
                'color' => 'Negro',
                'material' => 'Lana',
                'categoria_id' => 3,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
            [
                'codigo' => 'SOM001',
                'nombre' => 'Sombrero Borsalino',
                'descripcion' => 'Sombrero borsalino clásico',
                'talla' => 'Único',
                'color' => 'Negro',
                'material' => 'Fieltro',
                'categoria_id' => 4,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
            [
                'codigo' => 'ACC001',
                'nombre' => 'Mantilla Tradicional',
                'descripcion' => 'Mantilla bordada a mano',
                'talla' => 'Único',
                'color' => 'Multicolor',
                'material' => 'Seda',
                'categoria_id' => 5,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
            [
                'codigo' => 'DIS001',
                'nombre' => 'Disfraz Niño Diablada',
                'descripcion' => 'Disfraz de diablo para niño',
                'talla' => '8-10 años',
                'color' => 'Rojo',
                'material' => 'Poliéster',
                'categoria_id' => 6,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
            [
                'codigo' => 'POL003',
                'nombre' => 'Pollera de Gala',
                'descripcion' => 'Pollera especial para eventos',
                'talla' => 'M',
                'color' => 'Dorado',
                'material' => 'Brocado',
                'categoria_id' => 1,
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => $usuario->id,
            ],
        ];

        foreach ($productos as $producto) {
            $productoModel = Producto::firstOrCreate(['codigo' => $producto['codigo']], $producto);

            // Crear stock para cada sucursal
            StockPorSucursal::firstOrCreate(
                [
                    'producto_id' => $productoModel->id,
                    'sucursal_id' => $sucursalPrincipal->id,
                ],
                [
                    'stock_actual' => rand(10, 50),
                    'stock_minimo' => rand(5, 10),
                    'stock_reservado' => rand(0, 5),
                    'stock_alquilado' => rand(0, 8),
                    'stock_vendido' => rand(0, 20),
                    'precio_venta_sucursal' => rand(100, 500),
                    'precio_alquiler_sucursal' => rand(20, 100),
                    'activo' => true,
                ]
            );

            StockPorSucursal::firstOrCreate(
                [
                    'producto_id' => $productoModel->id,
                    'sucursal_id' => $sucursalSantaCruz->id,
                ],
                [
                    'stock_actual' => rand(5, 30),
                    'stock_minimo' => rand(3, 8),
                    'stock_reservado' => rand(0, 3),
                    'stock_alquilado' => rand(0, 5),
                    'stock_vendido' => rand(0, 15),
                    'precio_venta_sucursal' => rand(100, 500),
                    'precio_alquiler_sucursal' => rand(20, 100),
                    'activo' => true,
                ]
            );
        }

        // Crear clientes de prueba
        $clientes = [
            [
                'sucursal_id' => $sucursalPrincipal->id,
                'tipo_cliente' => 'INDIVIDUAL',
                'nombres' => 'María Condori',
                'apellidos' => 'López',
                'telefono' => '70123456',
                'email' => 'maria.condori@gmail.com',
                'direccion' => 'Zona Norte, El Alto',
                'carnet_identidad' => '12345678 LP',
                'activo' => true,
            ],
            [
                'sucursal_id' => $sucursalPrincipal->id,
                'tipo_cliente' => 'INDIVIDUAL',
                'nombres' => 'Juan Carlos',
                'apellidos' => 'Mamani',
                'telefono' => '75987654',
                'email' => 'juan.mamani@gmail.com',
                'direccion' => 'Av. 6 de Agosto, La Paz',
                'carnet_identidad' => '87654321 LP',
                'activo' => true,
            ],
            [
                'sucursal_id' => $sucursalPrincipal->id,
                'tipo_cliente' => 'EMPRESA',
                'nombres' => 'Grupo Folklórico Kantuta',
                'apellidos' => '',
                'telefono' => '71456789',
                'email' => 'kantuta@gmail.com',
                'direccion' => 'Plaza San Francisco, La Paz',
                'carnet_identidad' => '123456789',
                'activo' => true,
            ],
            [
                'sucursal_id' => $sucursalSantaCruz->id,
                'tipo_cliente' => 'INDIVIDUAL',
                'nombres' => 'Ana Patricia',
                'apellidos' => 'Quispe',
                'telefono' => '78654321',
                'email' => 'ana.quispe@hotmail.com',
                'direccion' => 'Zona Sur, La Paz',
                'carnet_identidad' => '56789123 LP',
                'activo' => true,
            ],
            [
                'sucursal_id' => $sucursalSantaCruz->id,
                'tipo_cliente' => 'EMPRESA',
                'nombres' => 'Fraternidad Señor de Mayo',
                'apellidos' => '',
                'telefono' => '72135468',
                'email' => 'senormayo@yahoo.com',
                'direccion' => 'Villa Fátima, La Paz',
                'carnet_identidad' => '987654321',
                'activo' => true,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::firstOrCreate(['carnet_identidad' => $cliente['carnet_identidad']], $cliente);
        }

        // Crear cajas
        Caja::firstOrCreate(
            [
                'nombre' => 'Caja Principal',
                'sucursal_id' => $sucursalPrincipal->id
            ],
            [
                'saldo_inicial' => 1000.00,
                'saldo_actual' => 1500.00,
                'estado' => 'ABIERTA',
                'fecha_apertura' => now(),
            ]
        );

        Caja::firstOrCreate(
            [
                'nombre' => 'Caja Secundaria',
                'sucursal_id' => $sucursalPrincipal->id
            ],
            [
                'saldo_inicial' => 500.00,
                'saldo_actual' => 780.00,
                'estado' => 'ABIERTA',
                'fecha_apertura' => now(),
            ]
        );

        Caja::firstOrCreate(
            [
                'nombre' => 'Caja Santa Cruz',
                'sucursal_id' => $sucursalSantaCruz->id
            ],
            [
                'saldo_inicial' => 800.00,
                'saldo_actual' => 1200.00,
                'estado' => 'CERRADA',
                'fecha_apertura' => now()->subDay(),
                'fecha_cierre' => now()->subHours(2),
            ]
        );

        // Crear tipos de garantía
        $tiposGarantia = [
            ['nombre' => 'Efectivo', 'descripcion' => 'Garantía en efectivo'],
            ['nombre' => 'Cédula de Identidad', 'descripcion' => 'Cédula de identidad como garantía'],
            ['nombre' => 'Licencia de Conducir', 'descripcion' => 'Licencia de conducir como garantía'],
            ['nombre' => 'Depósito Bancario', 'descripcion' => 'Comprobante de depósito bancario'],
        ];

        foreach ($tiposGarantia as $tipo) {
            TipoGarantia::firstOrCreate(['nombre' => $tipo['nombre']], $tipo);
        }

        $this->command->info('Datos de prueba creados exitosamente!');
    }
}