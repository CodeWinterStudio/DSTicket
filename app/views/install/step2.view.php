<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= h($title ?? 'DSTicket') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/dsticket/public/assets/css/styleInstaller.css">
  <link rel="stylesheet" href="/dsticket/public/assets/css/step2.css"><!-- nombre real -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap"></noscript>
  <script defer src="/dsticket/public/install/js/step2Canvas.js"></script>
</head>
<body>
  <canvas id="bgCanvas" aria-hidden="true"></canvas>

  <div class="contenedorPrincipal">
    <div class="cabecera">
      <p class="version"><span>Instalando DSTicket v1.0-beta</span></p>
    </div>

    <img src="/dsticket/public/assets/logo/logoDSTicket.png" alt="Logo DSTICKET" class="imgInstaller">

    <h2>Configuración inicial</h2>
    <p>Introduce los datos del sistema, del usuario administrador y de la base de datos para continuar.</p>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <strong>Revisa los siguientes puntos:</strong>
        <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <input type="hidden" name="csrf" value="<?= h($csrf) ?>">

      <div class="box">
        <h2 class="thL">1 - Ajustes del sistema</h2>
        <table class="formTable" role="presentation">
          <tr>
            <td width="300px"><label for="sys_name">Nombre del sistema</label></td>
            <td><input class="inpC" id="sys_name" name="sys_name" required value="<?= h($data['sys_name']) ?>"></td>
          </tr>
          <tr>
            <td width="300px"><label for="sys_email">Email por defecto (notificaciones)</label></td>
            <td><input id="sys_email" name="sys_email" type="email" required value="<?= h($data['sys_email']) ?>"></td>
          </tr>
        </table>
      </div>

      <div class="box">
        <h2>2 - Usuario administrador</h2>
        <table class="formTable" role="presentation">
          <tr>
            <td width="300px"><label for="admin_name">Nombre y apellidos</label></td>
            <td><input class="inpC" id="admin_name" name="admin_name" required value="<?= h($data['admin_name']) ?>"></td>
          </tr>
          <tr>
            <td width="300px"><label for="admin_email">Email del admin</label></td>
            <td><input id="admin_email" name="admin_email" type="email" required value="<?= h($data['admin_email']) ?>"></td>
          </tr>
          <tr>
            <td width="300px"><label for="admin_pass">Contraseña</label></td>
            <td>
              <input id="admin_pass" name="admin_pass" type="password" minlength="8" required>
              <div class="hint">Mínimo 8 caracteres.</div>
            </td>
          </tr>
          <tr>
            <td width="300px"><label for="admin_pass2">Repite la contraseña</label></td>
            <td><input id="admin_pass2" name="admin_pass2" type="password" minlength="8" required></td>
          </tr>
        </table>
      </div>

      <div class="box">
        <h2>3 - Base de datos MySQL</h2>
        <table class="formTable" role="presentation">
          <tr>
            <td width="300px"><label for="db_host" class="labelC">Host</label></td>
            <td><input class="inpC" id="db_host" name="db_host" required value="<?= h($data['db_host']) ?>"></td>
          </tr>
          <tr>
            <td width="300px"><label for="db_port">Puerto</label></td>
            <td>
              <input class="inpC" id="db_port" name="db_port" required value="<?= h($data['db_port']) ?>">
              <div class="hint">Ej.: 3306 (por defecto)</div>
            </td>
          </tr>
          <tr>
            <td width="300px"><label for="db_name">Nombre de la BD</label></td>
            <td><input class="inpC" id="db_name" name="db_name" required value="<?= h($data['db_name']) ?>"></td>
          </tr>
          <tr>
            <td width="300px"><label for="db_user">Usuario</label></td>
            <td><input class="inpC" id="db_user" name="db_user" required value="<?= h($data['db_user']) ?>"></td>
          </tr>
          <tr>
            <td width="300px"><label for="db_pass">Contraseña</label></td>
            <td><input id="db_pass" name="db_pass" type="password" value="<?= h($data['db_pass']) ?>"></td>
          </tr>
          <tr>
            <td width="300px"><label for="db_prefix">Prefijo de tablas</label></td>
            <td>
              <input class="inpC" id="db_prefix" name="db_prefix" required value="<?= h($data['db_prefix']) ?>">
              <div class="hint">Solo letras/números/_ (p.ej. <code>dst_</code>).</div>
            </td>
          </tr>
        </table>
      </div>

      <div class="actions">
        <button type="button" class="btn" onclick="history.back()">Volver</button>
        <button class="btn" type="submit">Guardar y continuar</button>
      </div>
    </form>
  </div>
</body>
</html>
