<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;
use App\Models\Habitacion;
use App\Models\Acompanante;
use Carbon\Carbon;

class DetalleReserva extends Model
{
    use HasFactory;

    protected $table = 'detalle_reserva';
    protected $primaryKey = 'IdDetalle';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'IdReserva',
        'IdHabitacion',
        'FechaCheckIn',
        'FechaCheckOut',
        'PrecioNoche',
        'PagosAdelantados',
        'Descuento',
    ];

    protected $casts = [
        'IdDetalle' => 'integer',
        'IdReserva' => 'integer',
        'IdHabitacion' => 'integer',
        'FechaCheckIn' => 'date',
        'FechaCheckOut' => 'date',
        'PrecioNoche' => 'decimal:2',
        'PagosAdelantados' => 'decimal:2',
        'Descuento' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // RELACIONES
    // =============================================
    
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'IdReserva', 'IdReserva');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'IdHabitacion', 'IdHabitacion');
    }

    public function acompanantes()
    {
        return $this->hasMany(Acompanante::class, 'IdDetalleReserva', 'IdDetalle');
    }

    // =============================================
    // ATRIBUTOS (ACCESORS)
    // =============================================
    
    public function getNochesAttribute(): int
    {
        if (!$this->FechaCheckIn || !$this->FechaCheckOut) {
            return 1;
        }
        return max(1, $this->FechaCheckIn->diffInDays($this->FechaCheckOut));
    }

    public function getSubtotalHospedajeAttribute(): float
    {
        return $this->PrecioNoche * $this->noches;
    }

    public function getTotalPagarAttribute(): float
    {
        return $this->subtotal_hospedaje - $this->PagosAdelantados - $this->Descuento;
    }

    // =============================================
    // MÉTODOS
    // =============================================
    
    public function estaActivo(): bool
    {
        $hoy = Carbon::today();
        return $hoy->between($this->FechaCheckIn, $this->FechaCheckOut);
    }
}