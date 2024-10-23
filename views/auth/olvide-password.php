<h1 class="nombre-pagina">Olvidaste tu contraseña</h1>
<p class="descripcion-pagina">Restaura tu contraseña de una manera sensilla.</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";    
?>

<form action="/olvide" class="formulario"  method="post">
<div class="campo">
        <label for="email">Correo:</label>
        <input type="email" id="email" placeholder="Tu correo" name="email">
    </div>
    <input type="submit" value="Recuperar contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">Inicar Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
</div>