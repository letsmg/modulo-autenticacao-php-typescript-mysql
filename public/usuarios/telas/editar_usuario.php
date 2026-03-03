<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Usuário não autenticado redireciona para login
    header('Location: ../../index.php');
    exit;
}
// se não houver id na query string, envia para o próprio
if (!isset($_GET['id'])) {
    $me = $_SESSION['usuario_id'];
    header("Location: editar_usuario.php?id={$me}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Editar usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../css/bootstrap.css" />
    <link rel="stylesheet" href="../../css/custom.css" />
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
              <h1 class="h4 mb-3">Editar usuário</h1>
              <p class="text-muted mb-4">
                Faça as alterações desejadas. Administradores podem alterar o
                nível de acesso e desativar outros usuários, mas não podem
                desativar ou mudar o próprio nível.
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
                    >Senha (deixe em branco para manter a atual)</label
                  >
                  <div class="input-group">
                    <input
                      type="password"
                      id="senha"
                      name="senha"
                      class="form-control"
                      minlength="6"
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
                    O campo só ficará habilitado se você tiver permissão.
                  </div>
                </div>

                <div id="field-ativo" class="form-check d-none">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="ativo"
                    name="ativo"
                    value="1"
                  />
                  <label class="form-check-label" for="ativo">
                    Usuário ativo
                  </label>
                </div>

                <div>
                  <button type="submit" class="btn btn-success w-100">
                    Salvar
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
            Voltar para o <a href="../../index.php">início</a>
          </p>
        </div>
      </div>
    </div>

    <script type="module" src="js/user-form.js"></script>
  </body>
  </html>
