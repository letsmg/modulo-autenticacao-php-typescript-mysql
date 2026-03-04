<?php

class Mensagem {
    private $pdo;

    /**
     * Recebe a conexão PDO via Injeção de Dependência
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Envia uma nova mensagem
     */
    public function enviar(int $id_remetente, int $id_destinatario, string $texto): bool {
        try {
            $sql = "INSERT INTO mensagens (id_remetente, id_destinatario, texto, data_envio, lida) 
                    VALUES (?, ?, ?, NOW(), 0)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_remetente, $id_destinatario, $texto]);
        } catch (PDOException $e) {
            // Log do erro poderia ser feito aqui
            return false;
        }
    }

    /**
     * Lista mensagens recebidas por um usuário com JOIN para obter nome do remetente
     */
    public function listarRecebidas(int $id_usuario): array {
        try {
            $sql = "SELECT m.*, u.nome AS remetente_nome 
                    FROM mensagens m
                    JOIN usuarios u ON m.id_remetente = u.id
                    WHERE m.id_destinatario = :id
                    ORDER BY m.data_envio DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id_usuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Exclui uma mensagem (Segurança: valida se a mensagem pertence ao usuário logado)
     */
    public function excluir(int $id_mensagem, int $id_usuario): bool {
        try {
            $sql = "DELETE FROM mensagens WHERE id = ? AND id_destinatario = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_mensagem, $id_usuario]);
        } catch (PDOException $e) {
            return false;
        }
    }
}