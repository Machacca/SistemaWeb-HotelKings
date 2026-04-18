<?php
// app/Features/Huespedes/Services/HuespedService.php

namespace App\Features\Huespedes\Services;

use App\Models\Huesped;

class HuespedService
{
    public function create(array $data): Huesped
    {
        return Huesped::create($data);
    }
    
    public function update(Huesped $huesped, array $data): bool
    {
        return $huesped->update($data);
    }
    
    public function findOrCreate(array $data): Huesped
    {
        // Buscar por documento
        $huesped = Huesped::where('NroDocumento', $data['NroDocumento'])->first();
        
        if (!$huesped) {
            $huesped = $this->create($data);
        }
        
        return $huesped;
    }
}