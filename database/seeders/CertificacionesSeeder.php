<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificacionesSeeder extends Seeder
{
    public function run(): void
    {
        echo "📜 Creando certificados y evidencias...\n";

        // 1. Catálogo de Certificados
        $certs = [
            [
                'codigo_certificado' => 'CERT-ISO-9001',
                'ambito' => 'PLANTA',
                'area' => 'CALIDAD',
                'vigente_desde' => '2024-01-01',
                'vigente_hasta' => '2025-12-31',
                'emisor' => 'Bureau Veritas',
                'url_archivo' => 'https://example.com/cert-iso.pdf'
            ],
            [
                'codigo_certificado' => 'CERT-ORG-001',
                'ambito' => 'CAMPO',
                'area' => 'ORGANICO',
                'vigente_desde' => '2024-01-01',
                'vigente_hasta' => '2024-12-31',
                'emisor' => 'Certificadora BioLatina',
                'url_archivo' => 'https://example.com/cert-org.pdf'
            ],
            [
                'codigo_certificado' => 'CERT-BPM-2024',
                'ambito' => 'PLANTA',
                'area' => 'BPM',
                'vigente_desde' => '2024-06-01',
                'vigente_hasta' => '2025-06-01',
                'emisor' => 'Senasag',
                'url_archivo' => 'https://example.com/cert-bpm.pdf'
            ]
        ];

        $certIds = [];
        foreach ($certs as $c) {
            // Idempotency: updateOrInsert
            DB::table('certificacion.certificado')->updateOrInsert(
                ['codigo_certificado' => $c['codigo_certificado']],
                [
                    'ambito' => $c['ambito'],
                    'area' => $c['area'],
                    'vigente_desde' => $c['vigente_desde'],
                    'vigente_hasta' => $c['vigente_hasta'],
                    'emisor' => $c['emisor'],
                    'url_archivo' => $c['url_archivo']
                ]
            );

            $id = DB::table('certificacion.certificado')->where('codigo_certificado', $c['codigo_certificado'])->value('certificado_id');
            $certIds[$c['codigo_certificado']] = $id;
        }

        // 2. Asociar a Lotes de Campo
        $loteCampo = DB::table('campo.lotecampo')->first();
        if ($loteCampo) {
            $exists = DB::table('certificacion.certificadolotecampo')
                ->where('lote_campo_id', $loteCampo->lote_campo_id)
                ->where('certificado_id', $certIds['CERT-ORG-001'])
                ->exists();

            if (!$exists) {
                DB::table('certificacion.certificadolotecampo')->insert([
                    'lote_campo_id' => $loteCampo->lote_campo_id,
                    'certificado_id' => $certIds['CERT-ORG-001']
                    // 'fecha_asignacion' => now() // Column does not exist
                ]);
            }
        }

        // 3. Asociar a Lotes de Planta
        $lotePlanta = DB::table('planta.loteplanta')->first();
        if ($lotePlanta) {
            $exists = DB::table('certificacion.certificadoloteplanta')
                ->where('lote_planta_id', $lotePlanta->lote_planta_id)
                ->where('certificado_id', $certIds['CERT-BPM-2024'])
                ->exists();

            if (!$exists) {
                DB::table('certificacion.certificadoloteplanta')->insert([
                    'lote_planta_id' => $lotePlanta->lote_planta_id,
                    'certificado_id' => $certIds['CERT-BPM-2024']
                    // 'fecha_asignacion' => now() // Column does not exist
                ]);
            }
        }

        // 4. Asociar a Lotes de Salida (Producto Terminado)
        $loteSalida = DB::table('planta.lotesalida')->first();
        if ($loteSalida) {
            $exists = DB::table('certificacion.certificadolotesalida')
                ->where('lote_salida_id', $loteSalida->lote_salida_id)
                ->where('certificado_id', $certIds['CERT-ISO-9001'])
                ->exists();

            if (!$exists) {
                DB::table('certificacion.certificadolotesalida')->insert([
                    'lote_salida_id' => $loteSalida->lote_salida_id,
                    'certificado_id' => $certIds['CERT-ISO-9001']
                    // 'fecha_asignacion' => now() // Column does not exist
                ]);
            }
        }

        // 5. Certificado de Envío (ej. Fumigación)
        $envio = DB::table('logistica.envio')->first();
        if ($envio) {
            // Create the certificate itself first
            DB::table('certificacion.certificado')->updateOrInsert(
                ['codigo_certificado' => 'CERT-FUM-12345'],
                [
                    'ambito' => 'ENVIO',
                    'area' => 'FUMIGACION',
                    'vigente_desde' => now()->subDays(1),
                    'vigente_hasta' => now()->addYear(),
                    'emisor' => 'Fumigaciones La Paz',
                    'url_archivo' => 'https://example.com/fumigacion.pdf'
                ]
            );

            $certFumId = DB::table('certificacion.certificado')->where('codigo_certificado', 'CERT-FUM-12345')->value('certificado_id');

            // Link it in the pivot table
            $exists = DB::table('certificacion.certificadoenvio')
                ->where('envio_id', $envio->envio_id)
                ->where('certificado_id', $certFumId)
                ->exists();

            if (!$exists) {
                DB::table('certificacion.certificadoenvio')->insert([
                    'envio_id' => $envio->envio_id,
                    'certificado_id' => $certFumId
                ]);
            }
        }

        echo "✓ Certificados creados y asignados\n";
    }
}
