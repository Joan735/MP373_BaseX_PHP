<!--    Al ser redirigido por Menu.php, inicia las variables necesarias y las guarda. 
        Dependiendo del form enviado se hace una accion o otra, correspondiente al form. Cada proceso funciona igual que en los demas archivos. -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar Evento</title>
</head>

<body>
    <a href="Menu.php">Volver al menu</a>
</body>

</html>

<?php
include_once 'load.php';

use BaseXClient\BaseXException;
use BaseXClient\Session;

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


if (isset($_POST["leer"])) {
    include 'Lectura.php';
}

if (isset($_POST["insertar"])) {
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

        // Insertar nuevo evento
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

        $session->execute($xqueryInsert);
        echo "<p>✅ Evento insertado correctamente con ID $id.</p>";
        // Mostrar eventos actualizados
        $session->execute("SET SERIALIZER indent=yes");
        $result = $session->execute("XQUERY /conjunto_de_eventos");
        echo "<h3>📋 Eventos:</h3>";
        echo "<pre>" . htmlspecialchars($result) . "</pre>";

        $session->close();
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

if (isset($_POST["borrar"])) {
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

            // Borrar evento 
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

if (isset($_POST["filtrar"])) {
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
            // Filtra evento si se encuentra el id
            $xquerySort = <<< XQ
    XQUERY
      for \$a in //evento
      where \$a/id="$id"
      return \$a
    
    XQ;

            $evento = $session->execute($xquerySort);
            echo "<p>✅ Evento encontrado correctamente con ID $id.</p>";
            echo "<h3>📋 Evento:</h3>";
            echo "<pre>" . htmlspecialchars($evento) . "</pre>";
        } else {
            echo "<p>❌ Evento no encontrado con ID $id.</p>";
        }

        $session->close();
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

if (isset($_POST["actualizar"])) {
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
            // Actualiza el evento
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

            $session->execute($xqueryUpdate);
            echo "<p>✅ Evento actualizado correctamente con ID $id.</p>";
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