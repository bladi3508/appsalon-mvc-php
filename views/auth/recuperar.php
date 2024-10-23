<h1 class="nombre-pagina">Nueva Contraseña</h1>
<p class="descripcion-pagina">Favor de establecer una nueva contraseña</p>

<?php
include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return; ?>
<form class="formulario" method="post">
    <div class="campo">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" placeholder="Contraseña" name="password">
    </div>
    <input type="submit" value="Recuperar contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">Inicar Sesión</a>
</div>