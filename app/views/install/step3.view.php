
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= h($title ?? 'DSTicket') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/dsticket/public/assets/css/styleInstaller.css">
    <script defer src="/dsticket/public/install/js/step2Canvas.js"></script>
</head>
<body>
    <canvas id="bgCanvas" aria-hidden="true"></canvas>
  <main class="contenedorPrincipal">
    <h1>Instalación — Paso 3</h1>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <strong>Se encontraron problemas:</strong>
        <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
      </div>
      <button type="button" class="btn" onclick="location.href='step2.php'">
        Volver al Paso 2
        </button>

    <?php else: ?>
      <div class="ok">
        <p>¡Todo listo! Se han ejecutado las migraciones y creado el usuario administrador.</p>
        <ul><?php foreach ($log as $line): ?><li><?= h($line) ?></li><?php endforeach; ?></ul>
      </div>

      <h2>Siguientes pasos</h2>
      <ol>
        <li><strong>Por seguridad</strong>, elimina o renombra la carpeta <code>/public/install</code>.</li>
        <li>Guarda tu email de admin: <code><?= h($state['admin']['email'] ?? '') ?></code>.</li>
        <li>Apunta tu contraseña <code>********</code></li>
      </ol>
    <?php endif; ?>
  </main>
</body>
</html>
