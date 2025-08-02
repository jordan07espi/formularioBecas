<?php
// Puedes agregar configuración adicional aquí si es necesario
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulación a Beca</title>
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos opcionales -->
    <style>
        body {
            background: #f8f9fa;
        }
        h2, h4 {
            font-weight: bold;
        }

        /* ==== INICIA CÓDIGO PARA CHECKBOX PERSONALIZADO ==== */

        /* Contenedor principal para alinear nuestro nuevo checkbox */
        .custom-checkbox .form-check-label {
            position: relative;
            padding-left: 2.2em; /* Espacio para el nuevo checkbox */
            line-height: 1.5em;  /* Alinea verticalmente el texto */
        }

        /* Ocultamos el checkbox original, pero lo dejamos funcional */
        .custom-checkbox .form-check-input {
            opacity: 0;
            position: absolute;
        }
        
        /* Creamos la apariencia del recuadro del checkbox */
        .custom-checkbox .form-check-label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5em;
            height: 1.5em;
            border: 2px solid #0d6efd; /* Borde siempre de color primario */
            background-color: #fff;
            border-radius: 0.25em;
            transition: background-color 0.2s, box-shadow 0.2s; /* Transición suave */
        }

        /* EFECTO HOVER: Sombra azul al pasar el mouse */
        .custom-checkbox .form-check-label:hover::before {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Cuando el checkbox está MARCADO, cambiamos el fondo */
        .custom-checkbox .form-check-input:checked + .form-check-label::before {
            background-color: #0d6efd; /* Fondo primario */
        }

        /* Creamos la marca de verificación (el "check") que aparece cuando está marcado */
        .custom-checkbox .form-check-input:checked + .form-check-label::after {
            content: '';
            position: absolute;
            left: 0.5em;
            top: 0.2em;
            width: 0.5em;
            height: 1em;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        /* ==== FINALIZA CÓDIGO PARA CHECKBOX PERSONALIZADO ==== */

    </style>
</head>
<body>
    <!-- Navbar opcional -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Postulación a Becas</a>
        </div>
    </nav>
