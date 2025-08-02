document.addEventListener("DOMContentLoaded", function () {

    // === Función reutilizable para cargar datos vía AJAX ===
    function cargarDatos(url, selectDestino, limpiarSelect = null) {
        fetch(url)
            .then(response => {
                // Si la respuesta no es OK (ej. 404), muestra un error en la consola
                if (!response.ok) {
                    console.error("Error en la petición AJAX:", response.statusText);
                }
                return response.text();
            })
            .then(data => {
                selectDestino.innerHTML = data;
                if (limpiarSelect) limpiarSelect.innerHTML = "<option value=''>Seleccione Parroquia</option>";
            });
    }

    // === 1. Campos para NACIMIENTO ===
    const provinciaNac = document.getElementById("provincia_nacimiento");
    const cantonNac = document.getElementById("canton_nacimiento");
    const parroquiaNac = document.getElementById("parroquia_nacimiento");

    provinciaNac.addEventListener("change", function () {
        let id = this.value;
        if (id) {
            // RUTA CORREGIDA Y MÁS SEGURA
            cargarDatos(`/formulariobecas/ajax/get_cantones.php?id_provincia=${id}`, cantonNac, parroquiaNac);
        } else {
            cantonNac.innerHTML = "<option value=''>Seleccione Cantón</option>";
            parroquiaNac.innerHTML = "<option value=''>Seleccione Parroquia</option>";
        }
    });

    cantonNac.addEventListener("change", function () {
        let id = this.value;
        if (id) {
            // RUTA CORREGIDA Y MÁS SEGURA
            cargarDatos(`/formulariobecas/ajax/get_parroquias.php?id_canton=${id}`, parroquiaNac);
        } else {
            parroquiaNac.innerHTML = "<option value=''>Seleccione Parroquia</option>";
        }
    });

    // === 2. Campos para DOMICILIO ===
    const provinciaDom = document.getElementById("provincia_domicilio");
    const cantonDom = document.getElementById("canton_domicilio");
    const parroquiaDom = document.getElementById("parroquia_domicilio");

    provinciaDom.addEventListener("change", function () {
        let id = this.value;
        if (id) {
            // RUTA CORREGIDA Y MÁS SEGURA
            cargarDatos(`/formulariobecas/ajax/get_cantones.php?id_provincia=${id}`, cantonDom, parroquiaDom);
        } else {
            cantonDom.innerHTML = "<option value=''>Seleccione Cantón</option>";
            parroquiaDom.innerHTML = "<option value=''>Seleccione Parroquia</option>";
        }
    });

    cantonDom.addEventListener("change", function () {
        let id = this.value;
        if (id) {
            // RUTA CORREGIDA Y MÁS SEGURA
            cargarDatos(`/formulariobecas/ajax/get_parroquias.php?id_canton=${id}`, parroquiaDom);
        } else {
            parroquiaDom.innerHTML = "<option value=''>Seleccione Parroquia</option>";
        }
    });

});