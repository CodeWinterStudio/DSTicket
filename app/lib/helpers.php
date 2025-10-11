<?php
declare(strict_types=1);

function h(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function post($k, $def='')    { return $_POST[$k] ?? $def; }
function is_post(): bool      { return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'; }
