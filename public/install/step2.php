<?php
declare(strict_types=1);
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Raíz del proyecto
$APP_ROOT = realpath(__DIR__ . '/../../');  // C:\xampp\htdocs\dsticket

// Helpers
require $APP_ROOT . '/app/lib/helpers.php';

// Rutas base
$APP_DIR    = $APP_ROOT . '/app';
$CONFIG_PHP = $APP_DIR . '/config.php';
$STATE_JSON = $APP_DIR . '/install_state.json';

// CSRF
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
$csrf = $_SESSION['csrf'];

// Comprobaciones
$errors = [];
if (!is_dir($APP_DIR))      $errors[] = "La carpeta /app no existe en {$APP_DIR}.";
if (!is_writable($APP_DIR)) $errors[] = "La carpeta /app no tiene permisos de escritura.";

// Defaults + data
$defaults = [
  'sys_name'    => 'DSTicket',
  'sys_email'   => '',
  'admin_name'  => 'Administrador',
  'admin_email' => '',
  'db_host'     => '127.0.0.1',
  'db_port'     => '3306',
  'db_name'     => 'dsticket',
  'db_user'     => 'root',
  'db_pass'     => '',
  'db_prefix'   => 'dst_',
];
$data = array_merge($defaults, $_POST);

// POST
if (is_post() && empty($errors)) {
  if (!hash_equals($csrf, (string)post('csrf'))) $errors[] = "Token CSRF inválido. Recarga la página.";

  if (trim(post('sys_name')) === '') $errors[] = "El nombre del sistema es obligatorio.";
  if (!filter_var(post('sys_email'), FILTER_VALIDATE_EMAIL)) $errors[] = "Email del sistema inválido.";
  if (trim(post('admin_name')) === '') $errors[] = "El nombre del admin es obligatorio.";
  if (!filter_var(post('admin_email'), FILTER_VALIDATE_EMAIL)) $errors[] = "Email del admin inválido.";

  $pass  = (string)post('admin_pass');
  $pass2 = (string)post('admin_pass2');
  if ($pass === '' || strlen($pass) < 8) $errors[] = "La contraseña del admin debe tener al menos 8 caracteres.";
  if ($pass !== $pass2) $errors[] = "Las contraseñas del admin no coinciden.";

  if (trim(post('db_name')) === '') $errors[] = "El nombre de la base de datos es obligatorio.";
  if (trim(post('db_user')) === '') $errors[] = "El usuario de la base de datos es obligatorio.";
  if (!preg_match('/^[a-zA-Z0-9_]+$/', (string)post('db_prefix'))) $errors[] = "El prefijo solo permite letras, números o _.";

  if (empty($errors)) {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', post('db_host'), post('db_port'), post('db_name'));
    try {
      $pdo = new PDO($dsn, (string)post('db_user'), (string)post('db_pass'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
      $tmp = post('db_prefix') . 'install_check_' . bin2hex(random_bytes(3));
      $pdo->exec("CREATE TABLE IF NOT EXISTS `$tmp` (id INT PRIMARY KEY AUTO_INCREMENT)");
      $pdo->exec("DROP TABLE `$tmp`");
    } catch (Throwable $e) {
      $errors[] = "No se pudo conectar a MySQL/PDO o faltan permisos: " . $e->getMessage();
    }
  }

  if (empty($errors)) {
    $state = [
      'system' => ['name' => (string)post('sys_name'), 'email' => (string)post('sys_email')],
      'admin'  => ['name' => (string)post('admin_name'), 'email' => (string)post('admin_email'), 'password_hash' => password_hash($pass, PASSWORD_DEFAULT)],
      'db'     => [
        'host' => (string)post('db_host'),
        'port' => (string)post('db_port'),
        'name' => (string)post('db_name'),
        'user' => (string)post('db_user'),
        'pass' => (string)post('db_pass'),
        'prefix' => (string)post('db_prefix'),
      ],
      'generated_at' => date('c'),
    ];

    try { file_put_contents($STATE_JSON, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); }
    catch (Throwable $e) { $errors[] = "No se pudo escribir install_state.json: " . $e->getMessage(); }

    if (empty($errors)) {
      $configPhp = <<<PHP
<?php
// app/config.php - generado por el instalador
return [
  'db' => [
    'host'   => '{$state['db']['host']}',
    'port'   => '{$state['db']['port']}',
    'name'   => '{$state['db']['name']}',
    'user'   => '{$state['db']['user']}',
    'pass'   => '{$state['db']['pass']}',
    'charset'=> 'utf8mb4',
    'prefix' => '{$state['db']['prefix']}',
  ],
  'system' => [
    'name'   => '{$state['system']['name']}',
    'email'  => '{$state['system']['email']}',
  ],
];
PHP;
      try { file_put_contents($CONFIG_PHP, $configPhp); }
      catch (Throwable $e) { $errors[] = "No se pudo escribir config.php: " . $e->getMessage(); }
    }

    if (empty($errors)) {
      header('Location: step3.php');
      exit;
    }
  }
}

// Render
$title = 'Instalador — DSTicket · Paso 2';
include $APP_ROOT . '/app/views/install/step2.view.php';
