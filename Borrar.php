<!--  Un form recoje el id de un evento y se guarda en su variable. Si existe se hace otra query que elimina ese evento y muestra todos los eventos. -->
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Borrar Evento</title>
</head>

<body>
  <h2>Borrar Evento por ID</h2>
  <form action="Borrar.php" method="post">
    <label>ID: <input type="text" name="id" required></label><br>
    <br><br>
    <button type="submit" name="borrar">Borrar evento</button>
  </form>
</body>

</html>

<?php
include_once 'load.php';

use BaseXClient\BaseXException;
use BaseXClient\Session;

// Se inicia la variables "id" y se guarda.
if (!isset($_POST['id'])) {
  $_POST['id'] = "";
}

$id = htmlspecialchars($_POST['id']);

// Si se presiona el boton borrar ocurre lo siguiente
if (isset($_POST['borrar'])) {
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
      // Query para borrar evento 
      $xqueryDelete = <<< XQ
XQUERY
  for \$a in //evento
  where \$a/id="$id"
  return delete node \$a
XQ;
      // Ejecuta la query
      $session->execute($xqueryDelete);
      echo "<p>✅ Evento borrado correctamente con ID $id.</p>";
      // Mostrar eventos actualizados
      $session->execute("SET SERIALIZER indent=yes");
      $result = $session->execute("XQUERY /conjunto_de_eventos");
      echo "<h3>📋 Eventos:</h3>";
      echo "<pre>" . htmlspecialchars($result) . "</pre>";
    } else {
      echo "<p>❌ Evento no encontrado con ID $id.</p>";
    }
    // Cierra la consulta
    $session->close();
  } catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
  }
}
?>