<?php


// título específico; o cabeçalho logado cuidará de sessão e CSS
$titulo_pagina = 'Editar usuário';
require_once '../../cabecalhos/cabecalho_logado.php';
?>
    <?php require_once '../../menus/menu_logado.php'; ?>
    <?php /*
    <?php require_once '../logica/obter_usuario.php'; ?>
    */ ?>
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
                    value=""
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
                  <div class="d-grid gap-2 d-sm-flex">
                    <button type="submit" class="btn btn-success flex-fill">Salvar</button>
                    <a href="<?= $base_url ?>/usuarios/telas/list_usuarios.php" class="btn btn-secondary">Voltar</a>
                  </div>
                </div>
              </form>

              <section
                id="resultado"
                class="mt-4 small text-body-secondary"
              ></section>
            </div>
          </div>

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


      
    </div>

    <script type="module" src="<?= $base_url; ?>/js/user_form.min.js"></script>
    <script type="module" src="<?= $base_url; ?>/js/funcoes_bacanas.min.js"></script>
  </body>
  </html>
