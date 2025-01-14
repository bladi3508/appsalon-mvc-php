<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios.</p>

<?php

include_once __DIR__ . '/../templates/barra.php';

?>


<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>
    <li>
        <p>Servicio: <span><?php echo $servicio->nombre; ?></span></p>
        <p>Servicio: <span>$<?php echo $servicio->precio; ?></span></p>
        <div class="acciones">
            <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar</a>

            <form action="/servicios/eliminar" method="post">
                <input type="hidden" name="id" id="id" value="<?php echo $servicio->id; ?>">
                <input type="submit" value="Eliminar" class="boton-eliminar">
            </form>
        </div>
    </li>
    <?php endforeach; ?>
</ul>