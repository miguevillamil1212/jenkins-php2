<?php
// Ejemplo sencillo de PHP + HTML
$mensaje = "¡Bienvenido a mi primera página con Docker y Jenkins!";
$fecha = date("d-m-Y H:i:s");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Página de Prueba</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background: #4CAF50;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        main {
            padding: 2rem;
        }
        .caja {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
        footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.8em;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Mi Página Web con PHP</h1>
    </header>
    <main>
        <div class="caja">
            <h2><?php echo $mensaje; ?></h2>
            <p>Hoy es <strong><?php echo $fecha; ?></strong></p>
            <p>Este archivo fue cargado dentro del contenedor usando <em>Docker Compose</em> y puede ser administrado con <em>Jenkins</em>.</p>
            <p>✔ Demostración de HTML + PHP funcionando correctamente.</p>
        </div>
    </main>
    <footer>
        <p>Desarrollado con ❤️ usando PHP y Docker</p>
    </footer>
</body>
</html>
