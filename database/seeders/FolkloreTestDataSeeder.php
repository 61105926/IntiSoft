<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\StockPorSucursal;
use App\Models\MovimientoStockSucursal;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FolkloreTestDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🎭 Creando datos de prueba para el sistema folklórico...');

        // 1. Crear Sucursales
        $this->command->info('📍 Creando sucursales...');
        $empresa = \App\Models\Empresa::first();

        $sucursales = [
            [
                'empresa_id' => $empresa->id,
                'nombre' => 'Sucursal Centro',
                'codigo' => 'CENT',
                'direccion' => 'Plaza Murillo #123, La Paz',
                'telefono' => '2-2345678',
                'responsable' => 'María Gonzales',
                'activo' => true,
            ],
            [
                'empresa_id' => $empresa->id,
                'nombre' => 'Sucursal El Alto',
                'codigo' => 'ALTO',
                'direccion' => 'Av. 6 de Marzo #456, El Alto',
                'telefono' => '2-2876543',
                'responsable' => 'Carlos Mamani',
                'activo' => true,
            ],
            [
                'empresa_id' => $empresa->id,
                'nombre' => 'Sucursal Zona Sur',
                'codigo' => 'ZSUR',
                'direccion' => 'Av. Montenegro #789, Zona Sur',
                'telefono' => '2-2987654',
                'responsable' => 'Ana Quispe',
                'activo' => true,
            ]
        ];

        foreach ($sucursales as $sucursal) {
            Sucursal::create($sucursal);
        }

        // 2. Crear usuarios adicionales
        $this->command->info('👥 Creando usuarios...');
        $primeraSuccursal = Sucursal::first();

        $usuarios = [
            [
                'sucursal_id' => $primeraSuccursal->id,
                'username' => 'vendedor1',
                'email' => 'vendedor1@intisoft.com',
                'password' => Hash::make('password'),
                'nombres' => 'María Elena',
                'apellidos' => 'Quispe Mamani',
                'telefono' => '70123456',
                'name' => 'María Elena Quispe Mamani',
                'activo' => true,
            ],
            [
                'sucursal_id' => $primeraSuccursal->id,
                'username' => 'admin2',
                'email' => 'admin2@intisoft.com',
                'password' => Hash::make('password'),
                'nombres' => 'Carlos',
                'apellidos' => 'Choque Condori',
                'telefono' => '70987654',
                'name' => 'Carlos Choque Condori',
                'activo' => true,
            ]
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }

        // 3. Crear Categorías de Productos
        $this->command->info('🏷️ Creando categorías...');
        $categorias = [
            [
                'nombre' => 'Polleras',
                'descripcion' => 'Polleras tradicionales bolivianas',
                'activo' => true,
            ],
            [
                'nombre' => 'Sombreros',
                'descripcion' => 'Sombreros y tocados folklóricos',
                'activo' => true,
            ],
            [
                'nombre' => 'Mantillas',
                'descripcion' => 'Mantillas y rebozos',
                'activo' => true,
            ],
            [
                'nombre' => 'Calzado',
                'descripcion' => 'Calzado tradicional boliviano',
                'activo' => true,
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Accesorios y complementos',
                'activo' => true,
            ]
        ];

        foreach ($categorias as $categoria) {
            CategoriaProducto::create($categoria);
        }

        // 4. Crear Productos
        $this->command->info('📦 Creando productos...');
        $productos = [
            // Polleras
            [
                'categoria_id' => 1,
                'codigo' => 'PLR-001',
                'nombre' => 'Pollera Tradicional Cholita',
                'descripcion' => 'Pollera tradicional de cholita paceña con bordados',
                'talla' => 'M',
                'color' => 'Verde',
                'material' => 'Terciopelo',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
            ],
            [
                'categoria_id' => 1,
                'codigo' => 'PLR-002',
                'nombre' => 'Pollera de Fiesta',
                'descripcion' => 'Pollera elegante para festivales',
                'talla' => 'L',
                'color' => 'Rojo',
                'material' => 'Seda',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
            ],
            // Sombreros
            [
                'categoria_id' => 2,
                'codigo' => 'SOM-001',
                'nombre' => 'Sombrero Bombín Negro',
                'descripcion' => 'Sombrero bombín tradicional de cholita',
                'talla' => 'Único',
                'color' => 'Negro',
                'material' => 'Fieltro',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
            ],
            [
                'categoria_id' => 2,
                'codigo' => 'SOM-002',
                'nombre' => 'Sombrero de Caporales',
                'descripcion' => 'Sombrero para danza de caporales',
                'talla' => 'M',
                'color' => 'Dorado',
                'material' => 'Paño',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
            ],
            // Mantillas
            [
                'categoria_id' => 3,
                'codigo' => 'MAN-001',
                'nombre' => 'Mantilla Bordada',
                'descripcion' => 'Mantilla con bordados tradicionales',
                'talla' => 'Único',
                'color' => 'Blanco',
                'material' => 'Algodón',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
            ],
            // Calzado
            [
                'categoria_id' => 4,
                'codigo' => 'CAL-001',
                'nombre' => 'Zapatos de Cholita',
                'descripcion' => 'Zapatos tradicionales con tacón bajo',
                'talla' => '38',
                'color' => 'Negro',
                'material' => 'Cuero',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
            ],
            // Accesorios
            [
                'categoria_id' => 5,
                'codigo' => 'ACC-001',
                'nombre' => 'Aguayo Tradicional',
                'descripcion' => 'Aguayo boliviano multicolor',
                'talla' => 'Único',
                'color' => 'Multicolor',
                'material' => 'Lana',
                'disponible_venta' => true,
                'disponible_alquiler' => false,
                'usuario_creacion' => 1,
                'activo' => true,
            ],
            [
                'categoria_id' => 5,
                'codigo' => 'ACC-002',
                'nombre' => 'Collar de Perlas',
                'descripcion' => 'Collar tradicional de perlas blancas',
                'talla' => 'Único',
                'color' => 'Blanco',
                'material' => 'Perlas',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        // 5. Crear Stock para cada producto en cada sucursal
        $this->command->info('📊 Creando stock por sucursal...');
        $productos = Producto::all();
        $sucursales = Sucursal::all();

        foreach ($productos as $producto) {
            foreach ($sucursales as $sucursal) {
                $stockActual = rand(0, 50);
                $precioVenta = rand(100, 1000);
                $precioAlquiler = round($precioVenta * 0.3);

                StockPorSucursal::create([
                    'producto_id' => $producto->id,
                    'sucursal_id' => $sucursal->id,
                    'stock_actual' => $stockActual,
                    'stock_minimo' => 5,
                    'precio_venta_sucursal' => $precioVenta,
                    'precio_alquiler_sucursal' => $precioAlquiler,
                ]);
            }
        }

        // 6. Crear Clientes
        $this->command->info('👥 Creando clientes...');
        $clientes = [
            [
                'nombres' => 'Ana María',
                'apellidos' => 'González Pérez',
                'telefono' => '70111222',
                'email' => 'ana.gonzalez@email.com',
                'direccion' => 'Calle Comercio #123, La Paz',
                'ci' => '12345678',
                'activo' => true,
            ],
            [
                'nombres' => 'Luis Fernando',
                'apellidos' => 'Mamani Quispe',
                'telefono' => '70333444',
                'email' => 'luis.mamani@email.com',
                'direccion' => 'Av. Buenos Aires #456, La Paz',
                'ci' => '87654321',
                'activo' => true,
            ],
            [
                'nombres' => 'Patricia',
                'apellidos' => 'Choque Vargas',
                'telefono' => '70555666',
                'email' => 'patricia.choque@email.com',
                'direccion' => 'Plaza San Francisco #789, La Paz',
                'ci' => '11223344',
                'activo' => true,
            ],
            [
                'nombres' => 'Roberto',
                'apellidos' => 'Condori Huanca',
                'telefono' => '70777888',
                'email' => 'roberto.condori@email.com',
                'direccion' => 'Calle Sagárnaga #321, La Paz',
                'ci' => '44332211',
                'activo' => true,
            ]
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }

        // 7. Crear algunos movimientos de stock para el historial
        $this->command->info('📈 Creando historial de movimientos...');
        $movimientos = [
            [
                'producto_id' => 1,
                'sucursal_id' => 1,
                'tipo_movimiento' => 'ENTRADA',
                'cantidad' => 10,
                'stock_anterior' => 0,
                'stock_nuevo' => 10,
                'valor_unitario' => 450.00,
                'referencia' => 'INV-001',
                'motivo' => 'Compra inicial de inventario',
                'observaciones' => 'Producto nuevo en inventario',
                'usuario_id' => 1,
                'fecha_movimiento' => Carbon::now()->subDays(5),
            ],
            [
                'producto_id' => 1,
                'sucursal_id' => 1,
                'tipo_movimiento' => 'VENTA',
                'cantidad' => -2,
                'stock_anterior' => 10,
                'stock_nuevo' => 8,
                'valor_unitario' => 550.00,
                'referencia' => 'VEN-001',
                'motivo' => 'Venta al cliente Ana González',
                'observaciones' => 'Venta normal',
                'usuario_id' => 1,
                'fecha_movimiento' => Carbon::now()->subDays(2),
            ],
            [
                'producto_id' => 2,
                'sucursal_id' => 1,
                'tipo_movimiento' => 'ALQUILER',
                'cantidad' => -1,
                'stock_anterior' => 5,
                'stock_nuevo' => 4,
                'valor_unitario' => 200.00,
                'referencia' => 'ALQ-001',
                'motivo' => 'Alquiler para festival',
                'observaciones' => 'Alquiler por 3 días',
                'usuario_id' => 1,
                'fecha_movimiento' => Carbon::now()->subDays(1),
            ]
        ];

        foreach ($movimientos as $movimiento) {
            MovimientoStockSucursal::create($movimiento);
        }

        $this->command->info('✅ Datos de prueba creados exitosamente!');
        $this->command->info('📊 Resumen:');
        $this->command->info('   - Sucursales: ' . Sucursal::count());
        $this->command->info('   - Usuarios: ' . User::count());
        $this->command->info('   - Categorías: ' . CategoriaProducto::count());
        $this->command->info('   - Productos: ' . Producto::count());
        $this->command->info('   - Clientes: ' . Cliente::count());
        $this->command->info('   - Registros de Stock: ' . StockPorSucursal::count());
        $this->command->info('   - Movimientos: ' . MovimientoStockSucursal::count());
    }
}