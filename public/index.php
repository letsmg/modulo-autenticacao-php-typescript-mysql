<?php
  $titulo_pagina = 'bem-vindo';
  require_once './cabecalhos/cabecalho_index.php';
?>
    <div class="container d-flex flex-column justify-content-center min-vh-100">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h1 class="h4 mb-0">Entrar</h1>
                <a class="link-secondary text-decoration-none" href="usuarios/telas/cad_usuario.php">Criar conta</a>
              </div>
              <p class="text-muted mb-4">
                Informe seu e-mail e senha para acessar.
              </p>

              <form id="form-login" autocomplete="on" class="vstack gap-3">
                <div>
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    autocomplete="email"
                    required
                    value="a@a.com"
                  />
                </div>

                <div>
                  <label for="senha" class="form-label">Senha</label>
                  <div class="input-group">
                    <input
                      type="password"
                      id="senha"
                      name="senha"
                      class="form-control"
                      autocomplete="current-password"
                      required
                      value="Abratesesamo*"
                    />
                    <button
                      type="button"
                      class="btn btn-outline-secondary toggle-password"
                      data-target="senha"
                      aria-label="Mostrar ou ocultar senha"
                    >
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>

                <div>
                  <button type="submit" class="btn btn-primary w-100">
                    Entrar
                  </button>
                </div>
              </form>

              <section
                id="resultado-login"
                class="mt-4 small text-body-secondary"
              ></section>
            </div>
          </div>

          <p class="text-center mt-3 mb-0 text-muted small">
            Não tem cadastro? <a href="usuarios/telas/cad_usuario.php">Crie sua conta</a>
          </p>
        </div>
      </div>


      <div class="d-flex gap-2 justify-content-center mb-4">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-seeder>
          <i class="bi bi-magic"></i> Popular formulário
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger" data-limpar>
          <i class="bi bi-trash"></i> Limpar formulário
        </button>
      </div>




    <!-- fim do container -->
    </div> 

    


    <script type="module" src="<?= $base_url; ?>/js/login_form.min.js"></script>
    <script type="module" src="<?= $base_url; ?>/js/funcoes_bacanas.min.js"></script>
  </body>
  </html>
