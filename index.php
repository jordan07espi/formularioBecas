<?php 
// --- OPTIMIZACIÓN: Se carga la conexión y la lista de provincias una sola vez ---
include("config/db.php"); 
$provincias_res = $conn->query("SELECT * FROM tbl_provincia ORDER BY provincia");
$provincias = [];
if ($provincias_res) {
    while($row = $provincias_res->fetch_assoc()) {
        $provincias[] = $row;
    }
}
include("includes/header.php"); 
?>

<div class="container mt-5">
    <h2 class="mb-4">Formulario de Postulación a Beca</h2>

    <?php
    // Iniciar sesión para poder leer los mensajes
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Mostrar mensajes de error si existen (para envíos sin JavaScript)
    if (isset($_SESSION['form_errors'])) {
        echo '<div class="alert alert-danger">';
        echo '<strong>Por favor, corrija los siguientes errores:</strong><ul>';
        foreach ($_SESSION['form_errors'] as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
        unset($_SESSION['form_errors']);
    }

    // Mostrar mensaje de éxito si existe (para envíos sin JavaScript)
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    ?>


    <form action="submit.php" method="POST">
        <h4 class="bg-warning text-white p-2 rounded">Información Personal</h4>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Cédula</label>
                <input type="text" name="cedula" class="form-control" required placeholder="Ingrese su número de cédula">
            </div>
            <div class="col-md-4 mb-3">
                <label>Correo</label>
                <input type="email" name="email" class="form-control" required placeholder="Ingrese su correo">
            </div>
            <div class="col-md-4 mb-3">
                <label>Celular</label>
                <input type="text" name="celular" class="form-control" required placeholder="Ej. 09XXXXXXXX">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombres</label>
                <input type="text" name="nombres" class="form-control" placeholder="Ingrese sus nombres completos" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control" placeholder="Ingrese sus apellidos completos" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Nacionalidad</label>
                <select name="nacionalidad" id="nacionalidad" class="form-control">
                    <option value="">Seleccione Nacionalidad</option>
                    <option value="Ecuatoriana">ECUATORIANA</option>
                    <option value="Extranjera">EXTRANJERA</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Edad</label>
                <input type="number" name="edad" class="form-control" readonly style="background-color: #e9ecef;">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Provincia de Nacimiento</label>
                <select id="provincia_nacimiento" name="provincia_nacimiento" class="form-control" required>
                    <option value="">Seleccione Provincia</option>
                    <?php
                    // --- OPTIMIZACIÓN: Se reutiliza la variable $provincias
                    foreach ($provincias as $prov) {
                        echo "<option value='".$prov['id']."'>".$prov['provincia']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Cantón de Nacimiento</label>
                <select id="canton_nacimiento" name="canton_nacimiento" class="form-control" required>
                    <option value="">Seleccione Cantón</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Parroquia de Nacimiento</label>
                <select id="parroquia_nacimiento" name="parroquia_nacimiento" class="form-control" required>
                    <option value="">Seleccione Parroquia</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Género</label>
                <select name="genero" class="form-control">
                    <option value="">Seleccione Género</option>
                    <option>MASCULINO</option>
                    <option>FEMENINO</option>
                    <option>OTRO</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Estado Civil</label>
                <select name="estado_civil" class="form-control">
                    <option value="">Seleccione Estado Civil</option>
                    <option>SOLTERO</option>
                    <option>CASADO</option>
                    <option>DIVORCIADO</option>
                    <option>VIUDO</option>
                    <option>UNIÓN LIBRE</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>¿Tiene discapacidad?</label>
                <select name="discapacidad" class="form-control">
                    <option value="Seleccione">Seleccione</option>
                    <option>NO</option>
                    <option>SI</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Tipo de Discapacidad</label>
            <select name="tipo_discapacidad" id="tipo_discapacidad" class="form-control">
                <option value="">Seleccione Tipo de Discapacidad</option>
                <option value="Visual">VISUAL</option>
                <option value="Auditiva">AUDITIVA</option>
                <option value="Motora">MOTORA</option>
                <option value="Intelectual">INTELECTUAL</option>
                <option value="Psicosocial">PSICOSOCIAL</option>
                <option value="Sordoceguera">SORDOCEGUERA</option>
                <option value="Otra">OTRA</option>
            </select>
        </div>

        <h4 class="bg-primary text-white p-2 rounded">Información Académica</h4>

        <div class="mb-3">
            <label>Carrera a la que quiere aplicar</label>
            <select name="carrera_aplicar" id="carrera_aplicar" class="form-control">
                <option value="">Seleccione Carrera</option>
                <option value="ENFERMERIA">ENFERMERIA</option>
                <option value="EMERGENCIAS MEDICAS">EMERGENCIAS MEDICAS</option>
                <option value="EDUCACIÓN INICIAL">EDUCACIÓN INICIAL</option>
                <option value="MARKETING DIGITAL Y COMERCIO ELECTRONICO - ONLINE">MARKETING DIGITAL Y COMERCIO ELECTRONICO - ONLINE</option>
                <option value="ADMINISTRACIÓN- ONLINE">ADMINISTRACIÓN- ONLINE</option>
                <option value="REHABILITACIÓN FISICA">REHABILITACIÓN FISICA</option>
                <option value="GASTRONOMIA">GASTRONOMIA</option>
                <option value="MECANICA AUTOMOTRIZ">MECANICA AUTOMOTRIZ</option>
                <option value="LABORATORIO">LABORATORIO</option>
                <option value="NATUROPATIA">NATUROPATIA</option>
                <option value="ADMINISTRACIÓN DE SISTEMAS DE LA SALUD">ADMINISTRACIÓN DE SISTEMAS DE LA SALUD</option>
                <option value="ADMINISTRACIÓN DE FARMACIAS">ADMINISTRACIÓN DE FARMACIAS</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Unidad Educativa donde se graduó</label>
            <input type="text" name="unidad_educativa" class="form-control" placeholder="Ingrese el nombre de su unidad educativa" required>
        </div>

        <div class="mb-3">
            <label>Especialidad</label>
            <select name="especialidad" id="especialidad" class="form-control">
                <option value="">Seleccione Especialidad</option>
                <option value="Bachillerato en Ciencias">BACHILLERATO EN CIENCIAS</option>
                <option value="Bachillerato Técnico">BACHILLERATO TÉCNICO</option>
                <option value="Bachillerato en Artes">BACHILLERATO EN ARTES</option>
                <option value="Bachillerato en Humanidades y Ciencias Sociales">BACHILLERATO EN HUMANIDADES Y CIENCIAS SOCIALES</option>
                <option value="Bachillerato Deportivo">BACHILLERATO DEPORTIVO</option>
                <option value="Bachillerato Internacional">BACHILLERATO INTERNACIONAL</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>¿Ha realizado curso de salud?</label>
                <select name="curso_salud" class="form-control">
                    <option value="Seleccione">Seleccione</option>
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Institución donde realizó el curso</label>
                <input type="text" name="institucion_curso" class="form-control" placeholder="Ingrese el nombre de la institución" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Nivel del curso</label>
                <select name="nivel_curso" class="form-control">
                    <option value="">Seleccione Nivel</option>
                    <option value="Primero">PRIMERO</option>
                    <option value="Segundo">SEGUNDO</option>
                    <option value="Tercero">TERCERO</option>
                    <option value="Cuarto">CUARTO</option>
                    <option value="Quinto">QUINTO</option>
                    <option value="Sexto">SEXTO</option>
                    <option value="Séptimo">SÉPTIMO</option>
                    <option value="Octavo">OCTAVO</option>
                    <option value="Egresado">EGRESADO</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>¿Sufragó en las últimas elecciones?</label>
                <select name="sufrago" class="form-control">
                    <option value="Seleccione">Seleccione</option>
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>¿Estudia o iniciará estudios de tercer nivel?</label>
                <select name="estudios_tercer_nivel" class="form-control">
                    <option value="Seleccione">Seleccione</option>
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>¿Recibe beca del Senescyt?</label>
                <select name="beca_senescyt" class="form-control">
                    <option value="Seleccione">Seleccione</option>
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>¿Pertenece a un grupo prioritario?</label>
                <select name="grupo_prioritario" class="form-control">
                    <option value="Seleccione">Seleccione</option>
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Tipo de Grupo Prioritario</label>
                <select name="tipo_grupo_prioritario" class="form-control">
                    <option value="">Seleccione una opción</option>
                    <option value="ViolenciaGenero">VÍCTIMA DE VIOLENCIA DE GÉNERO</option>
                    <option value="Discapacidad">PERSONA CON DISCAPACIDAD PERMANENTE</option>
                    <option value="Deportista">DEPORTISTA DE NIVEL FORMATIVO O ALTO RENDIMIENTO</option>
                    <option value="HeroeNacional">BENEFICIARIO DE LA LEY DE HÉROES NACIONALES</option>
                    <option value="VulnerabilidadEconomica">CONDICIÓN DE VULNERABILIDAD ECONÓMICA</option>
                    <option value="Retornado">ECUATORIANO RETORNADO EN SITUACIÓN VULNERABLE</option>
                    <option value="PueblosOriginarios">PUEBLOS O NACIONALIDADES ECUATORIANAS</option>
                    <option value="ZonaViolencia">RESIDENTE EN ZONAS CON ALTA VIOLENCIA DELICTUAL</option>
                </select>
            </div>
        </div>

        <h4 class="bg-danger text-white p-2 rounded">Información de Domicilio</h4>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Provincia</label>
                <select id="provincia_domicilio" name="provincia_domicilio" class="form-control" required>
                    <option value="">Seleccione Provincia</option>
                    <?php
                    // --- OPTIMIZACIÓN: Se reutiliza la variable $provincias
                    foreach ($provincias as $prov) {
                        echo "<option value='".$prov['id']."'>".$prov['provincia']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Cantón</label>
                <select id="canton_domicilio" name="canton_domicilio" class="form-control" required>
                    <option value="">Seleccione Cantón</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Parroquia</label>
                <select id="parroquia_domicilio" name="parroquia_domicilio" class="form-control" required>
                    <option value="">Seleccione Parroquia</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Zona</label>
                <select name="zona" class="form-control">
                    <option>URBANA</option>
                    <option>RURAL</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Sector</label>
                <input type="text" name="sector" class="form-control" placeholder="Ingrese el sector de su domicilio">
            </div>
            <div class="col-md-4 mb-3">
                <label>Calle Principal</label>
                <input type="text" name="calle_principal" class="form-control" placeholder="Ingrese la calle principal de su domicilio">
            </div>
        </div>

        <div class="mb-3">
            <label>Calle Secundaria</label>
            <input type="text" name="calle_secundaria" class="form-control" placeholder="Ingrese la calle secundaria de su domicilio">
        </div>

        <div class="mb-3">
            <label>Referencia Domiciliaria</label>
            <textarea name="referencia" class="form-control" placeholder="Ingrese una referencia domiciliaria."></textarea>
        </div>

        <div class="p-3 my-4 border rounded bg-light text-center shadow-sm">
            <div class="form-check d-inline-block custom-checkbox">
                <input type="checkbox" name="acepto" id="acepto" class="form-check-input" required>
                <label class="form-check-label fw-bold" for="acepto" style="font-size: 1.1rem;">
                    ACEPTO LAS CONDICIONES DE TRATAMIENTO DE DATOS
                </label>
            </div>
        </div>

        <div id="form-messages" class="mt-3"></div>
        <button type="submit" class="btn btn-success">Enviar Solicitud</button>
    </form>
</div>

<?php include("includes/footer.php"); ?>