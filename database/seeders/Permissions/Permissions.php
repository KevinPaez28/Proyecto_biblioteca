<?php

namespace Database\Seeders\Permissions;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Permissions extends Seeder
{

    public function run(): void
    {
        $permissions = [
            ['name' => 'user.index', 'description' => 'Ver listado de usuarios'],
            ['name' => 'user.search', 'description' => 'Buscar usuarios por información'],
            ['name' => 'user.store', 'description' => 'Crear usuario'],
            ['name' => 'user.update', 'description' => 'Actualizar usuario'],
            ['name' => 'user.destroy', 'description' => 'Eliminar usuario'],

            ['name' => 'roles.index', 'description' => 'Ver listado de roles'],
            ['name' => 'roles.store', 'description' => 'Crear rol'],
            ['name' => 'roles.update', 'description' => 'Actualizar rol'],
            ['name' => 'roles.destroy', 'description' => 'Eliminar rol'],

            ['name' => 'programa.index', 'description' => 'Ver listado de programas'],
            ['name' => 'programa.store', 'description' => 'Crear programa'],
            ['name' => 'programa.update', 'description' => 'Actualizar programa'],
            ['name' => 'programa.destroy', 'description' => 'Eliminar programa'],

            ['name' => 'perfil.index', 'description' => 'Ver listado de perfiles'],
            ['name' => 'perfil.store', 'description' => 'Crear perfil'],
            ['name' => 'perfil.update', 'description' => 'Actualizar perfil'],
            ['name' => 'perfil.destroy', 'description' => 'Eliminar perfil'],

            ['name' => 'ficha.index', 'description' => 'Ver listado de fichas'],
            ['name' => 'ficha.store', 'description' => 'Crear ficha'],
            ['name' => 'ficha.update', 'description' => 'Actualizar ficha'],
            ['name' => 'ficha.destroy', 'description' => 'Eliminar ficha'],

            ['name' => 'documento.index', 'description' => 'Ver listado de documentos'],
            ['name' => 'documento.store', 'description' => 'Crear documento'],
            ['name' => 'documento.update', 'description' => 'Actualizar documento'],
            ['name' => 'documento.destroy', 'description' => 'Eliminar documento'],

            ['name' => 'actions.index', 'description' => 'Ver listado de acciones'],
            ['name' => 'actions.store', 'description' => 'Crear acción'],
            ['name' => 'actions.update', 'description' => 'Actualizar acción'],
            ['name' => 'actions.destroy', 'description' => 'Eliminar acción'],

            ['name' => 'historial.index', 'description' => 'Ver listado de historiales'],
            ['name' => 'historial.store', 'description' => 'Crear historial'],
            ['name' => 'historial.update', 'description' => 'Actualizar historial'],
            ['name' => 'historial.destroy', 'description' => 'Eliminar historial'],

            ['name' => 'horarios.index', 'description' => 'Ver listado de horarios'],
            ['name' => 'horarios.store', 'description' => 'Crear horario'],
            ['name' => 'horarios.update', 'description' => 'Actualizar horario'],
            ['name' => 'horarios.destroy', 'description' => 'Eliminar horario'],

            ['name' => 'jornadas.index', 'description' => 'Ver listado de jornadas'],
            ['name' => 'jornadas.store', 'description' => 'Crear jornada'],
            ['name' => 'jornadas.update', 'description' => 'Actualizar jornada'],
            ['name' => 'jornadas.destroy', 'description' => 'Eliminar jornada'],

            ['name' => 'estadoMotivos.index', 'description' => 'Ver listado de estados de motivos'],
            ['name' => 'estadoMotivos.store', 'description' => 'Crear estado de motivo'],
            ['name' => 'estadoMotivos.update', 'description' => 'Actualizar estado de motivo'],
            ['name' => 'estadoMotivos.destroy', 'description' => 'Eliminar estado de motivo'],

            ['name' => 'motivos.index', 'description' => 'Ver listado de motivos'],
            ['name' => 'motivos.store', 'description' => 'Crear motivo'],
            ['name' => 'motivos.update', 'description' => 'Actualizar motivo'],
            ['name' => 'motivos.destroy', 'description' => 'Eliminar motivo'],

            ['name' => 'estadoEventos.index', 'description' => 'Ver listado de estados de eventos'],
            ['name' => 'estadoEventos.store', 'description' => 'Crear estado de evento'],
            ['name' => 'estadoEventos.update', 'description' => 'Actualizar estado de evento'],
            ['name' => 'estadoEventos.destroy', 'description' => 'Eliminar estado de evento'],

            ['name' => 'estadosalas.index', 'description' => 'Ver listado de estados de salas'],
            ['name' => 'estadosalas.store', 'description' => 'Crear estado de sala'],
            ['name' => 'estadosalas.update', 'description' => 'Actualizar estado de sala'],
            ['name' => 'estadosalas.destroy', 'description' => 'Eliminar estado de sala'],

            ['name' => 'salas.index', 'description' => 'Ver listado de salas'],
            ['name' => 'salas.store', 'description' => 'Crear sala'],
            ['name' => 'salas.update', 'description' => 'Actualizar sala'],
            ['name' => 'salas.destroy', 'description' => 'Eliminar sala'],

            ['name' => 'eventos.index', 'description' => 'Ver listado de eventos'],
            ['name' => 'eventos.today', 'description' => 'Ver eventos del día'],
            ['name' => 'eventos.store', 'description' => 'Crear evento'],
            ['name' => 'eventos.update', 'description' => 'Actualizar evento'],
            ['name' => 'eventos.destroy', 'description' => 'Eliminar evento'],

            ['name' => 'asistencia.index', 'description' => 'Ver listado de asistencias'],
            ['name' => 'asistencia.store', 'description' => 'Crear asistencia'],
            ['name' => 'asistencia.update', 'description' => 'Actualizar asistencia'],
            ['name' => 'asistencia.destroy', 'description' => 'Eliminar asistencia'],
        ];
    }
}
