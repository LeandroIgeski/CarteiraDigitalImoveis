<?php
// Configurar sessão robusta ANTES de session_start()
if (session_status() === PHP_SESSION_NONE) {
    // Forçar diretório de sessão dentro do projeto para facilitar depuração e garantir permissão
    $project_session_dir = __DIR__ . '/../sessions';
    if (!is_dir($project_session_dir)) {
        @mkdir($project_session_dir, 0777, true);
    }
    // Use caminho absoluto Windows
    $project_session_dir = str_replace('\\', '/', realpath($project_session_dir));
    ini_set('session.save_path', $project_session_dir);

    ini_set('session.cache_limiter', '');
    ini_set('session.use_strict_mode', '0');
    ini_set('session.use_cookies', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.gc_probability', '1');
    ini_set('session.gc_divisor', '100');
    ini_set('session.gc_maxlifetime', '3600');
    session_start();
}

    $hostname = "localhost";
    $bancodedados = "administracao";
    $usuario = "root";
    $senha = "";

    $conn = new mysqli($hostname, $usuario, $senha, $bancodedados);
    if ($conn->connect_errno) {
        die("Falha ao conectar: (" . $conn->connect_errno . ") " . $conn->connect_error);
    }
?>