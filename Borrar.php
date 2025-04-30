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
    <button type="submit" name="submit">Borrar evento</button>
  </form>
</body>

</html>

<?php
include_once 'load.php';

use BaseXClient\BaseXException;
use BaseXClient\Session;

if (!isset($_POST['id'])) {
  $_POST['id'] = "";
}

$id = htmlspecialchars($_POST['id']);

if (isset($_POST['submit'])) {
  try {
    $session = new Session("localhost", 1984, "admin", "admin");
    $session->execute("OPEN eventos"); // obrim la base de dades

    // Borrar evento si se encuentra el id
    $xqueryDelete = <<< XQ
XQUERY
  for \$a in //evento
  where \$a/id="$id"
  return delete node \$a

XQ;

    $session->execute($xqueryDelete);
    echo "<p>✅ Evento borrado correctamente con ID $id.</p>";
    // Mostrar eventos actualizados
    $session->execute("SET SERIALIZER indent=yes");
    $result = $session->execute("XQUERY /conjunto_de_eventos");
    echo "<h3>📋 Eventos actuales:</h3>";
    echo "<pre>" . htmlspecialchars($result) . "</pre>";

    $session->close();
  } catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
  }
}
?>