<?php

spl_autoload_register(function ($class) {
    $file = preg_replace('/^App/', 'src', $class);
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
    $file = sprintf('%s/%s.php', __DIR__, $file);

    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
});
