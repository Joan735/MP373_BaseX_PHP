<?php

require_once "BaseXClient/Session.php"; // Asegúrate de que la ruta sea correcta

use BaseXClient\BaseXException;
use BaseXClient\Session;

try {
    // Crear una sesión con BaseX
    $session = new Session("localhost", 1984, "admin", "admin");

    try {
        $session->execute("open eventos"); // obrim la base de dades
        $input = 'for $a in ./eventos return $a';// creem la query FLOWR
        $query = $session->query($input); // executem la query
        // recorrem tots els resultats y els imprimim
        while ($query->more()) {
        echo $query->next(). "<br />";
        }
        $query->close(); // un cop mostrats els resultats de la query, la tanquem
        } catch (Exception $e) { // control d’errors
        echo $e->getMessage();
        }
    // Cerrar la consulta y la sesión
    $session->close();
} 

catch (Exception $e) { // Captura de errores
    echo "Error: " . $e->getMessage();
}