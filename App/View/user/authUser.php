<form action="" method="post">
   
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
    
    <input type="submit" value="Log In">
</form>