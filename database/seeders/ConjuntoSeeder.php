<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConjuntoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert categorías de conjunto
        \DB::table('categorias_conjunto')->insert([
            ['nombre' => 'TRAJES_FOLKLORICOS', 'descripcion' => 'Trajes folklóricos completos tradicionales', 'codigo' => 'TF', 'icono' => 'costume', 'orden_visualizacion' => 1],
            ['nombre' => 'CONJUNTOS_DANZA', 'descripcion' => 'Conjuntos especializados para danzas específicas', 'codigo' => 'CD', 'icono' => 'dance', 'orden_visualizacion' => 2],
            ['nombre' => 'CONJUNTOS_ENSAYO', 'descripcion' => 'Conjuntos básicos para ensayos y práctica', 'codigo' => 'CE', 'icono' => 'practice', 'orden_visualizacion' => 3],
            ['nombre' => 'CONJUNTOS_INFANTILES', 'descripcion' => 'Conjuntos diseñados para niños', 'codigo' => 'CI', 'icono' => 'child', 'orden_visualizacion' => 4],
            ['nombre' => 'CONJUNTOS_PREMIUM', 'descripcion' => 'Conjuntos de alta gama y lujo', 'codigo' => 'CP', 'icono' => 'premium', 'orden_visualizacion' => 5],
        ]);

        // Insert tipos de componente
        \DB::table('tipos_componente')->insert([
            ['nombre' => 'CALZADO', 'descripcion' => 'Zapatos, botas y calzado especializado', 'codigo' => 'CAL', 'icono' => 'shoe', 'es_obligatorio_defecto' => true, 'orden_visualizacion' => 1],
            ['nombre' => 'PANTALON_FALDA', 'descripcion' => 'Pantalones, faldas y partes inferiores', 'codigo' => 'PAN', 'icono' => 'pants', 'es_obligatorio_defecto' => true, 'orden_visualizacion' => 2],
            ['nombre' => 'CAMISA_BLUSA', 'descripcion' => 'Camisas, blusas y partes superiores', 'codigo' => 'CAM', 'icono' => 'shirt', 'es_obligatorio_defecto' => true, 'orden_visualizacion' => 3],
            ['nombre' => 'FAJA_CINTURON', 'descripcion' => 'Fajas, cinturones y accesorios de cintura', 'codigo' => 'FAJ', 'icono' => 'belt', 'es_obligatorio_defecto' => true, 'orden_visualizacion' => 4],
            ['nombre' => 'SOMBRERO_TOCADO', 'descripcion' => 'Sombreros, tocados y accesorios de cabeza', 'codigo' => 'SOM', 'icono' => 'hat', 'es_obligatorio_defecto' => true, 'orden_visualizacion' => 5],
            ['nombre' => 'MASCARA', 'descripcion' => 'Máscaras y elementos de caracterización', 'codigo' => 'MAS', 'icono' => 'mask', 'es_obligatorio_defecto' => false, 'orden_visualizacion' => 6],
            ['nombre' => 'JOYAS_BISUTERIA', 'descripcion' => 'Joyas, collares y bisutería', 'codigo' => 'JOY', 'icono' => 'jewelry', 'es_obligatorio_defecto' => false, 'orden_visualizacion' => 7],
            ['nombre' => 'ACCESORIOS_DANZA', 'descripcion' => 'Cascabeles, chicotes y accesorios de danza', 'codigo' => 'ACD', 'icono' => 'accessories', 'es_obligatorio_defecto' => false, 'orden_visualizacion' => 8],
        ]);

        // Insert conjuntos de ejemplo
        \DB::table('conjuntos')->insert([
            [
                'categoria_conjunto_id' => 1,
                'codigo' => 'TF-CAP-M-001',
                'nombre' => 'Traje Caporales Masculino Tradicional',
                'descripcion' => 'Traje completo de caporales para hombre con todos los accesorios tradicionales',
                'precio_venta_base' => 2500.00,
                'precio_alquiler_dia' => 200.00,
                'precio_alquiler_semana' => 1200.00,
                'precio_alquiler_mes' => 4000.00,
                'genero' => 'MASCULINO',
                'usuario_creacion' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'categoria_conjunto_id' => 1,
                'codigo' => 'TF-CAP-F-001',
                'nombre' => 'Traje Caporales Femenino Tradicional',
                'descripcion' => 'Traje completo de caporales para mujer con todos los accesorios',
                'precio_venta_base' => 2800.00,
                'precio_alquiler_dia' => 220.00,
                'precio_alquiler_semana' => 1320.00,
                'precio_alquiler_mes' => 4400.00,
                'genero' => 'FEMENINO',
                'usuario_creacion' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'categoria_conjunto_id' => 1,
                'codigo' => 'TF-MOR-M-001',
                'nombre' => 'Traje Morenada Masculino Premium',
                'descripcion' => 'Traje completo de morenada con máscara y accesorios premium',
                'precio_venta_base' => 3500.00,
                'precio_alquiler_dia' => 300.00,
                'precio_alquiler_semana' => 1800.00,
                'precio_alquiler_mes' => 6000.00,
                'genero' => 'MASCULINO',
                'usuario_creacion' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert variaciones de conjunto
        \DB::table('variaciones_conjunto')->insert([
            // Caporales Masculino
            ['conjunto_id' => 1, 'codigo_variacion' => 'TF-CAP-M-001-S-DOR-CLA', 'nombre_variacion' => 'Caporales Masculino S Dorado Clásico', 'talla' => 'S', 'color' => 'Dorado', 'estilo' => 'Clásico', 'precio_venta' => 2500.00, 'precio_alquiler_dia' => 200.00, 'precio_alquiler_semana' => 1200.00, 'precio_alquiler_mes' => 4000.00, 'created_at' => now(), 'updated_at' => now()],
            ['conjunto_id' => 1, 'codigo_variacion' => 'TF-CAP-M-001-M-DOR-CLA', 'nombre_variacion' => 'Caporales Masculino M Dorado Clásico', 'talla' => 'M', 'color' => 'Dorado', 'estilo' => 'Clásico', 'precio_venta' => 2500.00, 'precio_alquiler_dia' => 200.00, 'precio_alquiler_semana' => 1200.00, 'precio_alquiler_mes' => 4000.00, 'created_at' => now(), 'updated_at' => now()],
            ['conjunto_id' => 1, 'codigo_variacion' => 'TF-CAP-M-001-L-DOR-CLA', 'nombre_variacion' => 'Caporales Masculino L Dorado Clásico', 'talla' => 'L', 'color' => 'Dorado', 'estilo' => 'Clásico', 'precio_venta' => 2500.00, 'precio_alquiler_dia' => 200.00, 'precio_alquiler_semana' => 1200.00, 'precio_alquiler_mes' => 4000.00, 'created_at' => now(), 'updated_at' => now()],

            // Caporales Femenino
            ['conjunto_id' => 2, 'codigo_variacion' => 'TF-CAP-F-001-XS-DOR-CLA', 'nombre_variacion' => 'Caporales Femenino XS Dorado Clásico', 'talla' => 'XS', 'color' => 'Dorado', 'estilo' => 'Clásico', 'precio_venta' => 2800.00, 'precio_alquiler_dia' => 220.00, 'precio_alquiler_semana' => 1320.00, 'precio_alquiler_mes' => 4400.00, 'created_at' => now(), 'updated_at' => now()],
            ['conjunto_id' => 2, 'codigo_variacion' => 'TF-CAP-F-001-S-DOR-CLA', 'nombre_variacion' => 'Caporales Femenino S Dorado Clásico', 'talla' => 'S', 'color' => 'Dorado', 'estilo' => 'Clásico', 'precio_venta' => 2800.00, 'precio_alquiler_dia' => 220.00, 'precio_alquiler_semana' => 1320.00, 'precio_alquiler_mes' => 4400.00, 'created_at' => now(), 'updated_at' => now()],
            ['conjunto_id' => 2, 'codigo_variacion' => 'TF-CAP-F-001-M-DOR-CLA', 'nombre_variacion' => 'Caporales Femenino M Dorado Clásico', 'talla' => 'M', 'color' => 'Dorado', 'estilo' => 'Clásico', 'precio_venta' => 2800.00, 'precio_alquiler_dia' => 220.00, 'precio_alquiler_semana' => 1320.00, 'precio_alquiler_mes' => 4400.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert componentes
        \DB::table('componentes')->insert([
            // Calzado
            ['tipo_componente_id' => 1, 'codigo' => 'CAL-BOT-CAP-M-42-NEG', 'nombre' => 'Botas Caporales Masculinas T42 Negro', 'descripcion' => 'Botas altas de cuero negro para caporales masculino talla 42', 'talla' => '42', 'color' => 'Negro', 'genero' => 'MASCULINO', 'precio_venta_individual' => 650.00, 'precio_alquiler_individual' => 50.00, 'costo_unitario' => 390.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_componente_id' => 1, 'codigo' => 'CAL-BOT-CAP-F-38-NEG', 'nombre' => 'Botas Caporales Femeninas T38 Negro', 'descripcion' => 'Botas altas de cuero negro para caporales femenino talla 38', 'talla' => '38', 'color' => 'Negro', 'genero' => 'FEMENINO', 'precio_venta_individual' => 680.00, 'precio_alquiler_individual' => 55.00, 'costo_unitario' => 408.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Pantalones
            ['tipo_componente_id' => 2, 'codigo' => 'PAN-CAP-M-S-DOR', 'nombre' => 'Pantalón Caporales Masculino S Dorado', 'descripcion' => 'Pantalón de satén bordado dorado talla S', 'talla' => 'S', 'color' => 'Dorado', 'genero' => 'MASCULINO', 'precio_venta_individual' => 450.00, 'precio_alquiler_individual' => 35.00, 'costo_unitario' => 270.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Camisas/Blusas
            ['tipo_componente_id' => 3, 'codigo' => 'CAM-CAP-M-S-DOR', 'nombre' => 'Camisa Caporales Masculina S Dorado', 'descripcion' => 'Camisa bordada dorada talla S', 'talla' => 'S', 'color' => 'Dorado', 'genero' => 'MASCULINO', 'precio_venta_individual' => 380.00, 'precio_alquiler_individual' => 30.00, 'costo_unitario' => 228.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_componente_id' => 3, 'codigo' => 'BLU-CAP-F-XS-DOR', 'nombre' => 'Blusa Caporales Femenina XS Dorado', 'descripcion' => 'Blusa bordada dorada talla XS', 'talla' => 'XS', 'color' => 'Dorado', 'genero' => 'FEMENINO', 'precio_venta_individual' => 420.00, 'precio_alquiler_individual' => 35.00, 'costo_unitario' => 252.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Fajas
            ['tipo_componente_id' => 4, 'codigo' => 'FAJ-CAP-UNI-DOR', 'nombre' => 'Faja Caporales Universal Dorado', 'descripcion' => 'Faja bordada ajustable dorada', 'talla' => 'UNICO', 'color' => 'Dorado', 'genero' => 'UNISEX', 'precio_venta_individual' => 220.00, 'precio_alquiler_individual' => 18.00, 'costo_unitario' => 132.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Sombreros
            ['tipo_componente_id' => 5, 'codigo' => 'SOM-CAP-M-NEG', 'nombre' => 'Sombrero Caporales Masculino Negro', 'descripcion' => 'Sombrero de fieltro negro para caporales masculino', 'talla' => 'UNICO', 'color' => 'Negro', 'genero' => 'MASCULINO', 'precio_venta_individual' => 320.00, 'precio_alquiler_individual' => 25.00, 'costo_unitario' => 192.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_componente_id' => 5, 'codigo' => 'SOM-CAP-F-NEG', 'nombre' => 'Sombrero Caporales Femenino Negro', 'descripcion' => 'Sombrero de fieltro negro con adornos para caporales femenino', 'talla' => 'UNICO', 'color' => 'Negro', 'genero' => 'FEMENINO', 'precio_venta_individual' => 350.00, 'precio_alquiler_individual' => 28.00, 'costo_unitario' => 210.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Accesorios de danza
            ['tipo_componente_id' => 8, 'codigo' => 'ACD-CAS-CAP-MET', 'nombre' => 'Cascabeles Caporales Metálicos', 'descripcion' => 'Set de cascabeles metálicos para caporales', 'talla' => 'UNICO', 'color' => 'Plateado', 'genero' => 'UNISEX', 'precio_venta_individual' => 280.00, 'precio_alquiler_individual' => 22.00, 'costo_unitario' => 168.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_componente_id' => 8, 'codigo' => 'ACD-CHI-CAP-CUE', 'nombre' => 'Chicote Caporales Cuero', 'descripcion' => 'Chicote de cuero trenzado para caporales', 'talla' => 'UNICO', 'color' => 'Marrón', 'genero' => 'UNISEX', 'precio_venta_individual' => 150.00, 'precio_alquiler_individual' => 12.00, 'costo_unitario' => 90.00, 'usuario_creacion' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert relaciones conjunto-componente (Caporales Masculino)
        \DB::table('conjunto_componentes')->insert([
            // Traje Caporales Masculino (conjunto_id = 1)
            ['conjunto_id' => 1, 'componente_id' => 1, 'cantidad_requerida' => 1, 'es_obligatorio' => true, 'orden_ensamblaje' => 1, 'created_at' => now(), 'updated_at' => now()],   // Botas
            ['conjunto_id' => 1, 'componente_id' => 3, 'cantidad_requerida' => 1, 'es_obligatorio' => true, 'orden_ensamblaje' => 2, 'created_at' => now(), 'updated_at' => now()],   // Pantalón
            ['conjunto_id' => 1, 'componente_id' => 4, 'cantidad_requerida' => 1, 'es_obligatorio' => true, 'orden_ensamblaje' => 3, 'created_at' => now(), 'updated_at' => now()],   // Camisa
            ['conjunto_id' => 1, 'componente_id' => 6, 'cantidad_requerida' => 1, 'es_obligatorio' => true, 'orden_ensamblaje' => 4, 'created_at' => now(), 'updated_at' => now()],   // Faja
            ['conjunto_id' => 1, 'componente_id' => 7, 'cantidad_requerida' => 1, 'es_obligatorio' => true, 'orden_ensamblaje' => 5, 'created_at' => now(), 'updated_at' => now()],   // Sombrero
            ['conjunto_id' => 1, 'componente_id' => 9, 'cantidad_requerida' => 1, 'es_obligatorio' => false, 'orden_ensamblaje' => 6, 'created_at' => now(), 'updated_at' => now()],  // Cascabeles
            ['conjunto_id' => 1, 'componente_id' => 10, 'cantidad_requerida' => 1, 'es_obligatorio' => false, 'orden_ensamblaje' => 7, 'created_at' => now(), 'updated_at' => now()], // Chicote
        ]);

        echo "Conjunto system seeded successfully!\n";
    }
}
