<?php

namespace Database\Seeders;

use App\Models\CategoriaConjunto;
use App\Models\Conjunto;
use App\Models\VariacionConjunto;
use App\Models\TipoComponente;
use App\Models\Componente;
use App\Models\ConjuntoComponente;
use App\Models\InstanciaConjunto;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class FolkloreConjuntosSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎭 Creando sistema folklórico completo...');

        // Obtener sucursal principal
        $sucursal = Sucursal::first();
        if (!$sucursal) {
            $this->command->error('No hay sucursales disponibles');
            return;
        }

        // 1. Crear categorías de conjuntos folklóricos
        $this->createCategorias();

        // 2. Crear tipos de componentes
        $this->createTiposComponentes();

        // 3. Crear componentes individuales
        $this->createComponentes();

        // 4. Crear conjuntos folklóricos
        $this->createConjuntos();

        // 5. Crear variaciones de conjuntos
        $this->createVariaciones();

        // 6. Asociar componentes a conjuntos
        $this->associateComponentesToConjuntos();

        // 7. Crear instancias físicas en inventario
        $this->createInstancias($sucursal);

        $this->command->info('✅ Sistema folklórico creado exitosamente');
    }

    private function createCategorias()
    {
        $categorias = [
            [
                'nombre' => 'Danzas Tradicionales',
                'descripcion' => 'Trajes para danzas folklóricas tradicionales bolivianas',
                'activo' => true,
            ],
            [
                'nombre' => 'Carnaval',
                'descripcion' => 'Trajes de carnaval y festividades',
                'activo' => true,
            ],
            [
                'nombre' => 'Danzas Regionales',
                'descripcion' => 'Trajes específicos de regiones de Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Danzas Modernas',
                'descripcion' => 'Adaptaciones modernas de danzas folklóricas',
                'activo' => true,
            ],
            [
                'nombre' => 'Infantil',
                'descripcion' => 'Trajes folklóricos para niños',
                'activo' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            CategoriaConjunto::create($categoria);
        }

        $this->command->info('   ✅ Categorías de conjuntos creadas');
    }

    private function createTiposComponentes()
    {
        $tipos = [
            ['nombre' => 'Pollera', 'descripcion' => 'Falda tradicional'],
            ['nombre' => 'Blusa', 'descripcion' => 'Blusa o camisa'],
            ['nombre' => 'Mantilla', 'descripcion' => 'Mantilla o chal'],
            ['nombre' => 'Sombrero', 'descripcion' => 'Sombreros tradicionales'],
            ['nombre' => 'Pantalón', 'descripcion' => 'Pantalones masculinos'],
            ['nombre' => 'Camisa', 'descripcion' => 'Camisas masculinas'],
            ['nombre' => 'Poncho', 'descripcion' => 'Ponchos tradicionales'],
            ['nombre' => 'Calzado', 'descripcion' => 'Zapatos y abarcas'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Complementos y adornos'],
            ['nombre' => 'Chaleco', 'descripcion' => 'Chalecos y vests'],
        ];

        foreach ($tipos as $tipo) {
            TipoComponente::create($tipo);
        }

        $this->command->info('   ✅ Tipos de componentes creados');
    }

    private function createComponentes()
    {
        $componentes = [
            // Componentes femeninos
            ['codigo' => 'COMP-001', 'nombre' => 'Pollera de Cholita Paceña', 'tipo_componente_id' => 1, 'genero' => 'FEMENINO'],
            ['codigo' => 'COMP-002', 'nombre' => 'Blusa de Cholita', 'tipo_componente_id' => 2, 'genero' => 'FEMENINO'],
            ['codigo' => 'COMP-003', 'nombre' => 'Mantilla Bordada', 'tipo_componente_id' => 3, 'genero' => 'FEMENINO'],
            ['codigo' => 'COMP-004', 'nombre' => 'Bombín Negro', 'tipo_componente_id' => 4, 'genero' => 'FEMENINO'],
            ['codigo' => 'COMP-005', 'nombre' => 'Pollera de Caporales', 'tipo_componente_id' => 1, 'genero' => 'FEMENINO'],
            ['codigo' => 'COMP-006', 'nombre' => 'Blusa de Caporales', 'tipo_componente_id' => 2, 'genero' => 'FEMENINO'],
            ['codigo' => 'COMP-007', 'nombre' => 'Pollera de Tinku', 'tipo_componente_id' => 1, 'genero' => 'FEMENINO'],
            ['codigo' => 'COMP-008', 'nombre' => 'Blusa de Tinku', 'tipo_componente_id' => 2, 'genero' => 'FEMENINO'],

            // Componentes masculinos
            ['codigo' => 'COMP-009', 'nombre' => 'Pantalón de Caporal', 'tipo_componente_id' => 5, 'genero' => 'MASCULINO'],
            ['codigo' => 'COMP-010', 'nombre' => 'Camisa de Caporal', 'tipo_componente_id' => 6, 'genero' => 'MASCULINO'],
            ['codigo' => 'COMP-011', 'nombre' => 'Chaleco de Caporal', 'tipo_componente_id' => 10, 'genero' => 'MASCULINO'],
            ['codigo' => 'COMP-012', 'nombre' => 'Pantalón de Tinku', 'tipo_componente_id' => 5, 'genero' => 'MASCULINO'],
            ['codigo' => 'COMP-013', 'nombre' => 'Camisa de Tinku', 'tipo_componente_id' => 6, 'genero' => 'MASCULINO'],
            ['codigo' => 'COMP-014', 'nombre' => 'Poncho Andino', 'tipo_componente_id' => 7, 'genero' => 'MASCULINO'],

            // Accesorios unisex
            ['codigo' => 'COMP-015', 'nombre' => 'Abarcas Tradicionales', 'tipo_componente_id' => 8, 'genero' => 'UNISEX'],
            ['codigo' => 'COMP-016', 'nombre' => 'Cascabeles', 'tipo_componente_id' => 9, 'genero' => 'UNISEX'],
            ['codigo' => 'COMP-017', 'nombre' => 'Whipala Pequeña', 'tipo_componente_id' => 9, 'genero' => 'UNISEX'],
            ['codigo' => 'COMP-018', 'nombre' => 'Cinturón Bordado', 'tipo_componente_id' => 9, 'genero' => 'UNISEX'],
        ];

        foreach ($componentes as $componente) {
            Componente::create($componente);
        }

        $this->command->info('   ✅ Componentes creados');
    }

    private function createConjuntos()
    {
        $conjuntos = [
            [
                'codigo' => 'CONJ-001',
                'nombre' => 'Cholita Paceña Completa',
                'descripcion' => 'Traje completo de cholita paceña tradicional',
                'categoria_conjunto_id' => 1,
                'genero' => 'FEMENINO',
                'precio_alquiler_dia' => 150.00,
                'precio_venta_base' => 800.00,
                'activo' => true,
            ],
            [
                'codigo' => 'CONJ-002',
                'nombre' => 'Caporal Masculino',
                'descripcion' => 'Traje completo de caporal para hombre',
                'categoria_conjunto_id' => 1,
                'genero' => 'MASCULINO',
                'precio_alquiler_dia' => 120.00,
                'precio_venta_base' => 650.00,
                'activo' => true,
            ],
            [
                'codigo' => 'CONJ-003',
                'nombre' => 'Caporal Femenino',
                'descripcion' => 'Traje completo de caporal para mujer',
                'categoria_conjunto_id' => 1,
                'genero' => 'FEMENINO',
                'precio_alquiler_dia' => 130.00,
                'precio_venta_base' => 700.00,
                'activo' => true,
            ],
            [
                'codigo' => 'CONJ-004',
                'nombre' => 'Tinku Masculino',
                'descripcion' => 'Traje de tinku tradicional para hombre',
                'categoria_conjunto_id' => 3,
                'genero' => 'MASCULINO',
                'precio_alquiler_dia' => 100.00,
                'precio_venta_base' => 500.00,
                'activo' => true,
            ],
            [
                'codigo' => 'CONJ-005',
                'nombre' => 'Tinku Femenino',
                'descripcion' => 'Traje de tinku tradicional para mujer',
                'categoria_conjunto_id' => 3,
                'genero' => 'FEMENINO',
                'precio_alquiler_dia' => 110.00,
                'precio_venta_base' => 550.00,
                'activo' => true,
            ],
            [
                'codigo' => 'CONJ-006',
                'nombre' => 'Cholita Infantil',
                'descripcion' => 'Traje de cholita para niñas',
                'categoria_conjunto_id' => 5,
                'genero' => 'INFANTIL',
                'precio_alquiler_dia' => 80.00,
                'precio_venta_base' => 400.00,
                'activo' => true,
            ],
        ];

        foreach ($conjuntos as $conjunto) {
            Conjunto::create($conjunto);
        }

        $this->command->info('   ✅ Conjuntos folklóricos creados');
    }

    private function createVariaciones()
    {
        $variaciones = [
            // Cholita Paceña variaciones
            ['conjunto_id' => 1, 'codigo_variacion' => 'VAR-001', 'nombre_variacion' => 'Cholita Paceña - Rojo', 'color' => 'Rojo', 'talla' => 'S'],
            ['conjunto_id' => 1, 'codigo_variacion' => 'VAR-002', 'nombre_variacion' => 'Cholita Paceña - Rojo', 'color' => 'Rojo', 'talla' => 'M'],
            ['conjunto_id' => 1, 'codigo_variacion' => 'VAR-003', 'nombre_variacion' => 'Cholita Paceña - Rojo', 'color' => 'Rojo', 'talla' => 'L'],
            ['conjunto_id' => 1, 'codigo_variacion' => 'VAR-004', 'nombre_variacion' => 'Cholita Paceña - Verde', 'color' => 'Verde', 'talla' => 'S'],
            ['conjunto_id' => 1, 'codigo_variacion' => 'VAR-005', 'nombre_variacion' => 'Cholita Paceña - Verde', 'color' => 'Verde', 'talla' => 'M'],
            ['conjunto_id' => 1, 'codigo_variacion' => 'VAR-006', 'nombre_variacion' => 'Cholita Paceña - Azul', 'color' => 'Azul', 'talla' => 'M'],

            // Caporal Masculino variaciones
            ['conjunto_id' => 2, 'codigo_variacion' => 'VAR-007', 'nombre_variacion' => 'Caporal Masc. - Dorado', 'color' => 'Dorado', 'talla' => 'M'],
            ['conjunto_id' => 2, 'codigo_variacion' => 'VAR-008', 'nombre_variacion' => 'Caporal Masc. - Dorado', 'color' => 'Dorado', 'talla' => 'L'],
            ['conjunto_id' => 2, 'codigo_variacion' => 'VAR-009', 'nombre_variacion' => 'Caporal Masc. - Plateado', 'color' => 'Plateado', 'talla' => 'M'],

            // Caporal Femenino variaciones
            ['conjunto_id' => 3, 'codigo_variacion' => 'VAR-010', 'nombre_variacion' => 'Caporal Fem. - Dorado', 'color' => 'Dorado', 'talla' => 'S'],
            ['conjunto_id' => 3, 'codigo_variacion' => 'VAR-011', 'nombre_variacion' => 'Caporal Fem. - Dorado', 'color' => 'Dorado', 'talla' => 'M'],
            ['conjunto_id' => 3, 'codigo_variacion' => 'VAR-012', 'nombre_variacion' => 'Caporal Fem. - Plateado', 'color' => 'Plateado', 'talla' => 'M'],

            // Tinku variaciones
            ['conjunto_id' => 4, 'codigo_variacion' => 'VAR-013', 'nombre_variacion' => 'Tinku Masc. - Tradicional', 'color' => 'Multicolor', 'talla' => 'M'],
            ['conjunto_id' => 4, 'codigo_variacion' => 'VAR-014', 'nombre_variacion' => 'Tinku Masc. - Tradicional', 'color' => 'Multicolor', 'talla' => 'L'],
            ['conjunto_id' => 5, 'codigo_variacion' => 'VAR-015', 'nombre_variacion' => 'Tinku Fem. - Tradicional', 'color' => 'Multicolor', 'talla' => 'S'],
            ['conjunto_id' => 5, 'codigo_variacion' => 'VAR-016', 'nombre_variacion' => 'Tinku Fem. - Tradicional', 'color' => 'Multicolor', 'talla' => 'M'],

            // Cholita Infantil
            ['conjunto_id' => 6, 'codigo_variacion' => 'VAR-017', 'nombre_variacion' => 'Cholita Infantil - Rosa', 'color' => 'Rosa', 'talla' => 'XS'],
            ['conjunto_id' => 6, 'codigo_variacion' => 'VAR-018', 'nombre_variacion' => 'Cholita Infantil - Celeste', 'color' => 'Celeste', 'talla' => 'XS'],
        ];

        foreach ($variaciones as $variacion) {
            VariacionConjunto::create($variacion);
        }

        $this->command->info('   ✅ Variaciones de conjuntos creadas');
    }

    private function associateComponentesToConjuntos()
    {
        // Cholita Paceña (conjunto_id: 1)
        $cholita_componentes = [
            ['conjunto_id' => 1, 'componente_id' => 1, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Pollera de Cholita Paceña
            ['conjunto_id' => 1, 'componente_id' => 2, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Blusa de Cholita
            ['conjunto_id' => 1, 'componente_id' => 3, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Mantilla Bordada
            ['conjunto_id' => 1, 'componente_id' => 4, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Bombín Negro
            ['conjunto_id' => 1, 'componente_id' => 15, 'cantidad_requerida' => 1, 'es_obligatorio' => false], // Abarcas Tradicionales
        ];

        // Caporal Masculino (conjunto_id: 2)
        $caporal_masc_componentes = [
            ['conjunto_id' => 2, 'componente_id' => 9, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Pantalón de Caporal
            ['conjunto_id' => 2, 'componente_id' => 10, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Camisa de Caporal
            ['conjunto_id' => 2, 'componente_id' => 11, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Chaleco de Caporal
            ['conjunto_id' => 2, 'componente_id' => 15, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Abarcas Tradicionales
            ['conjunto_id' => 2, 'componente_id' => 16, 'cantidad_requerida' => 2, 'es_obligatorio' => false], // Cascabeles
        ];

        // Caporal Femenino (conjunto_id: 3)
        $caporal_fem_componentes = [
            ['conjunto_id' => 3, 'componente_id' => 5, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Pollera de Caporales
            ['conjunto_id' => 3, 'componente_id' => 6, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Blusa de Caporales
            ['conjunto_id' => 3, 'componente_id' => 15, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Abarcas Tradicionales
            ['conjunto_id' => 3, 'componente_id' => 16, 'cantidad_requerida' => 2, 'es_obligatorio' => false], // Cascabeles
        ];

        // Tinku Masculino (conjunto_id: 4)
        $tinku_masc_componentes = [
            ['conjunto_id' => 4, 'componente_id' => 12, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Pantalón de Tinku
            ['conjunto_id' => 4, 'componente_id' => 13, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Camisa de Tinku
            ['conjunto_id' => 4, 'componente_id' => 14, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Poncho Andino
            ['conjunto_id' => 4, 'componente_id' => 15, 'cantidad_requerida' => 1, 'es_obligatorio' => false], // Abarcas Tradicionales
        ];

        // Tinku Femenino (conjunto_id: 5)
        $tinku_fem_componentes = [
            ['conjunto_id' => 5, 'componente_id' => 7, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Pollera de Tinku
            ['conjunto_id' => 5, 'componente_id' => 8, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Blusa de Tinku
            ['conjunto_id' => 5, 'componente_id' => 14, 'cantidad_requerida' => 1, 'es_obligatorio' => true], // Poncho Andino
            ['conjunto_id' => 5, 'componente_id' => 15, 'cantidad_requerida' => 1, 'es_obligatorio' => false], // Abarcas Tradicionales
        ];

        // Cholita Infantil (conjunto_id: 6)
        $cholita_infantil_componentes = [
            ['conjunto_id' => 6, 'componente_id' => 1, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Pollera de Cholita Paceña
            ['conjunto_id' => 6, 'componente_id' => 2, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Blusa de Cholita
            ['conjunto_id' => 6, 'componente_id' => 3, 'cantidad_requerida' => 1, 'es_obligatorio' => false], // Mantilla Bordada
            ['conjunto_id' => 6, 'componente_id' => 4, 'cantidad_requerida' => 1, 'es_obligatorio' => true],  // Bombín Negro
            ['conjunto_id' => 6, 'componente_id' => 15, 'cantidad_requerida' => 1, 'es_obligatorio' => false], // Abarcas Tradicionales
        ];

        $all_components = array_merge(
            $cholita_componentes,
            $caporal_masc_componentes,
            $caporal_fem_componentes,
            $tinku_masc_componentes,
            $tinku_fem_componentes,
            $cholita_infantil_componentes
        );

        foreach ($all_components as $component) {
            ConjuntoComponente::create($component);
        }

        $this->command->info('   ✅ Componentes asociados a conjuntos');
    }

    private function createInstancias($sucursal)
    {
        $instancias = [
            // Cholita Paceña instancias
            ['variacion_conjunto_id' => 1, 'numero_serie' => 'CHOL-R-S-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 1, 'numero_serie' => 'CHOL-R-S-002', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 2, 'numero_serie' => 'CHOL-R-M-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 2, 'numero_serie' => 'CHOL-R-M-002', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 3, 'numero_serie' => 'CHOL-R-L-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],

            // Caporal Masculino instancias
            ['variacion_conjunto_id' => 7, 'numero_serie' => 'CAP-M-D-M-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 7, 'numero_serie' => 'CAP-M-D-M-002', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 8, 'numero_serie' => 'CAP-M-D-L-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],

            // Caporal Femenino instancias
            ['variacion_conjunto_id' => 10, 'numero_serie' => 'CAP-F-D-S-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 11, 'numero_serie' => 'CAP-F-D-M-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 11, 'numero_serie' => 'CAP-F-D-M-002', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],

            // Cholita Infantil
            ['variacion_conjunto_id' => 17, 'numero_serie' => 'CHOL-INF-R-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
            ['variacion_conjunto_id' => 18, 'numero_serie' => 'CHOL-INF-C-001', 'sucursal_id' => $sucursal->id, 'estado_disponibilidad' => 'DISPONIBLE'],
        ];

        foreach ($instancias as $instancia) {
            InstanciaConjunto::create($instancia);
        }

        $this->command->info('   ✅ Instancias físicas creadas en inventario');
        $this->command->info("   📦 Total: " . count($instancias) . " trajes folklóricos disponibles");
    }
}