<?php

namespace App\Imports;

use App\Models\Apprentice;
use App\Models\User\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ApprenticesImport
{
    public $errors = [];

    public function importFile($file)
    {
        // Cargar el Excel
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Primera fila = encabezados
        $headers = array_map('strtolower', $rows[0]);

        // Recorrer filas
        for ($i = 1; $i < count($rows); $i++) {
            $row = array_combine($headers, $rows[$i]);

            try {
                User::create([
                    'numero_documento'   => $row['numero_documento'] ?? null,
                    'nombres'            => $row['nombres'] ?? null,
                    'apellidos'          => $row['apellidos'] ?? null,
                    'celular'            => $row['celular'] ?? null,
                    'correo_electronico' => $row['correo_electronico'] ?? null,
                ]);
            } catch (\Exception $e) {
                $this->errors[] = [
                    'numero_documento' => $row['numero_documento'] ?? null,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $this->errors;
    }
}
