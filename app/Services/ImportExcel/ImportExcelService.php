<?php

namespace App\Services\ImportExcel;

use App\Imports\ApprenticesImport;
use App\Models\Ficha_users\ficha_user;
use App\Models\Profiles\Profiles;
use App\Models\User\User;
use GuzzleHttp\Psr7\UploadedFile;
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
                    // ================= ROL (SIEMPRE APRENDIZ) =================
                    $rol = Role::where('name', 'Aprendiz')->first();

                    if ($rol) {
                        $user->assignRole($rol->name);
                    }


                    // ================= FICHA =================
                    if (!empty($row['ficha_id'])) {
                        ficha_user::create([
                            'usuario_id' => $user->id,
                            'ficha_id'   => $row['ficha_id'],
                        ]);
                    }
                } catch (\Throwable $e) {
                    $errors[] = [
                        'documento' => $row['documento'] ?? null,
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
