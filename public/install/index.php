<?php

/**
 * Fallback installer entry when Document Root = /public
 * Redirects to Laravel /install wizard.
 */
$installed = dirname(__DIR__, 2).'/storage/app/installed';
if (is_file($installed)) {
    header('Location: /');
    exit;
}

header('Location: /install');
exit;
