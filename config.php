<?php
// Rôle de ce fichier : Contient les réglages communs et les chemins utilisés par le site.

if (!defined('URL')) {
    // Fonctionne dans un sous-dossier localhost ou un domaine Laragon.
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    define('URL', rtrim($basePath, '/') . '/');
}
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', __DIR__);
}
