<div class="modal fade" id="modalNuevoHuesped" tabindex="-1" aria-labelledby="modalNuevoHuespedLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> 
        <div class="modal-content" style="border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header bg-dark text-white p-4" style="border-radius: 25px 25px 0 0;">
                <h5 class="modal-title font-weight-bold" id="modalNuevoHuespedLabel">
                    <i class="fas fa-user-plus mr-2 text-warning"></i> Registro Rápido de Huésped
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formNuevoHuespedQuick" action="{{ route('huespedes.store') }}" method="POST">
                @csrf
                <div class="modal-body p-5">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRES</label>
                            <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 shadow-none px-4 text-dark font-weight-bold" style="height: 45px;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">APELLIDOS</label>
                            <input type="text" name="Apellido" class="form-control rounded-pill bg-light border-0 shadow-none px-4 text-dark font-weight-bold" style="height: 45px;" required>
                        </div>
                    </div>

                    <div class="row mb-4 border-bottom pb-4">
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TIPO DOCUMENTO</label>
                            <select name="TipoDocumento" class="form-control rounded-pill bg-light border-0 shadow-none px-4 custom-select-pill text-dark font-weight-bold" style="height: 45px;">
                                <option value="DNI">DNI - Doc. Nacional de Identidad</option>
                                <option value="PAS">Pasaporte</option>
                                <option value="CE">Carnet de Extranjería</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NRO. DOCUMENTO</label>
                            <input type="text" name="NroDocumento" class="form-control rounded-pill bg-light border-0 shadow-none px-4 text-dark font-weight-bold" style="height: 45px;" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">PAÍS / NACIONALIDAD</label>
                            <select name="Nacionalidad" id="selectPaisModal" onchange="actualizarPrefijoModal()" 
                                    class="form-control rounded-pill bg-light border-0 shadow-none px-4 custom-select-pill text-dark font-weight-bold" style="height: 45px;">
                                <option value="Perú" data-prefijo="+51">🇵🇪 Perú</option>
                                <option value="Argentina" data-prefijo="+54">🇦🇷 Argentina</option>
                                <option value="Bolivia" data-prefijo="+591">🇧🇴 Bolivia</option>
                                <option value="Chile" data-prefijo="+56">🇨🇱 Chile</option>
                                <option value="Colombia" data-prefijo="+57">🇨🇴 Colombia</option>
                                <option value="Ecuador" data-prefijo="+593">🇪🇨 Ecuador</option>
                                <option value="España" data-prefijo="+34">🇪🇸 España</option>
                                <option value="México" data-prefijo="+52">🇲🇽 México</option>
                                <option value="Otros" data-prefijo="+">🌐 Otros</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TELÉFONO</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-dark text-warning border-0 font-weight-bold px-3" id="prefijoModal" style="border-radius: 20px 0 0 20px; height: 45px;">+51</span>
                                </div>
                                <input type="text" name="Telefono" class="form-control bg-light border-0 shadow-none px-3 text-dark font-weight-bold" style="border-radius: 0 20px 20px 0; height: 45px;" placeholder="999 999 999">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">EMAIL</label>
                            <input type="email" name="Email" class="form-control rounded-pill bg-light border-0 shadow-none px-4 text-dark font-weight-bold" style="height: 45px;" placeholder="ejemplo@correo.com">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-light" style="border-radius: 0 0 25px 25px;">
                    <button type="button" class="btn btn-link text-muted font-weight-bold text-decoration-none mr-3" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-5 font-weight-bold shadow-sm text-dark">
                        <i class="fas fa-save mr-2"></i> REGISTRAR HUÉSPED
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function actualizarPrefijoModal() {
        const select = document.getElementById('selectPaisModal');
        const prefijo = select.options[select.selectedIndex].getAttribute('data-prefijo');
        document.getElementById('prefijoModal').innerText = prefijo;
    }

    // Inicializar al cargar (por si el modal se abre por JS)
    $('#modalNuevoHuesped').on('shown.bs.modal', function () {
        actualizarPrefijoModal();
    });
</script>

<style>
    .text-dark { color: #000 !important; }
    .custom-select-pill {
        -webkit-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }
</style>