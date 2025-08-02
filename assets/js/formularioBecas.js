document.addEventListener('DOMContentLoaded', function() {
    // Llamamos a todas las funciones de inicialización desde un único punto.
    iniciarValidadorDeCedula();
    iniciarLogicaDiscapacidad();
    iniciarLogicaCursoSalud();
    iniciarLogicaGrupoPrioritario();
    iniciarValidadorDeCelular();
    iniciarEnvioConAJAX(); 
    iniciarCalculoDeEdad();
    iniciarConversionMayusculas();
    iniciarValidacionTexto(); 
});

/**
 * Valida y da sugerencias para campos de texto (Nombres y Apellidos).
 * - Muestra un ERROR (rojo) si contiene números.
 * - Muestra una SUGERENCIA (naranja) si solo hay una palabra.
 */
function iniciarValidacionTexto() {
    const campos = ['nombres', 'apellidos'];

    campos.forEach(function(nombreCampo) {
        const input = document.querySelector(`[name="${nombreCampo}"]`);
        if (!input) return;

        // Contenedor para mensajes de ERROR (rojo)
        let errorMsg = document.createElement('div');
        errorMsg.style.color = 'red';
        errorMsg.style.fontSize = '0.9em';
        errorMsg.style.marginTop = '4px';
        input.parentNode.appendChild(errorMsg);

        // Contenedor para mensajes de SUGERENCIA (naranja)
        let advertenciaMsg = document.createElement('div');
        advertenciaMsg.style.color = '#fd7e14'; // Color naranja de Bootstrap
        advertenciaMsg.style.fontSize = '0.9em';
        advertenciaMsg.style.marginTop = '4px';
        input.parentNode.appendChild(advertenciaMsg);

        input.addEventListener('input', function() {
            // Limpiar mensajes y validación previos
            input.setCustomValidity("");
            errorMsg.textContent = "";
            advertenciaMsg.textContent = "";
            
            const valor = this.value.trim();
            
            // 1. Validación de ERROR: No debe contener números.
            if (/\d/.test(valor)) {
                errorMsg.textContent = "Este campo no puede contener números.";
                input.setCustomValidity("Contenido inválido.");
                return; // Si hay error, no mostramos la sugerencia
            }

            // 2. Validación de SUGERENCIA: Avisar si solo hay una palabra.
            const palabras = valor.split(/\s+/).filter(Boolean); // Divide por espacios y elimina elementos vacíos

            if (palabras.length === 1) {
                const tipo = nombreCampo === 'nombres' ? 'nombres' : 'apellidos';
                advertenciaMsg.textContent = `Sugerencia: Si tiene dos ${tipo}, por favor ingréselos.`;
            }
        });
    });
}


/**
 * Agrega la validación para el campo de celular, ahora con un mensaje más específico.
 */
function iniciarValidadorDeCelular() {
    const celularInput = document.querySelector('input[name="celular"]');
    if (!celularInput) return;

    let mensajeTel = document.createElement('div');
    mensajeTel.style.color = 'red';
    mensajeTel.style.fontSize = '0.9em';
    mensajeTel.style.marginTop = '4px';
    celularInput.parentNode.appendChild(mensajeTel);

    celularInput.addEventListener('input', function() {
        celularInput.setCustomValidity("");
        mensajeTel.textContent = "";

        const celular = celularInput.value.trim();
        if (celular.length === 0) return;

        if (/[^0-9]/.test(celular)) { 
            mensajeTel.textContent = "El celular solo puede contener números.";
            celularInput.setCustomValidity("Solo se admiten números.");
        } else if (celular.length !== 10) {
            mensajeTel.textContent = "El celular debe tener exactamente 10 dígitos.";
            celularInput.setCustomValidity("Longitud incorrecta.");
        } else if (!celular.startsWith("09")) {
            mensajeTel.textContent = "El celular debe comenzar con 09.";
            celularInput.setCustomValidity("Prefijo incorrecto.");
        }
    });
}


/**
 * Convierte a mayúsculas el texto de los campos designados mientras el usuario escribe.
 */
function iniciarConversionMayusculas() {
    const camposAMayusculas = [
        'nombres', 
        'apellidos', 
        'unidad_educativa', 
        'institucion_curso',
        'sector',
        'calle_principal',
        'calle_secundaria',
        'referencia'
    ];

    camposAMayusculas.forEach(function(nombreCampo) {
        const input = document.querySelector(`[name="${nombreCampo}"]`);
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });
}

/**
 * Calcula y autocompleta la edad basándose en la fecha de nacimiento.
 */
function iniciarCalculoDeEdad() {
    const fechaNacimientoInput = document.querySelector('input[name="fecha_nacimiento"]');
    const edadInput = document.querySelector('input[name="edad"]');

    if (!fechaNacimientoInput || !edadInput) return;

    fechaNacimientoInput.addEventListener('change', function() {
        const fechaNac = this.value;
        if (!fechaNac) {
            edadInput.value = "";
            return;
        }

        const hoy = new Date();
        const nacimiento = new Date(fechaNac);

        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();

        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }

        edadInput.value = edad >= 0 ? edad : "";
    });
}


/**
 * Agrega la validación en tiempo real para el campo de la cédula.
 */
