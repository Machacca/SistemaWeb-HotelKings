<div class="modal fade" id="modalDetalleReserva" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header bg-info text-white border-0 p-4" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-calendar-check mr-2"></i> Detalles de Reserva</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div id="contenido-detalle-reserva">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <form id="form-confirmar-ingreso" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-pill px-4">Confirmar Ingreso</button>
                </form>
                <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>