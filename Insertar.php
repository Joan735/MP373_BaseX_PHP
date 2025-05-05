<!--  Un form que recoje los parametros de un evento y se guarda en sus respectias variables.  
      Se hace una query que inserta en un nodo el id y las variables del evento en el xml y muestra todos los eventos. -->
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Añadir Evento</title>
</head>

<body>
  <h2>Insertar Evento</h2>
  <form action="Insertar.php" method="post">
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Fecha: <input type="date" name="fecha" required></label><br>
    <label>Ubicacion: <input type="text" name="ubicacion" required></label><br>
    <label>Descripción:<br>
      <textarea name="descripcion" rows="4" cols="50" required></textarea>
    </label><br><br>
    <button type="submit" name="insertar">Insertar evento</button>
  </form>
</body>

</html>

<?php
include_once 'load.php';

use BaseXClient\BaseXException;
use BaseXClient\Session;

// Se inician las variables "nombre", "fecha", "ubicacion" y "descripcion" y se guardan.
if (!isset($_POST['nombre'])) {
  $_POST['nombre'] = "";
}
if (!isset($_POST['fecha'])) {
  $_POST['fecha'] = "";
}
if (!isset($_POST['ubicacion'])) {
  $_POST['ubicacion'] = "";
}
if (!isset($_POST['descripcion'])) {
  $_POST['descripcion'] = "";
}

$nombre = htmlspecialchars($_POST['nombre']);
$fecha = htmlspecialchars($_POST['fecha']);
$ubicacion = htmlspecialchars($_POST['ubicacion']);
$descripcion = htmlspecialchars($_POST['descripcion']);

// Si se presiona el boton insertar ocurre lo siguiente
if (isset($_POST['insertar'])) {
  try {
    $session = new Session("localhost", 1984, "admin", "admin");
    $session->execute("OPEN eventos"); // obrim la base de dades

    // Obtener el mayor ID existente y sumar 1
    $queryId = <<<XQ
XQUERY
  let \$ids :=//evento/id
  return if (empty(\$ids)) then 1 else max(\$ids) + 1
XQ;

    $id = trim($session->execute($queryId));

    // Query para insertar nuevo evento
    $xqueryInsert = <<< XQ
XQUERY
  insert node
    <evento>
      <id>$id</id>
      <nombre>$nombre</nombre>
      <fecha>$fecha</fecha>
      <ubicacion>$ubicacion</ubicacion>
      <descripcion>$descripcion</descripcion>
    </evento>
  into /conjunto_de_eventos
XQ;
    // Ejecuta la query
    $session->execute($xqueryInsert);
    echo "<p>✅ Evento insertado correctamente con ID $id.</p>";
    // Mostrar eventos actualizados
    $session->execute("SET SERIALIZER indent=yes");
    $result = $session->execute("XQUERY /conjunto_de_eventos");
    echo "<h3>📋 Eventos actuales:</h3>";
    echo "<pre>" . htmlspecialchars($result) . "</pre>";
    // Cierra la sesion
    $session->close();
  } catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
  }
}
?>