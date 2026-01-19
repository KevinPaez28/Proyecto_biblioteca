<?php

namespace App\Services\ImportExcel;

use App\Models\Ficha\Ficha;
use App\Models\Ficha_users\ficha_user;
use App\Models\Profiles\Profiles;
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
            $rows = $sheet->toArray();

            $headers = array_map('strtolower', $rows[0]);

            for ($i = 1; $i < count($rows); $i++) {
                $row = array_combine($headers, $rows[$i]);

                try {
                    // ================= VALIDAR FICHA =================
                    if (empty($row['ficha'])) {
                        $errors[] = [
                            'documento' => $row['numero_documento'] ?? null,
                            'error'     => 'No se proporcionó ficha',
                        ];
                        continue; // Saltar fila si no hay ficha
                    }

                    $ficha = Ficha::where('ficha', trim($row['ficha']))->first();
                    if (!$ficha) {
                        $errors[] = [
                            'documento' => $row['numero_documento'] ?? null,
                            'error'     => "La ficha {$row['ficha']} no existe",
                        ];
                        continue; // Saltar fila si ficha no existe
                    }

                    // ================= USUARIO =================
                    $user = User::create([
                        'document'  => $row['numero_documento'],
                        'email'     => $row['correo_electronico'],
                        'password'  => bcrypt('12345678'),
                        'status_id' => 1,
                    ]);

                    // ================= PERFIL =================
                    Profiles::create([
                        'usuario_id' => $user->id,
                        'name'       => $row['nombres'],
                        'last_name'  => $row['apellidos'],
                        'phone'      => $row['celular'],
                    ]);

                    // ================= ROL =================
                    $rol = Role::where('name', 'Aprendiz')->first();
                    if ($rol) {
                        $user->assignRole($rol->name);
                    }

                    // ================= FICHA =================
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
