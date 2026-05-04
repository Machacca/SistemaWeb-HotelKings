@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #1a1a1a; margin: 0;">Productos</h1>
            <p class="text-muted mb-0">Gestión de inventario y productos</p>
        </div>
        
        @if(in_array(auth()->user()->IdRol, [1, 4]))
            <a href="{{ route('productos.create') }}" 
               class="btn btn-dark rounded-pill px-5 py-3 shadow-sm font-weight-bold" 
               style="font-size: 1rem;">
                <i class="fas fa-plus mr-2 text-warning"></i> Nuevo Producto
            </a>
        @endif
    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form method="GET" id="formFiltros" class="row align-items-end">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">BUSCAR</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" 
                           class="form-control rounded-pill border-0 bg-light px-4" 
                           placeholder="Nombre o categoría...">
                </div>
                
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">CATEGORÍA</label>
                    <select name="categoria" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                                {{ $categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">STOCK</label>
                    <select name="stock" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="">Todos</option>
                        <option value="bajo" {{ request('stock') == 'bajo' ? 'selected' : '' }}>Stock Bajo</option>
                        <option value="critico" {{ request('stock') == 'critico' ? 'selected' : '' }}>Sin Stock</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">ESTADO</label>
                    <select name="activo" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="1" {{ request('activo', '1') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                        <option value="" {{ request('activo') == '' && request('activo') !== '1' ? 'selected' : '' }}>Todos</option>
                    </select>
                </div>

                @if(auth()->user()->IdRol == 4)
                <div class="col-md-1 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted ml-2">HOTEL</label>
                    <select name="hotel" class="form-control rounded-pill border-0 bg-light px-4">
                        <option value="">Todos</option>
                        @foreach($hoteles as $hotel)
                            <option value="{{ $hotel->IdHotel }}" {{ request('hotel') == $hotel->IdHotel ? 'selected' : '' }}>
                                {{ $hotel->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-1 text-right">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 font-weight-bold shadow-sm w-100">
                        <i class="fas fa-filter mr-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TARJETAS DE PRODUCTOS --}}
    <div class="row">
        @forelse($productos as $producto)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card product-card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    
                    {{-- Cabecera con estado --}}
                    <div class="card-header bg-dark text-white border-0 d-flex justify-content-between align-items-center" style="border-radius: 20px 20px 0 0;">
                        <span class="font-weight-bold text-truncate" style="max-width: 150px;">{{ $producto->Nombre }}</span>
                        @if(!$producto->activo)
                            <span class="badge badge-secondary">INACTIVO</span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        {{-- Categoría --}}
                        @if($producto->categoria)
                            <div class="mb-2">
                                <span class="badge badge-light px-3 py-2">
                                    <i class="fas fa-tag mr-1"></i> {{ $producto->categoria }}
                                </span>
                            </div>
                        @endif
                        
                        {{-- Precio --}}
                        <div class="mb-3">
                            <span class="h4 text-success">S/ {{ number_format($producto->PrecioVenta, 2) }}</span>
                        </div>
                        
                        {{-- Stock con alerta --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Stock actual:</span>
                                <span class="{{ $producto->stockBajo() ? 'text-danger font-weight-bold' : 'text-dark' }}">
                                    {{ $producto->StockActual }} unidades
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php
                                    $porcentaje = $producto->StockMinimo > 0 
                                        ? min(100, ($producto->StockActual / $producto->StockMinimo) * 100) 
                                        : 100;
                                @endphp
                                <div class="progress-bar {{ $producto->stockBajo() ? 'bg-danger' : 'bg-warning' }}" 
                                     style="width: {{ $porcentaje }}%"></div>
                            </div>
                            <div class="small text-muted mt-1">
                                Stock mínimo: {{ $producto->StockMinimo }} unidades
                            </div>
                        </div>
                        
                        {{-- Estadísticas rápidas --}}
                        <div class="row mt-3 pt-2 border-top">
                            <div class="col-6 text-center">
                                <div class="small text-muted">Vendidos</div>
                                <div class="font-weight-bold">{{ number_format($producto->total_ventas) }}</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="small text-muted">Valor stock</div>
                                <div class="font-weight-bold">S/ {{ number_format($producto->valor_inventario, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Botones de acción --}}
                    <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('productos.show', $producto->IdProducto) }}" 
                               class="btn btn-sm px-3 py-1" 
                               style="background: rgba(212, 175, 55, 0.1); color: #d4af37; border-radius: 50px;">
                                <i class="fas fa-eye mr-1"></i> Ver
                            </a>
                            @if(in_array(auth()->user()->IdRol, [1, 4]))
                                <a href="{{ route('productos.edit', $producto->IdProducto) }}" 
                                   class="btn btn-sm px-3 py-1" 
                                   style="background: rgba(212, 175, 55, 0.1); color: #d4af37; border-radius: 50px;">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                                @if($producto->activo)
                                    <button type="button" 
                                            class="btn btn-sm px-3 py-1 btn-eliminar"
                                            data-url="{{ route('productos.destroy', $producto->IdProducto) }}"
                                            data-nombre="{{ $producto->Nombre }}"
                                            style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border-radius: 50px;">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm px-3 py-1 btn-reactivar"
                                            data-url="{{ route('productos.reactivar', $producto->IdProducto) }}"
                                            data-nombre="{{ $producto->Nombre }}"
                                            style="background: rgba(40, 167, 69, 0.1); color: #28a745; border-radius: 50px;">
                                        <i class="fas fa-undo-alt mr-1"></i>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <style>
                .product-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
                }
                .gap-2 {
                    gap: 0.5rem;
                }
            </style>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-boxes fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No hay productos registrados.</p>
                    @if(in_array(auth()->user()->IdRol, [1, 4]))
                        <a href="{{ route('productos.create') }}" class="btn btn-dark rounded-pill px-4">
                            <i class="fas fa-plus mr-2"></i> Crear primer producto
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    {{-- Paginación Personalizada --}}
    @if($productos->hasPages())
    <div class="d-flex justify-content-center align-items-center mt-5">
        <nav aria-label="Navegación de páginas">
            <ul class="pagination-luxury">
                {{-- Botón Anterior --}}
                @if($productos->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $productos->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif
                
                {{-- Números de página --}}
                @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                    @if($page == $productos->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
                
                {{-- Botón Siguiente --}}
                @if($productos->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $productos->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    {{-- Contador de resultados --}}
    <div class="text-center mt-2">
        <small class="text-muted">
            Mostrando {{ $productos->firstItem() ?? 0 }} - {{ $productos->lastItem() ?? 0 }} 
            de {{ $productos->total() }} resultados
        </small>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // ELIMINAR (DESACTIVAR)
    // ============================================
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let nombre = this.dataset.nombre;
            
            SwalConfirmacion(
                '¿Desactivar producto "' + nombre + '"?',
                'El producto quedará como INACTIVO en el sistema.',
                'warning',
                'Sí, desactivar'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            SwalToast('success', data.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            SwalError('Error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        SwalError('Error', 'Ocurrió un error al desactivar el producto');
                    });
                }
            });
        });
    });

    // ============================================
    // REACTIVAR
    // ============================================
    document.querySelectorAll('.btn-reactivar').forEach(btn => {
        btn.addEventListener('click', function() {
            let url = this.dataset.url;
            let nombre = this.dataset.nombre;
            
            SwalConfirmacionVerde(
                '¿Reactivar producto "' + nombre + '"?',
                'El producto volverá a estar disponible en el sistema.',
                'Sí, reactivar'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            SwalToast('success', data.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            SwalError('Error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        SwalError('Error', 'Ocurrió un error al reactivar el producto');
                    });
                }
            });
        });
    });

});
</script>

<style>
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
    
    .product-card .card-header {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%) !important;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }

        /* Paginación Luxury */
    .pagination-luxury {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 5px;
    }

    .pagination-luxury .page-item {
        margin: 0 2px;
    }

    .pagination-luxury .page-link {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 2px solid #e9ecef;
        background: white;
        color: #1a1a1a;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .pagination-luxury .page-link:hover {
        background: #f8f9fa;
        border-color: #c9a45c;
        color: #c9a45c;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(201, 164, 92, 0.15);
    }

    .pagination-luxury .page-item.active .page-link {
        background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
        border-color: #1a1a1a;
        color: #c9a45c;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .pagination-luxury .page-item.disabled .page-link {
        background: #f8f9fa;
        border-color: #e9ecef;
        color: #ccc;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination-luxury .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
    }

    /* Animación de carga */
    .pagination-luxury .page-link {
        position: relative;
        overflow: hidden;
    }

    .pagination-luxury .page-link::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(201, 164, 92, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }

    .pagination-luxury .page-link:hover::after {
        width: 100%;
        height: 100%;
    }
</style>

@endsection