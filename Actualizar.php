<!--  Un form que recoje los parametros de un evento y se guarda en sus respectias variables. Se mira si existe ese evento con una query. 
      Si existe se hace otra query que cambia el nodo existente con otro con los datos nuevos y muestra los eventos. -->
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Actualizar Evento</title>
</head>

<body>
  <h2>Actualizar Evento por ID</h2>
  <form action="Actualizar.php" method="post">
    <label>ID: <input type="text" name="id" required></label><br>
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Fecha: <input type="date" name="fecha" required></label><br>
    <label>Ubicacion: <input type="text" name="ubicacion" required></label><br>
    <label>Descripción:<br>
      <textarea name="descripcion" rows="4" cols="50" required></textarea>
    </label><br><br>
    <button type="submit" name="actualizar">Actualizar evento</button>
  </form>
</body>

</html>

<?php
include_once 'load.php';

use BaseXClient\BaseXException;
use BaseXClient\Session;

// Se inician las variables "id", "nombre", "fecha", "ubicacion" y "descripcion" y se guardan.
if (!isset($_POST['id'])) {
  $_POST['id'] = "";
}
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

$id = htmlspecialchars($_POST['id']);
$nombre = htmlspecialchars($_POST['nombre']);
$fecha = htmlspecialchars($_POST['fecha']);
$ubicacion = htmlspecialchars($_POST['ubicacion']);
$descripcion = htmlspecialchars($_POST['descripcion']);

// Si se presiona el boton actualizar ocurre lo siguiente
if (isset($_POST['actualizar'])) {
  try {
    $session = new Session("localhost", 1984, "admin", "admin");
    $session->execute("OPEN eventos"); // obrim la base de dades

    // Verificamos si existe un evento con ese ID
    $idCheckQuery = <<<XQ
XQUERY 
count(/conjunto_de_eventos/evento[id = $id])
XQ;
    $existe = intval($session->execute($idCheckQuery));

    // Si existe el evento ocurre lo siguiente, sino muestra un mensaje de no encontrado.
    if ($existe > 0) {
      // Query para actualizar el evento
      $xqueryUpdate = <<< XQ
      XQUERY
      for \$a in ./conjunto_de_eventos/evento
      where \$a/id=$id
      return replace node \$a with
      <evento>
      <id>$id</id>
      <nombre>$nombre</nombre>
      <fecha>$fecha</fecha>
      <ubicacion>$ubicacion</ubicacion>
      <descripcion>$descripcion</descripcion>
      </evento>
      XQ;

      // Ejecuta la query
      $session->execute($xqueryUpdate);
      echo "<p>✅ Evento actualizado correctamente con ID $id.</p>";
      // Mostrar eventos actualizados
      $session->execute("SET SERIALIZER indent=yes");
      $result = $session->execute("XQUERY /conjunto_de_eventos");
      echo "<h3>📋 Eventos:</h3>";
      echo "<pre>" . htmlspecialchars($result) . "</pre>";
    } else {
      echo "<p>❌ Evento no encontrado con ID $id.</p>";
    }
    
    $session->close();
  } catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
  }
}
?>