<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatosBasicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏗️  Creando datos básicos del sistema...');

        // 1. Crear empresa
        $empresa = Empresa::firstOrCreate(
            ['nit' => '12345678001'],
            [
                'razon_social' => 'Folklore Tradicional S.A.',
                'nit' => '12345678001',
                'direccion' => 'Av. Cultural 123, Centro',
                'telefono' => '+591-2-1234567',
                'email' => 'info@folkloretradicial.com',
            ]
        );

        // 2. Crear sucursal
        $sucursal = Sucursal::firstOrCreate(
            ['codigo' => 'CENTRO'],
            [
                'empresa_id' => $empresa->id,
                'nombre' => 'Sucursal Centro',
                'codigo' => 'CENTRO',
                'direccion' => 'Plaza Principal s/n',
                'telefono' => '+591-2-7654321',
                'responsable' => 'Administrador General',
                'activo' => true,
            ]
        );

        // 3. Crear usuario
        $usuario = User::firstOrCreate(
            ['email' => 'admin@folklore.com'],
            [
                'sucursal_id' => $sucursal->id,
                'username' => 'admin',
                'email' => 'admin@folklore.com',
                'password' => Hash::make('password'),
                'nombres' => 'Administrador',
                'apellidos' => 'Sistema',
                'telefono' => '+591-70123456',
                'activo' => true,
                'email_verified_at' => now(),
            ]
        );

        // 4. Crear categorías de productos
        $categorias = [
            ['nombre' => 'Trajes Femeninos', 'descripcion' => 'Vestimentas tradicionales para mujeres'],
            ['nombre' => 'Trajes Masculinos', 'descripcion' => 'Vestimentas tradicionales para hombres'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Sombreros, joyas y complementos'],
            ['nombre' => 'Calzado', 'descripcion' => 'Zapatos y sandalias tradicionales'],
        ];

        foreach ($categorias as $cat) {
            CategoriaProducto::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }

        $catFemenino = CategoriaProducto::where('nombre', 'Trajes Femeninos')->first();
        $catMasculino = CategoriaProducto::where('nombre', 'Trajes Masculinos')->first();
        $catAccesorios = CategoriaProducto::where('nombre', 'Accesorios')->first();

        // 5. Crear productos
        $productos = [
            [
                'nombre' => 'Pollera Paceña Completa',
                'codigo' => 'PPC-001',
                'descripcion' => 'Pollera tradicional con blusa, manta y accesorios',
                'categoria_id' => $catFemenino->id,
                'usuario_creacion' => $usuario->id,
                'precio_venta' => 450.00,
                'precio_alquiler' => 80.00,
            ],
            [
                'nombre' => 'Traje de Caporales Mujer',
                'codigo' => 'TCM-001',
                'descripcion' => 'Vestimenta completa para danza de caporales femenino',
                'categoria_id' => $catFemenino->id,
                'usuario_creacion' => $usuario->id,
                'precio_venta' => 380.00,
                'precio_alquiler' => 65.00,
            ],
            [
                'nombre' => 'Traje de Morenada',
                'codigo' => 'TM-001',
                'descripcion' => 'Traje tradicional de morenada con máscara incluida',
                'categoria_id' => $catMasculino->id,
                'usuario_creacion' => $usuario->id,
                'precio_venta' => 520.00,
                'precio_alquiler' => 90.00,
            ],
            [
                'nombre' => 'Traje de Caporales Hombre',
                'codigo' => 'TCH-001',
                'descripcion' => 'Vestimenta completa para danza de caporales masculino',
                'categoria_id' => $catMasculino->id,
                'usuario_creacion' => $usuario->id,
                'precio_venta' => 350.00,
                'precio_alquiler' => 60.00,
            ],
            [
                'nombre' => 'Sombrero Borsalino',
                'codigo' => 'SB-001',
                'descripcion' => 'Sombrero tradicional de alta calidad',
                'categoria_id' => $catAccesorios->id,
                'usuario_creacion' => $usuario->id,
                'precio_venta' => 120.00,
                'precio_alquiler' => 15.00,
            ],
            [
                'nombre' => 'Traje de Diablada',
                'codigo' => 'TD-001',
                'descripcion' => 'Vestimenta completa de diablada con máscara elaborada',
                'categoria_id' => $catMasculino->id,
                'usuario_creacion' => $usuario->id,
                'precio_venta' => 680.00,
                'precio_alquiler' => 120.00,
            ],
        ];

        foreach ($productos as $prod) {
            $precioVenta = $prod['precio_venta'];
            $precioAlquiler = $prod['precio_alquiler'];
            unset($prod['precio_venta'], $prod['precio_alquiler']);
            
            $producto = Producto::firstOrCreate(['codigo' => $prod['codigo']], $prod);
            
            // Crear stock en sucursal con precios
            \App\Models\StockPorSucursal::firstOrCreate([
                'sucursal_id' => $sucursal->id,
                'producto_id' => $producto->id,
            ], [
                'stock_actual' => 10,
                'stock_minimo' => 2,
                'stock_reservado' => 0,
                'stock_alquilado' => 0,
                'stock_vendido' => 0,
                'precio_venta_sucursal' => $precioVenta,
                'precio_alquiler_sucursal' => $precioAlquiler,
                'activo' => true,
            ]);
        }

        // 6. Crear clientes
        $clientes = [
            [
                'sucursal_id' => $sucursal->id,
                'nombres' => 'María Elena',
                'apellidos' => 'Condori Mamani',
                'email' => 'maria.condori@email.com',
                'telefono' => '+591-71234567',
                'carnet_identidad' => '12345678',
                'direccion' => 'Zona Sur, Calle Los Rosales 456',
                'tipo_cliente' => 'INDIVIDUAL',
                'activo' => true,
            ],
            [
                'sucursal_id' => $sucursal->id,
                'nombres' => 'Carlos Alberto',
                'apellidos' => 'Quispe López',
                'email' => 'carlos.quispe@email.com',
                'telefono' => '+591-72345678',
                'carnet_identidad' => '87654321',
                'direccion' => 'Av. Buenos Aires 789',
                'tipo_cliente' => 'INDIVIDUAL',
                'activo' => true,
            ],
            [
                'sucursal_id' => $sucursal->id,
                'nombres' => 'Ana Lucia',
                'apellidos' => 'Vargas Ticona',
                'email' => 'ana.vargas@email.com',
                'telefono' => '+591-73456789',
                'carnet_identidad' => '13579246',
                'direccion' => 'Plaza Murillo, Edificio Central 101',
                'tipo_cliente' => 'INDIVIDUAL',
                'activo' => true,
            ]
        ];

        foreach ($clientes as $cliente) {
            Cliente::firstOrCreate(['carnet_identidad' => $cliente['carnet_identidad']], $cliente);
        }

        $this->command->info('✅ Datos básicos creados:');
        $this->command->info('   🏢 1 Empresa + 1 Sucursal');
        $this->command->info('   👤 1 Usuario administrador');
        $this->command->info('   📦 6 Productos folklóricos');
        $this->command->info('   👥 3 Clientes');
        $this->command->info('   🏷️ 4 Categorías de productos');
        $this->command->info('   📧 Email: admin@folklore.com | Password: password');
    }
}
