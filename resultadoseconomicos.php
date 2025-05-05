<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Filtrar Evento</title>
</head>

<body>

</body>

</html>

<?php
include_once 'load.php';

use BaseXClient\BaseXException;
use BaseXClient\Session;

if (isset($_POST['submit'])) {
  try {
    $session = new Session("localhost", 1984, "admin", "admin");
    $session->execute("OPEN eventos"); // obrim la base de dades

    // 
    $xquery = <<< XQ
XQUERY

XQ;

    $session->execute($xquery);
    echo "<p>✅ Evento insertado correctamente con ID $id.</p>";
    // Mostrar eventos
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