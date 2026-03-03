<?php ?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
  </head>
  <body class="bg-body-tertiary">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h1 class="h4 mb-0">Entrar</h1>
                <a class="link-secondary text-decoration-none" href="usuarios/telas/cadastro.php">Criar conta</a>
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
                      value="123456"
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
            Não tem cadastro? <a href="usuarios/telas/cadastro.php">Crie sua conta</a>
          </p>
        </div>
      </div>
    </div>

    <script type="module" src="usuarios/telas/js/login-form.js"></script>
  </body>
  </html>
