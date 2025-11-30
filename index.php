<?php
require_once 'db.php';

// Manejo de formulario (Insertar)
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $url = $_POST['url'];
    $plataforma = $_POST['plataforma'];
    $descripcion = $_POST['descripcion'];

    if (!empty($titulo) && !empty($url)) {
        $stmt = $pdo->prepare("INSERT INTO enlaces (titulo, url, plataforma, descripcion) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$titulo, $url, $plataforma, $descripcion])) {
            header("Location: index.php"); // Evitar reenvío de formulario
            exit;
        }
    }
}

// Obtener datos
$query = $pdo->query("SELECT * FROM enlaces ORDER BY fecha_creacion DESC");
$enlaces = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CloudLink App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .hero { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); color: white; padding: 3rem 0; margin-bottom: 2rem; border-radius: 0 0 20px 20px; }
        .card-link { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card-link:hover { transform: translateY(-5px); }
        .icon-box { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.5rem; color: white; margin-right: 15px; }
        .bg-google { background-color: #4285F4; }
        .bg-dropbox { background-color: #0061FF; }
        .bg-aws { background-color: #FF9900; }
        .bg-other { background-color: #6c757d; }
    </style>
</head>
<body>

    <header class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="fas fa-network-wired"></i> CloudLink</h1>
            <p class="lead">Gestor centralizado de recursos en la nube</p>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <!-- Formulario de Ingreso -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h4 class="mb-3"><i class="fas fa-plus-circle"></i> Nuevo Recurso</h4>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="titulo" class="form-control" required placeholder="Ej: Reporte Anual">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Plataforma</label>
                                <select name="plataforma" class="form-select">
                                    <option value="Google Drive">Google Drive</option>
                                    <option value="Dropbox">Dropbox</option>
                                    <option value="AWS">AWS S3</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Enlace (URL)</label>
                                <input type="url" name="url" class="form-control" required placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Listado de Recursos -->
            <div class="col-lg-8">
                <h4 class="mb-3 text-muted">Mis Recursos</h4>
                <div class="row">
                    <?php foreach ($enlaces as $link): 
                        $bgClass = 'bg-other';
                        $icon = 'fa-link';
                        if ($link['plataforma'] == 'Google Drive') { $bgClass = 'bg-google'; $icon = 'fa-google-drive'; }
                        if ($link['plataforma'] == 'Dropbox') { $bgClass = 'bg-dropbox'; $icon = 'fa-dropbox'; }
                        if ($link['plataforma'] == 'AWS') { $bgClass = 'bg-aws'; $icon = 'fa-aws'; }
                    ?>
                    <div class="col-md-6 mb-3">
                        <div class="card card-link h-100">
                            <div class="card-body d-flex align-items-start">
                                <div class="icon-box <?php echo $bgClass; ?>">
                                    <i class="fab <?php echo $icon; ?>"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($link['titulo']); ?></h5>
                                    <p class="card-text text-muted small mb-2"><?php echo htmlspecialchars($link['descripcion']); ?></p>
                                    <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="text-decoration-none fw-bold small">
                                        Abrir Enlace <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>