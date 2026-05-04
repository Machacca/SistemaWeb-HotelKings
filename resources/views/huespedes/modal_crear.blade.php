<div class="modal fade" id="modalNuevoHuesped" tabindex="-1" aria-labelledby="modalNuevoHuespedLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
            
            {{-- Encabezado --}}
            <div class="modal-header bg-dark text-white p-4" style="border-radius: 25px 25px 0 0;">
                <h5 class="modal-title font-weight-bold" id="modalNuevoHuespedLabel">
                    <i class="fas fa-user-plus mr-2 text-warning"></i> Registro Rápido de Huésped
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            {{-- Formulario --}}
            <form id="formNuevoHuespedQuick">
                @csrf
                <div class="modal-body p-4">
                    
                    {{-- Mostrar errores del servidor --}}
                    <div id="modalQuickErrors" class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px; display: none;">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                            <ul class="list-unstyled mb-0" id="modalQuickErrorsList"></ul>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Nombres --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NOMBRES</label>
                            <input type="text" name="Nombre" class="form-control rounded-pill bg-light border-0 px-4" 
                                   placeholder="Ej: Juan" required>
                        </div>
                        
                        {{-- Apellidos --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">APELLIDOS</label>
                            <input type="text" name="Apellido" class="form-control rounded-pill bg-light border-0 px-4" 
                                   placeholder="Ej: Pérez" required>
                        </div>
                        
                        {{-- Tipo de Documento --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TIPO DOCUMENTO</label>
                            <select name="TipoDocumento" class="form-control rounded-pill bg-light border-0 px-4">
                                <option value="DNI">DNI</option>
                                <option value="PAS">Pasaporte</option>
                                <option value="CE">Carnet de Extranjería</option>
                            </select>
                        </div>
                        
                        {{-- Número de Documento --}}
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">NRO. DOCUMENTO</label>
                            <input type="text" name="NroDocumento" class="form-control rounded-pill bg-light border-0 px-4" 
                                   placeholder="00000000" required>
                        </div>
                        
                        {{-- País / Nacionalidad --}}
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">PAÍS</label>
                            <select name="Nacionalidad" id="selectPaisModal" onchange="actualizarPrefijoModal()" class="form-control rounded-pill bg-light border-0 px-4">
                                <option value="Perú" data-prefijo="+51">Perú</option>
                                <option value="Argentina" data-prefijo="+54">Argentina</option>
                                <option value="Bolivia" data-prefijo="+591">Bolivia</option>
                                <option value="Chile" data-prefijo="+56">Chile</option>
                                <option value="Colombia" data-prefijo="+57">Colombia</option>
                                <option value="Ecuador" data-prefijo="+593">Ecuador</option>
                                <option value="España" data-prefijo="+34">España</option>
                                <option value="México" data-prefijo="+52">México</option>
                                <option value="Estados Unidos" data-prefijo="+1">Estados Unidos</option>
                                <option value="Otro" data-prefijo="">Otro</option>
                            </select>
                        </div>
                        
                        {{-- Teléfono con prefijo --}}
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">TELÉFONO</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span id="prefijoModal" class="input-group-text bg-dark text-warning border-0 rounded-pill-left" style="border-radius: 50px 0 0 50px;">+51</span>
                                </div>
                                <input type="text" name="Telefono" id="telefonoModal" class="form-control rounded-pill-right bg-light border-0 px-4" 
                                       placeholder="987 654 321" style="border-radius: 0 50px 50px 0;">
                            </div>
                        </div>
                        
                        {{-- Email --}}
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted ml-2">EMAIL</label>
                            <input type="email" name="Email" class="form-control rounded-pill bg-light border-0 px-4" 
                                   placeholder="correo@ejemplo.com">
                        </div>
                    </div>
                </div>
                
                {{-- Botones --}}
                <div class="modal-footer border-0 p-4 bg-light" style="border-radius: 0 0 25px 25px;">
                    <button type="button" class="btn btn-link text-muted" data-dismiss="modal">CANCELAR</button>
                    <button type="button" class="btn btn-warning rounded-pill px-5 font-weight-bold" id="btnRegistrarHuespedModal">
                        <i class="fas fa-save mr-2"></i> REGISTRAR
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Actualizar prefijo según país seleccionado
    function actualizarPrefijoModal() {
        const select = document.getElementById('selectPaisModal');
        let prefijo = select.options[select.selectedIndex].getAttribute('data-prefijo');
        const prefijoSpan = document.getElementById('prefijoModal');
        
        if (prefijo) {
            prefijoSpan.textContent = prefijo;
        } else {
            const codigoManual = prompt('Ingrese el código de país (ej: +xx):', '+51');
            if (codigoManual) {
                prefijoSpan.textContent = codigoManual;
                select.options[select.selectedIndex].setAttribute('data-prefijo', codigoManual);
            } else {
                prefijoSpan.textContent = '+51';
            }
        }
    }

    // Inicializar prefijo al abrir el modal usando JavaScript puro
    document.getElementById('modalNuevoHuesped').addEventListener('shown.bs.modal', function() {
        actualizarPrefijoModal();
    });

    // Registrar huésped vía AJAX (JavaScript puro)
    document.getElementById('btnRegistrarHuespedModal').addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Botón clickeado');
        
        // Limpiar errores anteriores
        document.getElementById('modalQuickErrors').style.display = 'none';
        document.getElementById('modalQuickErrorsList').innerHTML = '';
        
        // Obtener el prefijo y número de teléfono
        let prefijo = document.getElementById('prefijoModal').textContent;
        let numero = document.getElementById('telefonoModal').value.trim();
        
        // Limpiar el número (solo dígitos)
        numero = numero.replace(/\D/g, '');
        
        // Crear FormData
        let form = document.getElementById('formNuevoHuespedQuick');
        let formData = new FormData(form);
        
        // Si hay teléfono, concatenar con prefijo
        if (numero) {
            formData.set('Telefono', prefijo + ' ' + numero);
        }
        
        // Mostrar loading
        SwalLoading('Registrando huésped...');
        
        fetch('{{ route("huespedes.modalStore") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            SwalCerrar();
            
            if (data.success) {
                // Cerrar modal
                $('#modalNuevoHuesped').modal('hide');
                
                // Limpiar formulario
                form.reset();
                document.getElementById('prefijoModal').textContent = '+51';
                
                // Actualizar campos del formulario principal
                let idHuespedField = document.getElementById('IdHuesped');
                let displayField = document.getElementById('huesped_seleccionado_display');
                
                if (idHuespedField) {
                    idHuespedField.value = data.id;
                }
                if (displayField) {
                    displayField.value = data.nombre;
                }
                
                // Disparar evento de cambio
                if (idHuespedField) {
                    idHuespedField.dispatchEvent(new Event('change'));
                }
                
                // Mostrar mensaje de éxito
                SwalToast('success', 'Huésped registrado correctamente');
            }
        })
        .catch(error => {
            SwalCerrar();
            console.error('Error:', error);
            
            let errorsDiv = document.getElementById('modalQuickErrors');
            let errorsList = document.getElementById('modalQuickErrorsList');
            
            errorsList.innerHTML = '<li>Error al registrar el huésped. Intente nuevamente.</li>';
            errorsDiv.style.display = 'block';
        });
    });
    
    // También asegurar que el modal se pueda abrir correctamente
    document.querySelectorAll('[data-target="#modalNuevoHuesped"]').forEach(btn => {
        btn.addEventListener('click', function() {
            $('#modalNuevoHuesped').modal('show');
        });
    });
</script>

<style>
    .rounded-pill-left {
        border-radius: 50px 0 0 50px !important;
    }
    .rounded-pill-right {
        border-radius: 0 50px 50px 0 !important;
    }
</style>