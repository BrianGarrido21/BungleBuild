<div>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Nombre de Contacto</th>
                <th>Teléfono de Contacto</th>
                <th>Estado</th>
                <th>Operario Asignado</th>
                <th>Acciones</th> <!-- Columna para los futuros botones -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tasks)) : ?>
                <?php foreach ($tasks as $task) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['contact_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['contact_phone']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['assigned_worker']); ?></td>
                        <td>
                            <!-- Futuros botones -->
                            <button>Completar</button>
                            <button>Editar</button>
                            <button>Borrar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">No hay tareas disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Paginador -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
    <div>
        <ul style="list-style: none; display: flex; gap: 10px;">
            <!-- Botón de página anterior -->
            <?php if ($currentPage > 1): ?>
                <li><a href="?page=<?php echo $currentPage - 1; ?>&items_per_page=<?php echo $itemsPerPage; ?>">Anterior</a></li>
            <?php endif; ?>

            <!-- Mostrar números de página -->
            <?php for ($page = $paginationRange['startPage']; $page <= $paginationRange['endPage']; $page++) : ?>
                <li>
                    <a href="?page=<?php echo $page; ?>&items_per_page=<?php echo $itemsPerPage; ?>"
                        <?php if ($page == $currentPage) echo 'style="font-weight: bold;"'; ?>>
                        <?php echo $page; ?>
                    </a>
                </li>
            <?php endfor; ?>
            <!-- Botón de página siguiente -->
            <?php if ($currentPage < $totalPages): ?>
                <li><a href="?page=<?php echo $currentPage + 1; ?>&items_per_page=<?php echo $itemsPerPage; ?>">Siguiente</a></li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>