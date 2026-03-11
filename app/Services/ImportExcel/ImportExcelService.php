<?php

namespace App\Services\ImportExcel;

use App\Models\Ficha\Ficha;
use App\Models\Ficha_users\ficha_user;
use App\Models\Profiles\Profiles;
use App\Models\TypeDocuments\typeDocument;
use App\Models\User\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\Permission\Models\Role;

class ImportExcelService
{
    public function importFile($file)
    {
        $errors = [];

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            // ================= ENCABEZADOS =================
            $headers = array_map(function ($h) {
                return strtolower(trim($h));
            }, $rows[0]);

            for ($i = 1; $i < count($rows); $i++) {

                $rowData = $rows[$i];

                if (!array_filter($rowData)) {
                    continue;
                }

                $row = array_combine($headers, $rowData);

                try {

                    // ================= FICHA =================
                    $fichaExcel = isset($row['ficha']) ? trim((string)$row['ficha']) : '';

                    if ($fichaExcel === '') {
                        $errors[] = [
                            'documento' => $row['numero_documento'] ?? null,
                            'error'     => 'No se proporcionó ficha',
                        ];
                        continue;
                    }

                    if (is_numeric($fichaExcel)) {
                        $fichaExcel = (string)(int)$fichaExcel;
                    }

                    $ficha = Ficha::where('ficha', $fichaExcel)->first();

                    if (!$ficha) {
                        $errors[] = [
                            'documento' => $row['numero_documento'] ?? null,
                            'error'     => "La ficha {$fichaExcel} no existe",
                        ];
                        continue;
                    }

                    // ================= TIPO DOCUMENTO =================
                    $tipoDocExcel = isset($row['tipo_documento']) ? strtoupper(trim($row['tipo_documento'])) : '';

                    if ($tipoDocExcel === '') {
                        $errors[] = [
                            'documento' => $row['numero_documento'] ?? null,
                            'error'     => 'No se proporcionó tipo de documento',
                        ];
                        continue;
                    }

                    // Mapeo Excel → acrónimo BD
                    $mapaAcronimos = [
                        'CC'        => 'CC',
                        'TI'        => 'TI',
                        'CE'        => 'CE',
                        'PPT'       => 'PPT',
                        'PASAPORTE' => 'PA',
                        'PAS'       => 'PA',
                    ];

                    if (!isset($mapaAcronimos[$tipoDocExcel])) {
                        $errors[] = [
                            'documento' => $row['numero_documento'] ?? null,
                            'error'     => "Tipo de documento '{$tipoDocExcel}' no reconocido",
                        ];
                        continue;
                    }

                    $acronimoFinal = $mapaAcronimos[$tipoDocExcel];

                    $documentType = TypeDocument::where('acronym', $acronimoFinal)->first();

                    if (!$documentType) {
                        $errors[] = [
                            'documento' => $row['numero_documento'] ?? null,
                            'error'     => "El tipo con acrónimo '{$acronimoFinal}' no existe en BD",
                        ];
                        continue;
                    }

                    // ================= EVITAR DUPLICADOS =================
                    if (User::where('document', $row['numero_documento'])->exists()) {
                        $errors[] = [
                            'documento' => $row['numero_documento'],
                            'error'     => 'El usuario ya existe',
                        ];
                        continue;
                    }

                    // ================= CREAR USUARIO =================
                    $user = User::create([
                        'document'         => trim((string)$row['numero_documento']),
                        'email'            => trim((string)$row['correo_electronico']),
                        'password'         => bcrypt('12345678'),
                        'status_id'        => 1,
                        'document_type_id' => $documentType->id,
                    ]);

                    // ================= PERFIL =================
                    Profiles::create([
                        'usuario_id' => $user->id,
                        'name'       => trim((string)$row['nombres']),
                        'last_name'  => trim((string)$row['apellidos']),
                        'phone'      => trim((string)$row['celular']),
                    ]);

                    // ================= ROL =================
                    $rol = Role::where('name', 'Aprendiz')->first();
                    if ($rol) {
                        $user->assignRole($rol->name);
                    }

                    // ================= RELACIÓN FICHA =================
                    ficha_user::create([
                        'usuario_id' => $user->id,
                        'ficha_id'   => $ficha->id,
                    ]);

                } catch (\Throwable $e) {
                    $errors[] = [
                        'documento' => $row['numero_documento'] ?? null,
                        'error'     => $e->getMessage(),
                    ];
                }
            }

            if (!empty($errors)) {
                return [
                    'error'   => true,
                    'code'    => 206,
                    'message' => count($errors) . ' registros fallaron',
                    'errors'  => $errors,
                ];
            }

            return [
                'error'   => false,
                'code'    => 200,
                'message' => 'Aprendices importados correctamente',
                'data'    => [],
            ];

        } catch (\Throwable $e) {
            return [
                'error'   => true,
                'code'    => 500,
                'message' => 'Error al procesar el archivo',
                'errors'  => [$e->getMessage()],
            ];
        }
    }
}
