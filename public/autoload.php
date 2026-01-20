<?php

spl_autoload_register(function ($className) {

    $caminho = str_replace('App\\', 'app/', $className);
    $caminho = str_replace('\\', DIRECTORY_SEPARATOR, $caminho) . '.php';

    $caminhoCompleto = __DIR__ . '/../' . $caminho; // .. para sair de 'public' e entrar em 'app'

    if (file_exists($caminhoCompleto)) {
        require_once $caminhoCompleto;
    }
});
