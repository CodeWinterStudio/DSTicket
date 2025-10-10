



<?php
//http://127.0.0.1/dsticket/public/install/




error_reporting(E_ALL);
ini_set('display_errors', 1);
//comprobaciones básicas del entorno
$checks = [];
$checks['PHP >= 8.0']                   = version_compare(PHP_VERSION, '8.0.0', '>=');
$checks['Extensión pdo_mysql cargada']  = extension_loaded('pdo_mysql');

$appPath = realpath(__DIR__ . '/../../app');
$checks['Permisos de escritura en /app'] = $appPath ? is_writable($appPath) : false;

$all_ok = !in_array(false, $checks, true);

//todo ok?
$all_ok = !in_array(false,$checks,true);
?>


<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Instalador — dsticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/dsticket/public/assets/css/styleInstaller.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

        
            
</head>
    <body>
        <canvas id="bgCanvas" aria-hidden="true"></canvas>

        <div class="contenedorPrincipal"><!--contenedor principal-->
                <div class="cabecera">
                    <p class="version"><span>Instalando DSTicket v1.0-beta</span></p>
                </div>
            <img src="/dsticket/public/assets/logo/logoDSTicket.png" alt="Logo DSTICKET" class="imgInstaller">
            <div >
                <h2>Gracias por elegir DS Ticket!</h2>
                <p>DSTicket, es  una herramienta de soporte en desarrollo basada en código abierto diseñada para organizar y dar seguimiento a incidencias,
                    tareas internas y flujos de comunicación entre equipos de trabajo.</p>
                <p>Esta versión beta le guiará paso a paso en la configuración inicial del sistema.
                Tenga en cuenta que aún puede contener funciones en prueba o pendientes de mejora.</p>
            </div>
            <div class="box">
                <h2>Requisitos</h2>
                <p>Antes de comenzar, verificaremos la configuración de su servidor para asegurarnos de
                    que cumple con los requisitos mínimos para instalar y ejecutar DSTicket.</p>
                <h2>Comprobación de requisitos</h2>
                <ul>
                    
                <?php 
                
               // $checks['Extensión GD'] = false;
                //$all_ok = !in_array(false, $checks, true);

                foreach ($checks as $label => $ok) { ?>
                    <li class="<?php echo $ok ? 'ok' : 'bad'; ?>">
                    <?php echo htmlspecialchars($label); ?>:
                    <strong><?php echo $ok ? 'OK' : 'FALTA'; ?></strong>
                    </li>
                <?php } ?>
                </ul>
                <p><small>Ruta /app: <code><?php echo $appPath ? htmlspecialchars($appPath) : '(no existe)'; ?></code></small></p>
            </div>

            <div class="actions">
                <?php if ($all_ok) { ?>
                <form method="get" action="step2.php">
                    <button class="btn">Comenzar instalación</button>
                    
                </form>
                <?php } else { ?>
                <p class="bad">Corrige los requisitos marcados en rojo y recarga esta página.</p>
                <?php } ?>
            </div>

            
                
                
            

        </div>


<script>
const canvas = document.getElementById('bgCanvas');
const ctx = canvas.getContext('2d');

function resize() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resize();
window.addEventListener('resize', resize);

const colors = ['#11676a', '#1cabb0', '#3ddbe1'];
const circles = [];

for (let i = 0; i < 15; i++) {
  circles.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    r: 80 + Math.random() * 100,
    dx: (Math.random() - 0.5) * 0.8,
    dy: (Math.random() - 0.5) * 0.8,
    color: colors[Math.floor(Math.random() * colors.length)],
  });
}

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  for (let c of circles) {
    c.x += c.dx;
    c.y += c.dy;

    if (c.x < -c.r || c.x > canvas.width + c.r) c.dx *= -1;
    if (c.y < -c.r || c.y > canvas.height + c.r) c.dy *= -1;

    const gradient = ctx.createRadialGradient(c.x, c.y, 0, c.x, c.y, c.r);
    gradient.addColorStop(0, c.color + "AA");
    gradient.addColorStop(1, c.color + "00");

    ctx.fillStyle = gradient;
    ctx.beginPath();
    ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2);
    ctx.fill();
  }
  requestAnimationFrame(animate);
}
animate();
</script>

        
                    
    </body>
    
</html>