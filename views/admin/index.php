<h1 class="nombre-pagina">Panel de Administración</h1>
<p class="descripcion-pagina">Por Favor seleciona los servicios que deseas y agenda tu cita.</p>

<?php

include_once __DIR__ . '/../templates/barra.php';

?>

<h2>Buscar citas:</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="">Facha:</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php
    if( count($citas) === 0 ){
        echo "<h2>No hay citas registradas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php $idCita = 0; ?>
        <?php foreach( $citas as $key => $cita ): ?>
            <?php  if( $idCita !== $cita->id ): ?>
            <?php  $total = 0; ?>
            <li>
                <p>ID: <span><?php  echo $cita->id; ?></span></p>
                <p>Fecha: <span><?php  echo $cita->fecha; ?></span></p>
                <p>Hora: <span><?php  echo $cita->hora; ?></span></p>
                <p>Cliente: <span><?php  echo $cita->cliente; ?></span></p>
                <p>Correo: <span><?php  echo $cita->correo; ?></span></p>
                <p>telefono: <span><?php  echo $cita->telefono; ?></span></p>

                <h3>Servicios</h3>
                <?php $idCita = $cita->id; ?>
            <?php endif; ?>
                <?php $total += $cita->precio; ?>
                <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>
            <?php
                //Calcular el total a pagar del cliente
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;
            ?>
            <?php if(esUltimo($actual, $proximo)): ?>
                <p class="total">Total: <span>$<?php echo $total; ?></span></p>
                <form action="/api/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $cita->id;  ?>">
                    <input type="submit" value="Eliminar" class="boton-eliminar">
                </form>
            <?php endif; ?>
        <?php endforeach;  ?>    
    </ul>
</div>

<?php

$script = "

    <script src='build/js/buscador.js'></script>

";

?>