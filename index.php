<?php
// Inclui arquivo de conexão
include __DIR__ . '/config/conexao.php';

// Página solicitada via GET
$page = $_GET['page'] ?? 'login';

// Páginas públicas (não exigem login)
$publicPages = ['login', 'cadastrarUsuario'];

// Se não estiver logado e tentar acessar página restrita → redireciona para login
if (!isset($_SESSION['usuario']) && !in_array($page, $publicPages)) {
    header("Location: index.php?page=login");
    exit;
}

// Função para renderizar menu lateral
function renderMenu() {
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">';
    
    echo "
    <nav id='menu' class='shadow-lg' style=\"font-family:Montserrat,Arial,sans-serif;
        position:fixed;top:0;left:0;width:260px;height:100vh;background:#111;color:#fff;
        display:none;z-index:1000;padding:32px 24px;\">
        <h3 class='mb-4' style='font-weight:700;'>Menu</h3>
        <ul class='nav flex-column'>
            <li class='nav-item mb-2'><a href='index.php?page=imoveis' class='nav-link text-white'>Meus Imóveis</a></li>
            <li class='nav-item mb-2'><a href='index.php?page=cadastrarImovel' class='nav-link text-white'>Cadastrar Imóvel</a></li>
            <li class='nav-item mt-4'><a href='index.php?page=sair' class='nav-link text-danger fw-bold'>Sair</a></li>
        </ul>
    </nav>

    <div id='menu-btn' style='position:fixed;top:20px;left:20px;z-index:1100;cursor:pointer;'
         onclick=\"var m=document.getElementById('menu');var btn=document.getElementById('menu-btn');
         if(m.style.display==='block'){m.style.display='none';btn.style.display='block';}
         else{m.style.display='block';btn.style.display='none';}\">
        <div style='width:30px;height:4px;background:#111;margin:6px 0;border-radius:2px;'></div>
        <div style='width:30px;height:4px;background:#111;margin:6px 0;border-radius:2px;'></div>
        <div style='width:30px;height:4px;background:#111;margin:6px 0;border-radius:2px;'></div>
    </div>

    <script>
        document.getElementById('menu').onclick=function(){
            document.getElementById('menu').style.display='none';
            document.getElementById('menu-btn').style.display='block';
        };
    </script>";
}

// Renderiza menu em páginas restritas
if (!in_array($page, $publicPages)) {
    renderMenu();
}

// Controle de rotas
switch ($page) {
    case 'home':
        require 'view/home.php';
        break;
    case 'imoveis':
        require 'view/imoveis/verImoveis.php';
        break;
    case 'cadastrarImovel':
        require 'view/imoveis/cadastrarImoveis.php';
        break;
    case 'cadastrarUsuario':
        require 'view/usuarios/cadastrarUsuario.php';
        break;
    case 'sair':
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    case 'login':
        require 'view/login.php';
        break;
    default:
        require 'view/login.php';
        break;
}
?>
