<h1>Task Form</h1>

<form action="" method="POST" enctype="multipart/form-data">

    <label for="nif">NIF or CIF:</label>
    <input type="text" id="nif" name="nif" value="<?php echo old('nif'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('nif')) echo $errorHandler->getFormattedError('nif'); ?>
    <br>

    <label for="contact_person">Contact Person (Full Name):</label>
    <input type="text" id="contact_person" name="contact_person" value="<?php echo old('contact_person'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('contact_person')) echo $errorHandler->getFormattedError('contact_person'); ?>
    <br>

    <label for="phone">Contact Phone(s):</label>
    <input type="text" id="phone" name="phone" value="<?php echo old('phone'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('phone')) echo $errorHandler->getFormattedError('phone'); ?>
    <br>

    <label for="description">Task Description:</label>
    <textarea id="description" name="description"><?php echo old('description'); ?></textarea><br>
    <?php if ($_POST && $errorHandler->hasError('description')) echo $errorHandler->getFormattedError('description'); ?>
    <br>

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" value="<?php echo old('email'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('email')) echo $errorHandler->getFormattedError('email'); ?>
    <br>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?php echo old('address'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('address')) echo $errorHandler->getFormattedError('address'); ?>
    <br>

    <label for="city">City:</label>
    <input type="text" id="city" name="city" value="<?php echo old('city'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('city')) echo $errorHandler->getFormattedError('city'); ?>
    <br>

    <label for="postal_code">Postal Code:</label>
    <input type="text" id="postal_code" name="postal_code" value="<?php echo old('postal_code'); ?>"><br>
    <?php if ($_POST && $errorHandler->hasError('postal_code')) echo $errorHandler->getFormattedError('postal_code'); ?>
    <br>

    <label for="province">Province:</label>
    <select id="province" name="province">
        <!-- Opciones de provincia aquí API -->
    </select><br>
    <?php if ($_POST && $errorHandler->hasError('province')) echo $errorHandler->getFormattedError('province'); ?>
    <br>

    <label for="status">Task Status:</label>
    <select id="status" name="status">
        <option value="">Select one</option>
        <option value="B" <?php echo selected('status','B') ?>>B - Waiting for approval</option>
        <option value="P" <?php echo selected('status','P') ?>>P - Pending</option>
        <option value="R" <?php echo selected('status','R') ?>>R - Completed</option>
        <option value="C" <?php echo selected('status','C') ?>>C - Cancelled</option>
    </select><br>
    <?php if ($_POST && $errorHandler->hasError('status')) echo $errorHandler->getFormattedError('status'); ?>
    <br>

    <label for="completion_date">Completion Date:</label>
    <input type="date" id="completion_date" name="completion_date"><br>
    <?php if ($_POST && $errorHandler->hasError('completion_date')) echo $errorHandler->getFormattedError('completion_date'); ?>
    <br>

    <label for="assigned_operator">Assigned Operator:</label>
    <select id="assigned_operator" name="assigned_operator">
        <!-- Opciones de operarios aquí -->
    </select><br>
    <?php if ($_POST && $errorHandler->hasError('assigned_operator')) echo $errorHandler->getFormattedError('assigned_operator'); ?>
    <br>

    <label for="previous_notes">Previous Notes:</label>
    <textarea id="previous_notes" name="previous_notes"><?php echo old('previous_notes'); ?></textarea><br>
    <?php if ($_POST && $errorHandler->hasError('previous_notes')) echo $errorHandler->getFormattedError('previous_notes'); ?>
    <br>

    <label for="task_summary">Task Summary File:</label>
    <input type="file" id="task_summary" name="task_summary"><br>
    <?php if ($_POST && $errorHandler->hasError('task_summary')) echo $errorHandler->getFormattedError('task_summary'); ?>
    <br>

    <label for="work_photos">Work Photos:</label>
    <input type="file" id="work_photos" name="work_photos[]" multiple><br>
    <?php if ($_POST && $errorHandler->hasError('work_photos')) echo $errorHandler->getFormattedError('work_photos'); ?>
    <br>

    <button type="submit">Submit</button>
</form>


