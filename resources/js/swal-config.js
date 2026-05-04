/**
 * SweetAlert2 - Configuración Global
 * Funciones reutilizables para alertas en todo el sistema
 */
import Swal from 'sweetalert2';
// ============================================
// 1. ALERTA DE CONFIRMACIÓN (Sí / No)
// ============================================
// Uso: SwalConfirmacion('Título', 'Texto', 'warning', 'Sí, eliminar')
function SwalConfirmacion(titulo, texto, icono = 'warning', confirmarTexto = 'Confirmar') {
    return Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        showCancelButton: true,
        confirmButtonColor: '#dc3545',  // Rojo para acciones peligrosas
        cancelButtonColor: '#6c757d',   // Gris para cancelar
        confirmButtonText: confirmarTexto,
        cancelButtonText: 'Cancelar',
        reverseButtons: true,            // Cancelar a la izquierda, Confirmar a la derecha
        borderRadius: '15px',
        customClass: {
            popup: 'swal-rounded'
        }
    });
}

// ============================================
// 2. ALERTA DE CONFIRMACIÓN VERDE (para acciones positivas)
// ============================================
// Uso: SwalConfirmacionVerde('Título', 'Texto', 'Sí, guardar')
function SwalConfirmacionVerde(titulo, texto, confirmarTexto = 'Confirmar') {
    return Swal.fire({
        title: titulo,
        text: texto,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',  // Verde para acciones positivas
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmarTexto,
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        borderRadius: '15px'
    });
}

// ============================================
// 3. TOAST (Notificación rápida - esquina superior derecha)
// ============================================
// Uso: SwalToast('success', 'Registro guardado', 3000)
function SwalToast(icono, titulo, tiempo = 3000) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: tiempo,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: icono,
        title: titulo
    });
}

// ============================================
// 4. ALERTA DE ERROR (con botón de cerrar)
// ============================================
// Uso: SwalError('Error', 'Ocurrió un problema')
function SwalError(titulo, texto) {
    return Swal.fire({
        title: titulo,
        text: texto,
        icon: 'error',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#dc3545',
        borderRadius: '15px'
    });
}

// ============================================
// 5. ALERTA DE ÉXITO (con botón de cerrar)
// ============================================
// Uso: SwalExito('Éxito', 'Operación completada')
function SwalExito(titulo, texto) {
    return Swal.fire({
        title: titulo,
        text: texto,
        icon: 'success',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#28a745',
        borderRadius: '15px'
    });
}

// ============================================
// 6. ALERTA DE CARGA (para procesos lentos)
// ============================================
// Uso: SwalLoading('Guardando...')
function SwalLoading(texto = 'Procesando...') {
    return Swal.fire({
        title: texto,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// ============================================
// 7. CERRAR ALERTA ACTUAL
// ============================================
function SwalCerrar() {
    Swal.close();
}

// ============================================
// 8. DETECCIÓN AUTOMÁTICA DE MENSAJES FLASH DE LARAVEL
// ============================================
// Esto busca mensajes de sesión y muestra el toast correspondiente
function iniciarDetectorFlashMessages() {
    // Detectar mensaje de éxito
    if (typeof flashSuccess !== 'undefined' && flashSuccess) {
        SwalToast('success', flashSuccess, 4000);
    }
    
    // Detectar mensaje de error
    if (typeof flashError !== 'undefined' && flashError) {
        SwalToast('error', flashError, 5000);
    }
    
    // Detectar mensaje de advertencia
    if (typeof flashWarning !== 'undefined' && flashWarning) {
        SwalToast('warning', flashWarning, 4000);
    }
    
    // Detectar mensaje de información
    if (typeof flashInfo !== 'undefined' && flashInfo) {
        SwalToast('info', flashInfo, 3000);
    }
}

// ============================================
// 9. FUNCIÓN PARA ELIMINAR REGISTRO (Patrón común)
// ============================================
// Uso: confirmarEliminacion(formId, 'Habitación 101')
function confirmarEliminacion(formId, nombreRegistro) {
    SwalConfirmacion(
        '¿Eliminar ' + nombreRegistro + '?',
        'Esta acción no se puede deshacer',
        'warning',
        'Sí, eliminar'
    ).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// ============================================
// 10. FUNCIÓN PARA CAMBIAR ESTADO CON CONFIRMACIÓN
// ============================================
// Uso: confirmarCambioEstado(formId, 'liberar', 'Habitación 101')
function confirmarCambioEstado(formId, accion, nombreRegistro) {
    let titulo = '';
    let texto = '';
    
    if (accion === 'liberar') {
        titulo = '¿Habilitar ' + nombreRegistro + '?';
        texto = 'La habitación pasará a estado DISPONIBLE.';
    } else if (accion === 'mantenimiento') {
        titulo = '¿Poner en mantenimiento ' + nombreRegistro + '?';
        texto = 'La habitación no estará disponible para reservas.';
    } else {
        titulo = '¿Cambiar estado de ' + nombreRegistro + '?';
        texto = '¿Estás seguro de realizar esta acción?';
    }
    
    SwalConfirmacion(titulo, texto, 'warning', 'Sí, continuar').then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Inicializar detector de mensajes flash cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    iniciarDetectorFlashMessages();
});


// ============================================
// EXPORTAR FUNCIONES AL OBJETO WINDOW (GLOBAL)
// ============================================
window.SwalConfirmacion = SwalConfirmacion;
window.SwalConfirmacionVerde = SwalConfirmacionVerde;
window.SwalToast = SwalToast;
window.SwalError = SwalError;
window.SwalExito = SwalExito;
window.SwalLoading = SwalLoading;
window.SwalCerrar = SwalCerrar;
window.confirmarEliminacion = confirmarEliminacion;
window.confirmarCambioEstado = confirmarCambioEstado;
window.iniciarDetectorFlashMessages = iniciarDetectorFlashMessages;