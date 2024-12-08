<?php
require_once './classes/Libro.php';
require_once './classes/Biblioteca.php';

session_start();

$biblioteca = new Biblioteca();

// Manejo de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        if ($accion === 'agregar' || $accion === 'editar') {
            $id = $accion === 'agregar' ? count($biblioteca->listarLibros()) + 1 : $_POST['id'];

            $nuevoLibro = new Libro(
                $id,
                $_POST['titulo'],
                $_POST['autor'],
                $_POST['categoria']
            );

            if ($accion === 'agregar') {
                $biblioteca->agregarLibro($nuevoLibro);
            } else {
                $biblioteca->actualizarLibro($id, $_POST['titulo'], $_POST['autor'], $_POST['categoria']);
            }
        } elseif ($accion === 'eliminar') {
            $biblioteca->eliminarLibro($_POST['id']);
        } elseif ($accion === 'prestar') {
            $biblioteca->prestarLibro($_POST['id']);
        } elseif ($accion === 'buscar') {
            $criterio = $_POST['criterio'];
            $libros = $biblioteca->buscarLibro($criterio);
        }

        
    }
}

$libros = isset($libros) ? $libros : $biblioteca->listarLibros();

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Biblioteca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Gestión de Biblioteca</h1>

    <!-- Campo de búsqueda -->
    <form method="POST" class="mb-3">
        <input type="hidden" name="accion" value="buscar">
        <div class="input-group">
            <input type="text" class="form-control" name="criterio" placeholder="Buscar por título o ID" required>
            <button type="submit" class="btn btn-secondary">Buscar</button>
        </div>
    </form>

    <!-- Botón para agregar libro -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Libro</button>

    <!-- Tabla de libros -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($libros as $libro): ?>
                <tr>
                    <td><?= $libro->getId() ?></td>
                    <td><?= $libro->getTitulo() ?></td>
                    <td><?= $libro->getAutor() ?></td>
                    <td><?= $libro->getCategoria() ?></td>
                    <td><?= $libro->isDisponible() ? 'Sí' : 'No' ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregar" onclick="editarLibro(<?= $libro->getId() ?>, '<?= $libro->getTitulo() ?>', '<?= $libro->getAutor() ?>', '<?= $libro->getCategoria() ?>')">Editar</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= $libro->getId() ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="prestar">
                            <input type="hidden" name="id" value="<?= $libro->getId() ?>">
                            <button type="submit" class="btn btn-success btn-sm" <?= !$libro->isDisponible() ? 'disabled' : '' ?>>Prestar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Agregar/Editar -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar/Editar Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="accion" id="accion" value="agregar">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="autor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="autor" name="autor" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <input type="text" class="form-control" id="categoria" name="categoria" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editarLibro(id, titulo, autor, categoria) {
    document.getElementById('accion').value = 'editar';
    document.getElementById('id').value = id;
    document.getElementById('titulo').value = titulo;
    document.getElementById('autor').value = autor;
    document.getElementById('categoria').value = categoria;
}
</script>

</body>
</html>
