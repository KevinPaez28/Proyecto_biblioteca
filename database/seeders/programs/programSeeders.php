<?php

namespace Database\Seeders\programs;

use App\Models\Program\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class programSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [

            ['name' => 'DIBUJO MECÁNICO'],
            ['name' => 'SOLDADURA DE PRODUCTOS METALICOS EN PLATINA'],
            ['name' => 'INTEGRACION DE CONTENIDOS DIGITALES'],
            ['name' => 'INSTALACION Y MANTENIMIENTO DE REDES INALAMBRICAS'],
            ['name' => 'MANTENIMIENTO ELECTRICO Y CONTROL ELECTRONICO DE AUTOMOTORES'],
            ['name' => 'MANTENIMIENTO DE EQUIPOS DE AIRE ACONDICIONADO Y REFRIGERACIÓN'],
            ['name' => 'INSTRUMENTACION INDUSTRIAL'],
            ['name' => 'MANTENIMIENTO Y ENSAMBLE DE EQUIPOS ELECTRONICOS'],
            ['name' => 'MANTENIMIENTO DE AUTOMATISMOS INDUSTRIALES'],
            ['name' => 'OPERACIÓN EN TORNO Y FRESADORA'],
            ['name' => 'MANTENIMIENTO DE LOS MOTORES DIESEL'],
            ['name' => 'MANTENIMIENTO EQUIPOS DE CÓMPUTO'],
            ['name' => 'SISTEMAS TELEINFORMÁTICOS'],
            ['name' => 'MANTENIMIENTO E INSTALACIÓN DE SISTEMAS SOLARES FOTOVOLTAICOS'],
            ['name' => 'MECANICA DE MAQUINARIA INDUSTRIAL'],
            ['name' => 'CONTROL DE LA SEGURIDAD DIGITAL'],
            ['name' => 'MANTENIMIENTO DE MOTOCICLETAS Y MOTOCARROS'],
            ['name' => 'PROGRAMACIÓN DE SOFTWARE'],
            ['name' => 'CONTROL DE MOVILIDAD, TRANSPORTE Y SEGURIDAD VIAL'],
            ['name' => 'INSTALACION DE SISTEMAS ELECTRICOS RESIDENCIALES Y COMERCIALES'],
            ['name' => 'MANTENIMIENTO DE SISTEMAS DE PROPULSION ELECTRICA E HIBRIDA AUTOMOTRIZ'],
            ['name' => 'TRATAMIENTO DE RIESGOS DE CIBERSEGURIDAD EN LA MICRO, PEQUEÑA Y MEDIANA EMPRESA (MIPYMES)'],
            ['name' => 'ELECTRICISTA INDUSTRIAL'],
            ['name' => 'MANTENIMIENTO DE EQUIPO BIOMÉDICO'],
            ['name' => 'IMPLEMENTACIÓN Y MANTENIMIENTO DE SISTEMAS DE INSTRUMENTACIÓN Y CONTROL DE PROCESOS INDUSTRIALES'],
            ['name' => 'IMPLEMENTACIÓN DE REDES Y SERVICIOS DE TELECOMUNICACIONES'],
            ['name' => 'MANTENIMIENTO ELECTROMECÁNICO INDUSTRIAL'],
            ['name' => 'AUTOMATIZACIÓN DE SISTEMAS MECATRÓNICOS'],
            ['name' => 'DESARROLLO DE VIDEOJUEGOS Y ENTORNOS INTERACTIVOS'],
            ['name' => 'ANALISIS Y DESARROLLO DE SOFTWARE'],
            ['name' => 'DESARROLLO DE SISTEMAS ELECTRÓNICOS INDUSTRIALES'],
            ['name' => 'ELECTRICIDAD INDUSTRIAL'],
            ['name' => 'GESTIÓN DE LA PRODUCCIÓN INDUSTRIAL'],
            ['name' => 'GESTION DE REDES DE DATOS'],
            ['name' => 'PRODUCCION DE COMPONENTES MECANICOS CON MAQUINAS DE CONTROL NUMERICO COMPUTARIZADO'],
            ['name' => 'GESTIÓN DEL MANTENIMIENTO DE AUTOMOTORES'],
            ['name' => 'COORDINACION EN SISTEMAS INTEGRADOS DE GESTION'],
            ['name' => 'GESTIÓN DE LA SEGURIDAD Y SALUD EN EL TRABAJO'],
            ['name' => 'ANIMACIÓN DIGITAL'],

        ];
        foreach ($programs as $program) {
            Program::create([
                'training_program' => $program['name'],
            ]);
        }
    }
}
