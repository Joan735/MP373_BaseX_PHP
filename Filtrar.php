<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Filtrar Evento</title>
</head>

<body>
  <h2>Filtrar Evento por ID</h2>
  <form action="Filtrar.php" method="post">
    <label>ID: <input type="text" name="id" required></label><br>
    <br><br>
    <button type="submit" name="filtrar">Filtrar evento</button>
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

if (isset($_POST['filtrar'])) {
  try {
    $session = new Session("localhost", 1984, "admin", "admin");
    $session->execute("OPEN eventos"); // obrim la base de dades

    // Verificamos si existe un evento con ese ID
    $idCheckQuery = <<<XQ
XQUERY 
count(/conjunto_de_eventos/evento[id = $id])
XQ;
    $existe = intval($session->execute($idCheckQuery));

    if ($existe > 0) {
      // Filtrar evento
      $xquerySort = <<< XQ
XQUERY
  for \$a in //evento
  where \$a/id="$id"
  return \$a
XQ;

      $evento = $session->execute($xquerySort);
      echo "<p>✅ Evento encontrado correctamente con ID $id.</p>";
      echo "<h3>📋 Evento:</h3>";
      // Muestra el evento filtrado
      echo "<pre>" . htmlspecialchars($evento) . "</pre>";
    } else {
      echo "<p>❌ Evento no encontrado con ID $id.</p>";
    }

    $session->close();
  } catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
  }
}
?>