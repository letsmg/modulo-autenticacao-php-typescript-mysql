<?php
// Página de erro genérica (sempre retorna 403)
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Erro</title>
    <link rel="stylesheet" href="/ts/css/bootstrap.css" />
    <link rel="stylesheet" href="/ts/css/custom.css" />
    <style>
      .center-box{ min-height:70vh; display:flex; align-items:center; justify-content:center; }
      .error-card{ text-align:center; padding:2rem; border-radius:12px; background:#fff; box-shadow:0 10px 30px rgba(0,0,0,.08); }
      .error-card img{ max-width:280px; height:auto; display:block; margin:0 auto 1rem; }
      .countdown{ font-weight:700; color:var(--brand-start); }
    </style>
  </head>
  <body class="bg-body-tertiary">
    <div class="container center-box">
      <div class="error-card">
        <img src="/ts/storage/imgs/caozinho.png" alt="Erro" />
        <h3>Erro</h3>
        <p class="text-muted">Você será redirecionado em <span id="count">3</span> segundos.</p>
        <a class="btn btn-primary mt-2" href="/ts/index.php">Ir para login agora</a>
      </div>
    </div>

    <script>
      (function(){
        var t = 3;
        var el = document.getElementById('count');
        var id = setInterval(function(){
          t--;
          if(t<=0){
            clearInterval(id);
            window.location.href = '/ts/index.php';
            return;
          }
          el.textContent = t;
        }, 1000);
      })();
    </script>
  </body>
</html>