<?php

namespace App\Services\ImportExcel;

use App\Imports\ApprenticesImport;

class ImportExcelService
{
    protected $importer;

    public function __construct()
    {
        $this->importer = new ApprenticesImport();
    }

    /**
     * Importa aprendices desde un archivo Excel
     *
     * @param $data array Con clave 'file' => UploadedFile
     * @return array Resultado con errores si existen
     */
    public function importApprentices($data)
    {
        if (!isset($data['file'])) {
            return [
                'error' => true,
                'code' => 400,
                'message' => 'No se ha enviado ningún archivo',
            ];
        }

        $errors = $this->importer->importFile($data['file']);

        if (!empty($errors)) {
            return [
                'error' => true,
                'code' => 206,
                'message' => count($errors) . ' registros fallaron',
                'errors' => $errors,
            ];
        }

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Aprendices importados correctamente',
        ];
    }
}
