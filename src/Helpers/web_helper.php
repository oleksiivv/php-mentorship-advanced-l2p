<?php

function renderTemplate($path, $data = []): false|string
{
    if (!file_exists($path)) {
        return ''; // You could handle errors more gracefully
    }
    ob_start();
    extract($data);
    include $path;
    return ob_get_clean();
}