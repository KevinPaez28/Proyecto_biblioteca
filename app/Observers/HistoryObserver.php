<?php

namespace App\Observers;

use App\Models\History\History;
use Illuminate\Support\Facades\Auth;

class HistoryObserver
{
    public function created($model)
    {
        $this->createHistory($model, 'Creación');
    }

    public function updated($model)
    {
        $this->createHistory($model, 'Actualización');
    }

    public function deleted($model)
    {
        $this->createHistory($model, 'Eliminación');
    }

    protected function createHistory($model, $action)
    {
        if (!Auth::check()) {
            return;
        }

        History::create([
            'usuario_id' => Auth::id(),
            'acction_id' => $this->getActionId($action),
            'description' => "{$action} del modelo " . class_basename($model),
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'creation_date' => now(),
        ]);
    }


    protected function getActionId($action)
    {
        return match ($action) {
            'Creación' => 1,
            'Actualización' => 2,
            'Eliminación' => 3,
            default => 0,
        };
    }
}
