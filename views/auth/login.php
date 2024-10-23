<h1 class="nombre-pagina">Inicia Sessión</h1>
<p class="descripcion-pagina">Ingresa tus datos y agenda una cita.</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";    
?>

<form action="/" class="formulario" method="post">
    <div class="campo">
        <label for="email">Correo:</label>
        <input type="email" id="email" placeholder="Tu correo" name="email">
    </div>
    <div class="campo">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" placeholder="Contraseña" name="password">
    </div>
    <input type="submit" value="Ingresar" class="boton">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>