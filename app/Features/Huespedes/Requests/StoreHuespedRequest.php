<?php
// app/Features/Huespedes/Requests/StoreHuespedRequest.php

namespace App\Features\Huespedes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHuespedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'Nombre' => 'required|string|max:100',
            'Apellido' => 'required|string|max:100',
            'TipoDocumento' => 'required|in:DNI,CE,Pasaporte',
            'NroDocumento' => 'required|string|unique:huespedes,NroDocumento|max:20',
            'Email' => 'nullable|email|unique:huespedes,Email|max:100',
            'Telefono' => 'nullable|string|max:20',
            'Nacionalidad' => 'nullable|string|max:50',
        ];
    }
    
    public function messages()
    {
        return [
            'Nombre.required' => 'El nombre es obligatorio',
            'Apellido.required' => 'El apellido es obligatorio',
            'NroDocumento.unique' => 'Ya existe un huésped con este documento',
            'Email.unique' => 'Este email ya está registrado',
        ];
    }
}