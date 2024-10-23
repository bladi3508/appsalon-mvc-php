<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Agrega un nuevo servicio a brindar.</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";    
?>

<form action="/servicios/crear" method="post" class="formulario">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" value="Guardar" class="boton">

</form>