function iniciarValidadorDeCedula() {
    const cedulaInput = document.querySelector('input[name="cedula"]');
    if (!cedulaInput) return;

    let mensaje = document.createElement('div');
    mensaje.style.color = 'red';
    mensaje.style.fontSize = '0.9em';
    mensaje.style.marginTop = '4px';
    cedulaInput.parentNode.appendChild(mensaje);

    cedulaInput.addEventListener('input', function() {
        cedulaInput.setCustomValidity(""); 
        mensaje.textContent = "";

        const cedula = cedulaInput.value.trim();
        if (cedula.length === 0) return;
        
        if (!/^\d{10}$/.test(cedula)) {
            mensaje.textContent = "La cédula debe tener exactamente 10 dígitos y solo números.";
            cedulaInput.setCustomValidity("Cédula incorrecta.");
            return;
        }

        const provincia = parseInt(cedula.substring(0, 2), 10);
        if (provincia < 1 || provincia > 24) {
            mensaje.textContent = "Provincia no válida (dos primeros dígitos).";
            cedulaInput.setCustomValidity("Cédula incorrecta.");
            return;
        }

        const tercerDigito = parseInt(cedula[2], 10);
        if (tercerDigito > 5) {
            mensaje.textContent = "El tercer dígito no es válido.";
            cedulaInput.setCustomValidity("Cédula incorrecta.");
            return;
        }

        let suma = 0;
        for (let i = 0; i < 9; i++) {
            let num = parseInt(cedula[i]);
            if (i % 2 === 0) {
                num *= 2;
                if (num > 9) num -= 9;
            }
            suma += num;
        }
        const verificador = (10 - (suma % 10)) % 10;

        if (verificador !== parseInt(cedula[9])) {
            mensaje.textContent = "La cédula no es válida (dígito verificador incorrecto).";
            cedulaInput.setCustomValidity("La cédula no es válida.");
        }
    });
}

/**
 * Oculta o muestra el campo "Tipo de Discapacidad" según la selección.
 */
function iniciarLogicaDiscapacidad() {
    const discapacidadSelect = document.querySelector('select[name="discapacidad"]');
    const tipoDiscapacidadInput = document.querySelector('select[name="tipo_discapacidad"]');

    if (!discapacidadSelect) return;

    function toggleTipoDiscapacidad() {
        if (discapacidadSelect.value === "NO" || discapacidadSelect.value === "Seleccione") {
            tipoDiscapacidadInput.value = "";
            tipoDiscapacidadInput.setAttribute("disabled", "disabled");
        } else {
            tipoDiscapacidadInput.removeAttribute("disabled");
        }
    }

    discapacidadSelect.addEventListener('change', toggleTipoDiscapacidad);
    toggleTipoDiscapacidad(); 
}

/**
 * Oculta o muestra los campos relacionados al curso de salud.
 */
function iniciarLogicaCursoSalud() {
    const cursoSaludSelect = document.querySelector('select[name="curso_salud"]');
    const institucionCursoInput = document.querySelector('input[name="institucion_curso"]');
    const nivelCursoSelect = document.querySelector('select[name="nivel_curso"]');

    if (!cursoSaludSelect) return;

    function toggleCursoSaludFields() {
        if (cursoSaludSelect.value === "NO" || cursoSaludSelect.value === "Seleccione") {
            institucionCursoInput.value = "";
            institucionCursoInput.setAttribute("disabled", "disabled");
            nivelCursoSelect.value = "";
            nivelCursoSelect.setAttribute("disabled", "disabled");
        } else {
            institucionCursoInput.removeAttribute("disabled");
            nivelCursoSelect.removeAttribute("disabled");
        }
    }

    cursoSaludSelect.addEventListener('change', toggleCursoSaludFields);
    toggleCursoSaludFields();
}

/**
 * Oculta o muestra el campo "Tipo de Grupo Prioritario".
 */
function iniciarLogicaGrupoPrioritario() {
    const prioridadSelect = document.querySelector('select[name="grupo_prioritario"]');
    const tipoPrioridadInput = document.querySelector('select[name="tipo_grupo_prioritario"]');

    if (!prioridadSelect) return;

    function toggleTipoPrioridad() {
        if (prioridadSelect.value === "NO" || prioridadSelect.value === "Seleccione") {
            tipoPrioridadInput.value = "";
            tipoPrioridadInput.setAttribute("disabled", "disabled");
        } else {
            tipoPrioridadInput.removeAttribute("disabled");
        }
    }

    prioridadSelect.addEventListener('change', toggleTipoPrioridad);
    toggleTipoPrioridad();
}


/**
 * Intercepta el envío del formulario para manejarlo con AJAX (fetch).
 */
function iniciarEnvioConAJAX() {
    const form = document.querySelector('form[action="submit.php"]');
    if (!form) return;

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const messageContainer = document.getElementById('form-messages');
        messageContainer.innerHTML = '<div class="alert alert-info">Enviando solicitud...</div>';
        
        const formData = new FormData(form);

        fetch('submit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                messageContainer.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                form.reset();
                
                document.getElementById('canton_nacimiento').innerHTML = '<option value="">Seleccione Cantón</option>';
                document.getElementById('parroquia_nacimiento').innerHTML = '<option value="">Seleccione Parroquia</option>';
                document.getElementById('canton_domicilio').innerHTML = '<option value="">Seleccione Cantón</option>';
                document.getElementById('parroquia_domicilio').innerHTML = '<option value="">Seleccione Parroquia</option>';
            } else {
                let errorHtml = '<strong>Por favor, corrija los siguientes errores:</strong><ul>';
                data.errors.forEach(error => {
                    errorHtml += `<li>${error}</li>`;
                });
                errorHtml += '</ul>';
                messageContainer.innerHTML = `<div class="alert alert-danger">${errorHtml}</div>`;
            }
        })
        .catch(error => {
            messageContainer.innerHTML = '<div class="alert alert-danger">Error de conexión. Por favor, intente más tarde.</div>';
            console.error('Error:', error);
        });
    });
}