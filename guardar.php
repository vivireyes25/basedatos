<?php
// Configuración de la conexión a la base de datos en XAMPP
$host     = "localhost";
$usuario  = "root";      // Usuario por defecto en XAMPP
$password = "";          // Contraseña por defecto en XAMPP (vacía)
$database = "basedatos";    // Nombre de la base de datos
 
// 1. Crear conexión
$conexion = new mysqli($host, $usuario, $password, $database);
 
// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}
 
// Configurar conjunto de caracteres UTF-8
$conexion->set_charset("utf8mb4");
 
// 2. Comprobar si los datos se enviaron vía POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Recibir y limpiar los campos enviados
    $id       = trim($_POST['id']);
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
 
    // Validar que ningún campo esté vacío
    if (!empty($id) && !empty($nombre) && !empty($email) && !empty($telefono)) {
 
        // Prepared Statement para prevenir inyecciones SQL
        $stmt = $conexion->prepare("INSERT INTO datos (id, nombre, email, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $nombre, $email, $telefono);
 
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: sans-serif; background: #121212; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                    .card { background: #1e1e2e; padding: 30px; border-radius: 12px; border: 1px solid #444; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
                    .card h2 { color: #4caf50; margin-bottom: 15px; }
                    .card a { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #6200ee; color: white; text-decoration: none; border-radius: 6px; }
                    .card a:hover { background: #3700b3; }
                </style>
            </head>
            <body>
                <div class='card'>
                    <h2>¡Información guardada exitosamente!</h2>
                    <p>Los datos han sido almacenados en la tabla <b>datos</b> de la base de datos <b>Bodega</b>.</p>
                    <a href='javascript:history.back()'>Regresar al Formulario</a>
                </div>
            </body>
            </html>";
        } else {
            echo "<p>Error al guardar la información: " . $stmt->error . "</p>";
        }
 
        $stmt->close();
    } else {
        echo "<p>Por favor, llena todos los campos obligatorios.</p>";
    }
}
 
// Cerrar conexión
$conexion->close();
?>