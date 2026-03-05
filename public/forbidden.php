<?php
// Página de erro para diretórios proibidos
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Acesso proibido</title>
    <link rel="stylesheet" href="/ts/css/bootstrap.css" />
    <link rel="stylesheet" href="/ts/css/custom.css" />
    <style>
      .center-box{ min-height:70vh; display:flex; align-items:center; justify-content:center; }
      .forbidden-card{ text-align:center; padding:2rem; border-radius:12px; background:#fff; box-shadow:0 10px 30px rgba(0,0,0,.08); }
      .forbidden-card img{ max-width:280px; height:auto; display:block; margin:0 auto 1rem; }
      .countdown{ font-weight:700; color:var(--brand-start); }
    </style>
  </head>
  <body class="bg-body-tertiary">
    <div class="container center-box">
      <div class="forbidden-card">
        <img src="/ts/storage/imgs/caozinho.png" alt="Acesso proibido" />
        <h3>Acesso proibido</h3>
        <p class="text-muted">Você não tem permissão para ver esse diretório.</p>
        <p>Redirecionando para a página de login em <span id="count">3</span> segundos...</p>
        <a class="btn btn-primary mt-2" href="/ts/public/index.php">Ir para login agora</a>
      </div>
    </div>

    <script>
      (function(){
        var t = 5;
        var el = document.getElementById('count');
        var id = setInterval(function(){
          t--; if(t<=0){ clearInterval(id); window.location.href = '/ts/public/index.php'; return; }
          el.textContent = t;
        }, 1000);
      })();
    </script>
  </body>
</html>
