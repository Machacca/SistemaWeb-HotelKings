<header class="bg-white p-3 shadow-sm d-flex justify-content-between align-items-center px-4">
    <div style="width: 300px;">
        <div class="input-group bg-light rounded-pill px-3 py-1">
            <i class="fas fa-search align-self-center text-muted"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Buscar reserva...">
        </div>
    </div>

    <div class="d-flex align-items-center">
        <i class="far fa-bell mr-4 fa-lg text-muted"></i>
        <button class="btn btn-luxury mr-4 font-weight-bold shadow-sm">CHECK-IN</button>
        
        <div class="d-flex align-items-center border-left pl-3">
            <div class="text-right mr-3">
                <span class="d-block font-weight-bold" style="font-size: 0.85rem; color: #333;">Staff Inka</span>
                <small class="text-success" style="font-size: 0.7rem;">● En línea</small>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm border-0" 
                        title="Cerrar Sesión" 
                        style="border-radius: 10px; padding: 8px 12px;">
                    <i class="fas fa-power-off fa-lg"></i>
                </button>
            </form>
        </div>
    </div>
</header>