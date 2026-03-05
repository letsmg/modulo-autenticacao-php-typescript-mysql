<?php
// Título específico; o cabeçalho logado tratará sessão e CSS
$titulo_pagina = 'Enviar Mensagem';
require_once '../../cabecalhos/cabecalho_logado.php';
?>
    <?php require_once '../../menus/menu_logado.php'; ?>

    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <div>
                <h1 class="h4 mb-3">Enviar Mensagem</h1>
              </div>
              <p class="text-muted mb-4">
                Selecione o destinatário e envie sua mensagem.
              </p>

              <form id="form_mensagem" autocomplete="off" class="vstack gap-3">
                <div>
                  <label for="id_destinatario" class="form-label">Destinatário</label>
                  <select id="id_destinatario" name="id_destinatario" class="form-select" required>
                    <option value="">Selecione um usuário</option>
                    <?php
                    // Puxa usuários ativos exceto o logado (usa $_SESSION['usuario_id'])
                    $host = 'localhost';
                    $db   = 'ts';
                    $user = 'root';
                    $pass = '';
                    $charset = 'utf8mb4';
                    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

                    try {
                      $pdo = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                      ]);

                      $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE id != ? AND ativo = 1 ORDER BY nome");
                      $stmt->execute([$_SESSION['usuario_id']]);
                      while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$user['id']}\">{$user['nome']}</option>";
                      }
                    } catch (PDOException $e) {
                      // se falhar, nada a exibir (opção padrão permanece)
                    }
                    ?>
                  </select>
                </div>

                <div>
                  <label for="mensagem" class="form-label">Mensagem</label>
                  <textarea id="mensagem" name="mensagem" class="form-control" rows="4" required></textarea>
                </div>

                <div>
                  <div class="d-grid gap-2 d-sm-flex">
                    <button type="submit" class="btn btn-success flex-fill">Enviar</button>
                    <a href="<?= BASE_URL ?>mensagens/telas/list_mensagens.php" class="btn btn-secondary">Voltar</a>
                  </div>
                </div>
              </form>

              <section id="resultado" class="mt-4 small text-body-secondary"></section>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="module" src="<?= BASE_URL ?>/js/mensagem_form.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/js/funcoes_bacanas.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/js/notificacoes.min.js"></script>
  </body>
  </html>