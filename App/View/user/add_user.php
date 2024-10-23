<form action="" method="post">
    <!-- Nombre del Usuario -->
    <label for="name">Name (Full Name):</label>
    <input type="text" id="name" name="name" value="<?php echo old('name'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('name')) echo $errorHandler->getFormattedError('name'); ?>
    <br>
    
    <!-- Correo Electrónico -->
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo old('email'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('email')) echo $errorHandler->getFormattedError('email'); ?>
    <br>
    
    <!-- Contraseña -->
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br>
    <?php if ($_POST && $errorHandler->hasError('password')) echo $errorHandler->getFormattedError('password'); ?>
    <br>
    
    <!-- Rol del Usuario -->
    <label for="rol">Role:</label>
    <select id="rol" name="rol">
        <option value="administrative" <?php echo old('rol') == 'administrative' ? 'selected' : ''; ?>>Administrative</option>
        <option value="worker" <?php echo old('rol') == 'worker' ? 'selected' : ''; ?>>Worker</option>
    </select><br>
    <?php if ($_POST && $errorHandler->hasError('rol')) echo $errorHandler->getFormattedError('rol'); ?>
    <br>
    
    <input type="submit" value="Register">
</form>