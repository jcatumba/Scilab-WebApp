<?php
    /* PostgreSQL Access */
    $conexion = pg_connect("host=localhost port=5432 dbname=wwwusers user=postgres password=&1ptytp1::postgres");
    if  (!conexion) {
        echo "<center>Problemas de Conexión con la base de datos</center>";
        exit;
    }

    $sql="SELECT * from usuarios;";

    $resultado_set = pg_Exec($conexion, $sql);
    $filas = pg_NumRows($resultado_set);

    for ($j=0; $j < $filas; $j++) {
        echo "<p>Usuario: ".pg_result($resultado_set, $j, 0)."<br>
              Password: ".pg_result($resultado_set, $j, 1)."</p>";
    }

    pg_close($conexion);
    info();
?>
