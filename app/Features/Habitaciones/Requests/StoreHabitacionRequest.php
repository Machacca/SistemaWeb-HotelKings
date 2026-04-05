<?php

namespace App\Features\Habitaciones\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Puedes añadir lógica de roles aquí
    }

    public function rules(): array
    {
        return [
            'IdTipo' => 'required|exists:tipo_habitacion,IdTipo',
            'IdHotel' => 'required|exists:hoteles,IdHotel',
            'Numero' => 'required|string|max:10|unique:habitaciones,Numero',
            'Piso' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'Numero.unique' => 'El número de habitación ya está registrado.',
            'IdTipo.exists' => 'El tipo de habitación seleccionado no es válido.',
            'IdHotel.exists' => 'El hotel seleccionado no es válido.',
        ];
    }
}