<?php ?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Criar conta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
  </head>
  <body class="bg-body-tertiary">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h1 class="h4 mb-0">Criar conta</h1>
                <a class="link-secondary text-decoration-none" href="index.php"
                  >Já tenho conta</a
                >
              </div>
              <p class="text-muted mb-4">
                Preencha seus dados para criar um usuário no sistema.
              </p>

              <form id="form-usuario" autocomplete="off" class="vstack gap-3">
                <div>
                  <label for="nome" class="form-label">Nome</label>
                  <input
                    type="text"
                    id="nome"
                    name="nome"
                    class="form-control"
                    required
                  />
                </div>

                <div>
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    required
                  />
                </div>

                <div>
                  <label for="senha" class="form-label"
                    >Senha (mínimo 6 caracteres)</label
                  >
                  <div class="input-group">
                    <input
                      type="password"
                      id="senha"
                      name="senha"
                      class="form-control"
                      minlength="6"
                      required
                      autocomplete="new-password"
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
                  <label for="repetirSenha" class="form-label"
                    >Repita a senha</label
                  >
                  <div class="input-group">
                    <input
                      type="password"
                      id="repetirSenha"
                      name="repetirSenha"
                      class="form-control"
                      minlength="6"
                      required
                      autocomplete="new-password"
                    />
                    <button
                      type="button"
                      class="btn btn-outline-secondary toggle-password"
                      data-target="repetirSenha"
                      aria-label="Mostrar ou ocultar senha"
                    >
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>

                <div>
                  <label for="nivelAcesso" class="form-label"
                    >Nível de acesso</label
                  >
                  <select
                    id="nivelAcesso"
                    name="nivelAcesso"
                    class="form-select"
                  >
                    <option value="padrao">Padrão</option>
                    <option value="adm">Administrador</option>
                  </select>
                  <div class="form-text">
                    Em produção, esse campo normalmente não ficaria exposto.
                  </div>
                </div>

                <div>
                  <button type="submit" class="btn btn-success w-100">
                    Cadastrar
                  </button>
                </div>
              </form>

              <section
                id="resultado"
                class="mt-4 small text-body-secondary"
              ></section>
            </div>
          </div>
          <p class="text-center mt-3 mb-0 text-muted small">
            Voltar para o <a href="index.php">login</a>
          </p>
        </div>
      </div>
    </div>

    <script src="js/user-form.js"></script>
  </body>
  </html>

