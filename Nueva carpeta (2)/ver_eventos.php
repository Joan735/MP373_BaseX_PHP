<?php
include_once 'load.php';

use BaseXClient\BaseXException;
use BaseXClient\Session;

try {
    // Conectar a BaseX
    $session = new Session("localhost", 1984, "admin", "admin");

    // Ejecutar la consulta XQuery para obtener todos los eventos
   
    $session->execute("open eventos"); // obrim la base de dades
    $input = 'for $a in ./eventos return $a';// creem la query FLOWR
    $query = $session->query($input); // executem la query
    // Cerrar la sesión en BaseX
    $session->close();

    // Convertir la cadena XML en un objeto SimpleXML para poder recorrerlo
    $xml = simplexml_load_string($query);
} catch (Exception $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Eventos</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        form { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Lista de Eventos</h2>

    <!-- Formulario para refrescar la lista -->
    <form action="ver_eventos.php" method="post">
        <button type="submit">Refrescar lista</button>
    </form>

    <?php if($xml && isset($xml->evento)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Descripción</th>
            </tr>
            <?php foreach($xml->evento as $evento): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evento->id); ?></td>
                    <td><?php echo htmlspecialchars($evento->nombre); ?></td>
                    <td><?php echo htmlspecialchars($evento->fecha); ?></td>
                    <td><?php echo htmlspecialchars($evento->descripcion); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No hay eventos registrados.</p>
    <?php endif; ?>
</body>
</html>
