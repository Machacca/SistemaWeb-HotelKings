@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Nuevo Producto</h1>
            <p class="text-muted mb-0">Registre un nuevo producto o servicio</p>
        </div>
        
        <a href="{{ route('productos.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Listado
        </a>
    </div>

    {{-- Manejo de Errores --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="d-flex">
                <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                <ul class="list-unstyled mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Tarjeta con Encabezado Negro --}}
    <div style="max-width: 700px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3" style="border-radius: 25px 25px 0 0;">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-boxes mr-2 text-warning"></i> Registrar Producto
                </h5>
                <small class="text-muted">Complete los datos del nuevo producto</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('productos.store') }}" id="formProducto">
                    @csrf

                    <div class="row">
                        {{-- Nombre del Producto --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRE DEL PRODUCTO</label>
                            <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Nombre') }}" placeholder="Ej: Coca Cola 500ml" required>
                        </div>

                        {{-- Categoría --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">CATEGORÍA</label>
                            <select name="categoria" class="form-control rounded-pill bg-light border-0 px-4">
                                <option value="">Seleccionar categoría...</option>
                                <option value="Bebidas" {{ old('categoria') == 'Bebidas' ? 'selected' : '' }}>Bebidas</option>
                                <option value="Snacks" {{ old('categoria') == 'Snacks' ? 'selected' : '' }}>Snacks</option>
                                <option value="Comidas" {{ old('categoria') == 'Comidas' ? 'selected' : '' }}>Comidas</option>
                                <option value="Tocador" {{ old('categoria') == 'Tocador' ? 'selected' : '' }}>Artículos de Tocador</option>
                                <option value="Otros" {{ old('categoria') == 'Otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                        </div>

                        {{-- Precio de Venta --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">PRECIO DE VENTA (S/)</label>
                            <input type="number" step="0.01" name="PrecioVenta" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('PrecioVenta') }}" placeholder="0.00" required>
                        </div>

                        {{-- Stock Actual --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">STOCK ACTUAL</label>
                            <input type="number" name="StockActual" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('StockActual', 0) }}" min="0" required>
                        </div>

                        {{-- Stock Mínimo --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">STOCK MÍNIMO</label>
                            <input type="number" name="StockMinimo" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('StockMinimo', 5) }}" min="0" required>
                            <small class="text-muted ml-2">Se mostrará alerta cuando el stock baje de este número</small>
                        </div>

                        {{-- Hotel (solo para Master) --}}
                        @if(auth()->user()->IdRol == 4)
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted ml-2">HOTEL / SEDE</label>
                                <select name="IdHotel" class="form-control rounded-pill bg-light border-0 px-4" required>
                                    <option value="">Seleccionar hotel...</option>
                                    @foreach($hoteles as $hotel)
                                        <option value="{{ $hotel->IdHotel }}" {{ old('IdHotel') == $hotel->IdHotel ? 'selected' : '' }}>
                                            {{ $hotel->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="IdHotel" value="{{ auth()->user()->IdHotel }}">
                        @endif

                        {{-- Descripción --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">DESCRIPCIÓN (OPCIONAL)</label>
                            <textarea name="descripcion" class="form-control rounded-15 bg-light border-0 px-4 py-3" 
                                      rows="3" placeholder="Descripción del producto...">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>

                    {{-- Información del stock inicial --}}
                    <div class="alert alert-info mt-2" style="background-color: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 15px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle mr-3" style="color: #d4af37;"></i>
                            <div class="small">
                                <strong class="text-warning">Stock inicial</strong><br>
                                <span class="text-muted">El stock inicial se registrará automáticamente como una compra en el historial de movimientos.</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('productos.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2 text-warning"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formProducto').addEventListener('submit', function(e) {
        // Validación adicional: stock actual debe ser un número válido
        const stockActual = document.querySelector('input[name="StockActual"]').value;
        if (stockActual < 0) {
            e.preventDefault();
            SwalError('Error', 'El stock actual no puede ser negativo');
        }
    });
</script>

<style>
    .rounded-15 {
        border-radius: 15px !important;
    }
    
    .rounded-pill {
        border-radius: 50px !important;
    }
    
    .form-control:focus, select:focus {
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
        background-color: #fff !important;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
    }
</style>

@endsection