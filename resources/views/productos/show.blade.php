@extends('layouts.app')

@section('title', 'Detalles del Producto')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Encabezado de página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Detalles del Producto</h1>
            <p class="text-muted mb-0">Información completa del producto</p>
        </div>
        
        <div>
            <a href="{{ route('productos.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm font-weight-bold">
                <i class="fas fa-arrow-left mr-2 text-warning"></i> Volver al Listado
            </a>
        </div>
    </div>

    {{-- Tarjeta principal --}}
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Encabezado Negro --}}
            <div class="bg-dark text-white px-4 py-3 d-flex justify-content-between align-items-center" style="border-radius: 25px 25px 0 0;">
                <div>
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-box mr-2 text-warning"></i> {{ $producto->Nombre }}
                    </h5>
                    <small class="text-muted">Información del producto</small>
                </div>
                <span class="badge px-3 py-2 {{ $producto->activo ? 'badge-success' : 'badge-secondary' }}">
                    {{ $producto->activo ? 'ACTIVO' : 'INACTIVO' }}
                </span>
            </div>

            {{-- Cuerpo de la tarjeta --}}
            <div class="card-body p-4">
                
                {{-- Información básica --}}
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">CATEGORÍA</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $producto->categoria ?? 'Sin categoría' }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">PRECIO DE VENTA</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            <span class="text-success font-weight-bold">S/ {{ number_format($producto->PrecioVenta, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">HOTEL / SEDE</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $producto->hotel->Nombre ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">STOCK ACTUAL</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            <span class="{{ $producto->stockBajo() ? 'text-danger font-weight-bold' : 'text-dark' }}">
                                {{ $producto->StockActual }} unidades
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">STOCK MÍNIMO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $producto->StockMinimo }} unidades
                        </div>
                    </div>
                </div>

                {{-- Descripción --}}
                @if($producto->descripcion)
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">DESCRIPCIÓN</label>
                            <div class="form-control rounded-15 bg-light border-0 px-4 py-3" style="background-color: #e9ecef; height: auto;">
                                {{ $producto->descripcion }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ============================================= --}}
                {{-- ACCIONES RÁPIDAS PARA AJUSTAR STOCK --}}
                {{-- ============================================= --}}
                @if(in_array(auth()->user()->IdRol, [1, 4]) && $producto->activo)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light border-0" style="border-radius: 15px;">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-3">
                                        <i class="fas fa-tools mr-2 text-warning"></i> Acciones Rápidas de Stock
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalMerma">
                                            <i class="fas fa-trash-alt mr-1"></i> Merma / Producto Dañado
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalRetiro">
                                            <i class="fas fa-gift mr-1"></i> Cortesía / Retiro
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalCompra">
                                            <i class="fas fa-shopping-cart mr-1"></i> Registrar Compra
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalAjuste">
                                            <i class="fas fa-exchange-alt mr-1"></i> Ajuste General
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Estadísticas de movimientos --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-chart-line mr-2 text-warning"></i> Estadísticas de Movimientos
                        </h6>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 bg-light text-center rounded-3">
                            <div class="small text-muted">Compras</div>
                            <div class="h5 mb-0 font-weight-bold text-primary">{{ number_format($stats['total_compras']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 bg-light text-center rounded-3">
                            <div class="small text-muted">Ventas</div>
                            <div class="h5 mb-0 font-weight-bold text-success">{{ number_format($stats['total_ventas']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 bg-light text-center rounded-3">
                            <div class="small text-muted">Mermas</div>
                            <div class="h5 mb-0 font-weight-bold text-danger">{{ number_format($stats['total_mermas']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="p-3 bg-light text-center rounded-3">
                            <div class="small text-muted">Retiros</div>
                            <div class="h5 mb-0 font-weight-bold text-warning">{{ number_format($stats['total_retiros']) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Últimos movimientos --}}
                @if($producto->movimientos->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-history mr-2 text-warning"></i> Últimos Movimientos
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unit.</th>
                                            <th>Observación</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($producto->movimientos->take(10) as $movimiento)
                                        <tr>
                                            <td>{{ $movimiento->fecha_movimiento->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge {{ $movimiento->tipo_badge_class }}">
                                                    {{ $movimiento->tipo_nombre }}
                                                </span>
                                            </td>
                                            <td class="{{ in_array($movimiento->tipo, ['venta', 'retiro', 'merma']) ? 'text-danger' : 'text-success' }}">
                                                {{ $movimiento->cantidad_formateada }}
                                            </td>
                                            <td>{{ $movimiento->precio_unitario ? 'S/ ' . number_format($movimiento->precio_unitario, 2) : '—' }}</td>
                                            <td>{{ $movimiento->observacion ?? '—' }}</td>
                                            <td>{{ $movimiento->usuario->Username ?? '—' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Fechas de registro --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">FECHA DE REGISTRO</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-muted ml-2">ÚLTIMA ACTUALIZACIÓN</label>
                        <div class="form-control rounded-pill bg-light border-0 px-4" style="background-color: #e9ecef;">
                            {{ $producto->updated_at ? $producto->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Información adicional si está inactivo --}}
                @if(!$producto->activo)
                    <div class="alert alert-warning text-center mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Este producto está INACTIVO
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('productos.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                        <i class="fas fa-times mr-2 text-danger"></i> Cerrar
                    </a>
                    
                    @if(in_array(auth()->user()->IdRol, [1, 4]) && $producto->activo)
                        <a href="{{ route('productos.edit', $producto->IdProducto) }}" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Editar Producto
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- MODALES PARA AJUSTES DE STOCK --}}
{{-- ============================================= --}}

{{-- Modal Merma --}}
<div class="modal fade" id="modalMerma" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Registrar Merma / Producto Dañado</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('movimientos.producto.store', $producto->IdProducto) }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="merma">
                <div class="modal-body">
                    <p>Producto: <strong>{{ $producto->Nombre }}</strong></p>
                    <p>Stock actual: <strong>{{ $producto->StockActual }} unidades</strong></p>
                    <div class="form-group">
                        <label>Cantidad a dar de baja</label>
                        <input type="number" name="cantidad" class="form-control" min="1" max="{{ $producto->StockActual }}" required>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Ej: Producto vencido, dañado en almacén, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Registrar Merma</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Retiro / Cortesía --}}
<div class="modal fade" id="modalRetiro" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Registrar Cortesía / Retiro</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('movimientos.producto.store', $producto->IdProducto) }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="retiro">
                <div class="modal-body">
                    <p>Producto: <strong>{{ $producto->Nombre }}</strong></p>
                    <p>Stock actual: <strong>{{ $producto->StockActual }} unidades</strong></p>
                    <div class="form-group">
                        <label>Cantidad a retirar</label>
                        <input type="number" name="cantidad" class="form-control" min="1" max="{{ $producto->StockActual }}" required>
                    </div>
                    <div class="form-group">
                        <label>Motivo / Observación</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Ej: Cortesía para cliente VIP, consumo staff, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Registrar Retiro</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Compra --}}
<div class="modal fade" id="modalCompra" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Registrar Compra</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('movimientos.producto.store', $producto->IdProducto) }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="compra">
                <div class="modal-body">
                    <p>Producto: <strong>{{ $producto->Nombre }}</strong></p>
                    <div class="form-group">
                        <label>Cantidad comprada</label>
                        <input type="number" name="cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Precio de compra (unitario)</label>
                        <input type="number" step="0.01" name="precio_unitario" class="form-control" placeholder="Opcional">
                        <small class="text-muted">Para control de costos</small>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Ej: Compra a proveedor X, factura #123"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Compra</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Ajuste General --}}
<div class="modal fade" id="modalAjuste" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Ajuste General de Stock</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('movimientos.producto.store', $producto->IdProducto) }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="ajuste">
                <div class="modal-body">
                    <p>Producto: <strong>{{ $producto->Nombre }}</strong></p>
                    <p>Stock actual: <strong>{{ $producto->StockActual }} unidades</strong></p>
                    <div class="form-group">
                        <label>Cambio en stock</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+ / -</span>
                            </div>
                            <input type="number" name="cantidad" class="form-control" required>
                        </div>
                        <small class="text-muted">Ej: +10 para aumentar stock, -5 para disminuir</small>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Motivo del ajuste..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Registrar Ajuste</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Validar que la cantidad no exceda el stock para merma y retiro
    document.querySelectorAll('#modalMerma form, #modalRetiro form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const cantidad = this.querySelector('input[name="cantidad"]');
            const maxStock = {{ $producto->StockActual }};
            
            if (parseInt(cantidad.value) > maxStock) {
                e.preventDefault();
                SwalError('Error', `No puedes retirar más de ${maxStock} unidades. Stock actual: ${maxStock}`);
            }
        });
    });
</script>

<style>
    .rounded-3 {
        border-radius: 15px !important;
    }
    
    .rounded-pill {
        border-radius: 50px !important;
    }
    
    .badge-success {
        background-color: #2e7d32;
        color: white;
    }
    
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .badge-primary {
        background-color: #0066cc;
        color: white;
    }
    
    .gap-2 {
        gap: 0.5rem;
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