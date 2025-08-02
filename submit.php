<?php
// Ya no necesitamos iniciar sesión para AJAX, pero lo dejamos por si se usa sin JS
session_start();
include("config/db.php");

// === Función para validar la Cédula Ecuatoriana (sin cambios) ===
function validarCedula($cedula) {
    if (!preg_match('/^\d{10}$/', $cedula)) {
        return false;
    }
    $provincia = intval(substr($cedula, 0, 2));
    if ($provincia < 1 || $provincia > 24) {
        return false;
    }
    $tercerDigito = intval($cedula[2]);
    if ($tercerDigito > 5) {
        return false;
    }
    $suma = 0;
    $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    for ($i = 0; $i < 9; $i++) {
        $num = intval($cedula[$i]) * $coeficientes[$i];
        if ($num >= 10) {
            $num -= 9;
        }
        $suma += $num;
    }
    $verificador = (10 - ($suma % 10)) % 10;
    return $verificador === intval($cedula[9]);
}

// Preparamos la respuesta que se enviará como JSON
header('Content-Type: application/json');
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];
    
    // === 1. Capturar y Validar los datos ===
    $cedula = trim($_POST['cedula']);
    if (empty($cedula)) {
        $errors[] = "La cédula es obligatoria.";
    } elseif (!validarCedula($cedula)) {
        $errors[] = "La cédula ingresada no es válida.";
    }

    $email = trim($_POST['email']);
    if (empty($email)) {
        $errors[] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El formato del email no es válido.";
    }
    
    $acepto = isset($_POST['acepto']) ? 1 : 0;
    if ($acepto === 0) {
        $errors[] = "Debe aceptar las condiciones para poder enviar la solicitud.";
    }

    // === 2. Procesar el formulario si no hay errores ===
    if (empty($errors)) {
        
        // --- PREPARACIÓN DE DATOS (AQUÍ ESTÁ LA CORRECCIÓN) ---
        // Convertimos 'edad' a null si está vacío, para evitar el error con la base de datos.
        $edad = !empty($_POST['edad']) ? intval($_POST['edad']) : null;
        
        // Ternarios para manejar campos opcionales que dependen de otros
        $tipo_discapacidad = ($_POST['discapacidad'] === 'SI') ? $_POST['tipo_discapacidad'] : '';
        $institucion_curso = ($_POST['curso_salud'] === 'SI') ? trim($_POST['institucion_curso']) : '';
        $nivel_curso = ($_POST['curso_salud'] === 'SI') ? $_POST['nivel_curso'] : '';
        $tipo_grupo_prioritario = ($_POST['grupo_prioritario'] === 'SI') ? $_POST['tipo_grupo_prioritario'] : '';


        // --- Insertar en la BD ---
        $sql = "INSERT INTO solicitudes (
            cedula, email, nombres, apellidos, nacionalidad, fecha_nacimiento,
            provincia_nacimiento, canton_nacimiento, parroquia_nacimiento,
            celular, edad, genero, estado_civil, discapacidad, tipo_discapacidad,
            carrera_aplicar, unidad_educativa, especialidad, curso_salud, institucion_curso,
            nivel_curso, sufrago, estudios_tercer_nivel, beca_senescyt, grupo_prioritario,
            tipo_grupo_prioritario, provincia_domicilio, canton_domicilio, parroquia_domicilio,
            zona, sector, calle_principal, calle_secundaria, referencia, acepto
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        
        $stmt = $conn->prepare($sql);

        if ($stmt) {
             // La cadena de tipos 'ssssssiiisisssssssssssssssiiisssssi' es correcta para 35 campos
            $stmt->bind_param("ssssssiiisisssssssssssssssiiisssssi", 
                $_POST['cedula'], $_POST['email'], $_POST['nombres'], $_POST['apellidos'], $_POST['nacionalidad'], $_POST['fecha_nacimiento'],
                $_POST['provincia_nacimiento'], $_POST['canton_nacimiento'], $_POST['parroquia_nacimiento'],
                $_POST['celular'], $edad, $_POST['genero'], $_POST['estado_civil'], $_POST['discapacidad'], 
                $tipo_discapacidad,
                $_POST['carrera_aplicar'], $_POST['unidad_educativa'], $_POST['especialidad'], $_POST['curso_salud'],
                $institucion_curso, 
                $nivel_curso,
                $_POST['sufrago'], $_POST['estudios_tercer_nivel'], $_POST['beca_senescyt'], $_POST['grupo_prioritario'],
                $tipo_grupo_prioritario,
                $_POST['provincia_domicilio'], $_POST['canton_domicilio'], $_POST['parroquia_domicilio'],
                $_POST['zona'], $_POST['sector'], $_POST['calle_principal'], $_POST['calle_secundaria'], $_POST['referencia'],
                $acepto
            );

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = '¡Solicitud enviada correctamente!';
            } else {
                $response['status'] = 'error';
                $response['errors'] = ["Error al guardar en la base de datos: " . $stmt->error];
            }
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['errors'] = ["Error en la preparación de la consulta: " . $conn->error];
        }

    } else {
        // Si hay errores de validación, los empaquetamos en la respuesta JSON
        $response['status'] = 'error';
        $response['errors'] = $errors;
    }

    $conn->close();
} else {
    $response['status'] = 'error';
    $response['errors'] = ["Método no permitido."];
}

// Devolver la respuesta JSON
echo json_encode($response);
?>