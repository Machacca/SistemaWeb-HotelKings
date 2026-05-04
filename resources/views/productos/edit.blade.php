@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Editar Producto</h1>
            <p class="text-muted mb-0">Actualice los datos del producto</p>
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
                    <i class="fas fa-edit mr-2 text-warning"></i> Editar Producto
                </h5>
                <small class="text-muted">Modifique los datos del producto</small>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">
                <form method="POST" action="{{ route('productos.update', $producto->IdProducto) }}" id="formProductoEdit">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Nombre del Producto --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRE DEL PRODUCTO</label>
                            <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('Nombre', $producto->Nombre) }}" required>
                        </div>

                        {{-- Categoría --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">CATEGORÍA</label>
                            <select name="categoria" class="form-control rounded-pill bg-light border-0 px-4">
                                <option value="">Seleccionar categoría...</option>
                                <option value="Bebidas" {{ old('categoria', $producto->categoria) == 'Bebidas' ? 'selected' : '' }}>Bebidas</option>
                                <option value="Snacks" {{ old('categoria', $producto->categoria) == 'Snacks' ? 'selected' : '' }}>Snacks</option>
                                <option value="Comidas" {{ old('categoria', $producto->categoria) == 'Comidas' ? 'selected' : '' }}>Comidas</option>
                                <option value="Tocador" {{ old('categoria', $producto->categoria) == 'Tocador' ? 'selected' : '' }}>Artículos de Tocador</option>
                                <option value="Otros" {{ old('categoria', $producto->categoria) == 'Otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                        </div>

                        {{-- Precio de Venta --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">PRECIO DE VENTA (S/)</label>
                            <input type="number" step="0.01" name="PrecioVenta" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('PrecioVenta', $producto->PrecioVenta) }}" required>
                        </div>

                        {{-- Stock Actual --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">STOCK ACTUAL</label>
                            <input type="number" name="StockActual" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('StockActual', $producto->StockActual) }}" min="0" required>
                            <small class="text-muted ml-2">Cambiar el stock manualmente no registra movimiento. Use el módulo de movimientos para auditoría.</small>
                        </div>

                        {{-- Stock Mínimo --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">STOCK MÍNIMO</label>
                            <input type="number" name="StockMinimo" class="form-control rounded-pill bg-light border-0 px-4" 
                                   value="{{ old('StockMinimo', $producto->StockMinimo) }}" min="0" required>
                        </div>

                        {{-- Hotel (solo para Master) --}}
                        @if(auth()->user()->IdRol == 4)
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted ml-2">HOTEL / SEDE</label>
                                <select name="IdHotel" class="form-control rounded-pill bg-light border-0 px-4" required>
                                    <option value="">Seleccionar hotel...</option>
                                    @foreach($hoteles as $hotel)
                                        <option value="{{ $hotel->IdHotel }}" {{ old('IdHotel', $producto->IdHotel) == $hotel->IdHotel ? 'selected' : '' }}>
                                            {{ $hotel->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="IdHotel" value="{{ $producto->IdHotel }}">
                        @endif

                        {{-- Descripción --}}
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">DESCRIPCIÓN (OPCIONAL)</label>
                            <textarea name="descripcion" class="form-control rounded-15 bg-light border-0 px-4 py-3" 
                                      rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>
                    </div>

                    @if(!$producto->activo)
                        <div class="alert alert-warning text-center mt-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Este producto está INACTIVO
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('productos.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                            <i class="fas fa-times mr-2 text-danger"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-2"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formProductoEdit').addEventListener('submit', function(e) {
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