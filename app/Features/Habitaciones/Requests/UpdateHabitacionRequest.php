<?php

namespace App\Features\Habitaciones\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $habitacion = $this->route('habitacion');
        
        return [
            'IdTipo' => 'required|exists:tipo_habitacion,IdTipo',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
            'Numero' => "required|string|max:10|unique:habitaciones,Numero,{$habitacion->IdHabitacion},IdHabitacion",
            'Piso' => 'required|integer|min:1',
            'IdEstadoHabitacion' => 'required|in:1,2,3',
        ];
    }
}