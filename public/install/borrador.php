

<?php
//http://127.0.0.1/dsticket/public/install/

//comprobaciones básicas del entorno
$checks=[
    'php>= 8.0' =>version_compare(PHP_VERSION,'8.0.0', '>='),
    'Extensión pdo_mysql cargado' => extension_loaded('pdo_mysql'),
    'Permisos de escritura en /app' => is_writable(__DIR__ . '/../../app'),
    
];


//todo ok?
$all_ok = !in_array(false,$cheks,true);
?>

<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Instalador - dsticket<title>
            <meta name="viwport" content="width=device-width,initial-scale=1">
            <style>
                body { font-family: system-ui, sans-serif; max-width: 720px; margin: 2rem auto; }
                .ok { color: #0a0; } .bad { color: #a00; }
                .box { border: 1px solid #ddd; padding: 1rem; border-radius: .5rem; }
                .actions { margin-top: 1rem; } button { padding: .6rem 1rem; }
                code{background:#1111; padding:.15rem .35rem; border-radius:.25rem}
            </style>
    </head>

    <div class="box">
        <h2>Comparación de requisitos</h2>
        <ul>
            <?php foreach ($checks as $label => $ok):?>
                <li class="<?=$ok ? 'ok' : 'bad' ?>">
                    <?=htmlspecialchars($label) ?>: <strong><?=$ok ? 'ok' : 'falta'?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="actions">
        <?php if ($all_ok): ?>
            <form method="get" action="step2.php">
                <button>Comenzar instalación</button>
            </form>
        <?php else: ?>
            <p class="bad">Corrige los requisitos marcados en rojo y recarga esta página.</p>
        <?php endif; ?>
    </div>

        <p style="margin-top:1rem">
        <a href="/dsticket/public/">Volver a la app</a>
        </p>
    </body>
</html>