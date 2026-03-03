<?php

namespace Database\Seeders\TypeDocument;

use App\Models\TypeDocuments\typeDocument;
use Illuminate\Database\Seeder;

class typedocumentseeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeDocuments  = [
            ['name' => 'Cédula de Ciudadanía', 'acronym' => 'CC'],
            ['name' => 'Tarjeta de Identidad', 'acronym' => 'TI'],
            ['name' => 'Cédula de Extranjería', 'acronym' => 'CE'],
            ['name' => 'Permiso de Permanencia Temporal', 'acronym' => 'PPT'],
        ];

        foreach ($typeDocuments as $type) {
            TypeDocument::create($type);
        }
    }
}
