<h1 class="nombre-pagina">Crea una cuenta</h1>
<p class="descripcion-pagina">Sigue los siguentes pasos para crear una cuenta.</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";    
?>

<form action="/crear-cuenta" class="formulario"  method="post">
    <div class="campo">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre" 
        value="<?php echo s($usuario->nombre); ?>">
    </div>
    <div class="campo">
        <label for="apellido">Apellidos:</label>
        <input type="text" name="apellido" id="apellido" placeholder="Apellido" 
        value="<?php echo s($usuario->apellido); ?>">
    </div>
    <div class="campo">
        <label for="nombre">Telefono:</label>
        <input type="tel" name="telefono" id="telefono" placeholder="Telefono" 
        value="<?php echo s($usuario->telefono); ?>">
    </div>
    <div class="campo">
        <label for="email">Correo:</label>
        <input type="email" id="email" placeholder="Tu correo" name="email" 
        value="<?php echo s($usuario->email); ?>">
    </div>
    <div class="campo">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" placeholder="Contraseña" name="password">
    </div>
    <input type="submit" value="Crear centa" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya cuentas con una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>