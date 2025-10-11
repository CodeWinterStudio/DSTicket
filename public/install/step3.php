<?php
declare(strict_types=1);
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

$APP_ROOT = realpath(__DIR__ . '/../../');
require $APP_ROOT . '/app/lib/helpers.php';

$CONFIG_PHP = $APP_ROOT . '/app/config.php';
$STATE_JSON = $APP_ROOT . '/app/install_state.json';
$SQL_DIR    = $APP_ROOT . '/app/sql';
$LOCK_FILE  = $APP_ROOT . '/app/INSTALL_OK';

$errors = [];
$log = [];
$alreadyInstalled = is_file($LOCK_FILE);
$force = (isset($_GET['force']) && $_GET['force'] === '1');

/* --- Comprobaciones mínimas --- */
if (!is_file($CONFIG_PHP)) $errors[] = "Falta app/config.php (ejecuta Step 2).";
if (!is_file($STATE_JSON)) $errors[] = "Falta app/install_state.json (ejecuta Step 2).";

/* --- Si ya está instalado y NO forzamos, mostramos pantalla de éxito (no es error) --- */
if ($alreadyInstalled && !$force) {
  $cfg   = is_file($CONFIG_PHP) ? require $CONFIG_PHP : [];
  $state = is_file($STATE_JSON) ? json_decode((string)file_get_contents($STATE_JSON), true) : [];
  $log[] = "Instalación detectada previamente (INSTALL_OK).";
}
/* --- Si no está instalado (o forzamos), ejecutamos migraciones/seed --- */
elseif (empty($errors)) {
  $cfg   = require $CONFIG_PHP;
  $state = json_decode((string)file_get_contents($STATE_JSON), true);

  $dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
    $cfg['db']['host'], $cfg['db']['port'], $cfg['db']['name'], $cfg['db']['charset'] ?? 'utf8mb4'
  );

  try {
    $pdo = new PDO($dsn, $cfg['db']['user'], $cfg['db']['pass'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $log[] = "Conectado a MySQL.";
  } catch (Throwable $e) {
    $errors[] = "No se pudo conectar a MySQL: " . $e->getMessage();
  }

  // Migraciones: ejecuta todos los .sql en app/sql
  if (empty($errors)) {
    $prefix = rtrim($cfg['db']['prefix'] ?? 'dst_', '_') . '_';
    if (!is_dir($SQL_DIR)) { @mkdir($SQL_DIR, 0777, true); }
    $files = glob($SQL_DIR . '/*.sql') ?: [];
    sort($files);

    foreach ($files as $file) {
      $sql = str_replace('{prefix_}', $prefix, (string)file_get_contents($file));
      foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
        if ($stmt === '') continue;
        try { $pdo->exec($stmt); }
        catch (Throwable $e) { $errors[] = "Error en " . basename($file) . ": " . $e->getMessage(); break 2; }
      }
      $log[] = "OK: " . basename($file);
    }
  }

  // Seed admin
  if (empty($errors)) {
    try {
      $stmt = $pdo->prepare("SELECT id FROM {$prefix}users WHERE email = ?");
      $stmt->execute([$state['admin']['email']]);
      if (!$stmt->fetchColumn()) {
        $ins = $pdo->prepare("INSERT INTO {$prefix}users (name, email, password_hash, role) VALUES (?,?,?, 'admin')");
        $ins->execute([$state['admin']['name'], $state['admin']['email'], $state['admin']['password_hash']]);
        $log[] = "Admin insertado: " . $state['admin']['email'];
      } else {
        $log[] = "Admin ya existía: " . $state['admin']['email'];
      }
    } catch (Throwable $e) {
      $errors[] = "No se pudo crear el admin: " . $e->getMessage();
    }
  }

  // Lock
  if (empty($errors)) {
    @file_put_contents($LOCK_FILE, "DSTicket installed at " . date('c'));
    $log[] = "INSTALL_OK creado.";
    $alreadyInstalled = true;
  }
}

$title = 'Instalador — DSTicket · Paso 3';
include $APP_ROOT . '/app/views/install/step3.view.php';
