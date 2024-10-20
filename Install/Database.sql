CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrative', 'worker') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE task (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    nif VARCHAR(15) NOT NULL, -- NIF o CIF de la persona sobre la que se hará la factura
    contact_name VARCHAR(100) NOT NULL, -- Nombre y apellidos de la persona de contacto
    contact_phone VARCHAR(50) NOT NULL, -- Teléfonos de contacto (separados por comas si hay varios)
    description TEXT NOT NULL, -- Descripción de la tarea
    contact_email VARCHAR(100), -- Correo electrónico de la persona de contacto
    address TEXT NOT NULL, -- Dirección donde se realizará la tarea
    city VARCHAR(100) NOT NULL, -- Población
    postal_code VARCHAR(5) NOT NULL, -- Código postal de la tarea
    province_code INT NOT NULL, -- Código de provincia según INE (dos primeros dígitos del CP)
    status ENUM('B', 'P', 'R', 'C') DEFAULT 'B', -- Estado de la tarea (B: Esperando aprobación, P: Pendiente, R: Realizada, C: Cancelada)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de creación de la tarea
    assigned_worker VARCHAR(100) NOT NULL, -- Nombre o ID del operario encargado
    completion_date DATE, -- Fecha en la que se realizará la tarea
    pre_notes TEXT, -- Anotaciones antes de comenzar el trabajo
    post_notes TEXT, -- Anotaciones realizadas después del trabajo
    sumary_file VARCHAR(255) -- Ruta al fichero resumen del trabajo
);

CREATE TABLE task_photos (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL, -- Nombre del foto almacenada en el servidor
    photo_path VARCHAR(255) NOT NULL, -- Ruta de la foto almacenada en el servidor
    FOREIGN KEY (task_id) REFERENCES task(task_id) ON DELETE CASCADE -- Eliminar las fotos si se elimina la tarea
);