<?php

class InfoModel
{
    private $pdo;
    private $table = 'content_page';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer toutes les informations visibles (côté public)
     */
    public function getAllVisibleInfos()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id_content, content_label, body, media_content, created_at FROM {$this->table} WHERE visible = TRUE ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getVisibleInfos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer toutes les informations (côté admin)
     */
    public function getAllInfos()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getAllInfos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer une information précise par son ID
     */
    public function getInfoById($id_content)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_content = :id_content LIMIT 1");
            $stmt->execute([':id_content' => $id_content]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getInfoById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Créer une nouvelle information (côté admin)
     */
    public function createInfo($data)
    {
        try {
            // Validation des champs attendus
            $requiredFields = ['content_label', 'body'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return ["error" => "Le champ '$field' est requis."];
                }
            }

            // Préparation sécurisée des données
            $contentLabel = trim($data['content_label']);
            $body = trim($data['body']);
            $mediaContent = isset($data['media_content']) ? trim($data['media_content']) : null;
            $visible = isset($data['visible']) ? (int)$data['visible'] : 1;
            $idUser = isset($data['id_user']) ? (int)$data['id_user'] : null;

            // Insertion en BDD
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (content_label, body, media_content, visible, id_user)
                                        VALUES (:content_label, :body, :media_content, :visible, :id_user)");
            $stmt->execute([
                ':content_label' => $contentLabel,
                ':body' => $body,
                ':media_content' => $mediaContent,
                ':visible' => $visible,
                ':id_user' => $idUser,
            ]);

            return ["success" => true, "id_content" => $this->pdo->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Erreur SQL dans createInfo: " . $e->getMessage());
            return ["error" => "Erreur lors de la création de l'information."];
        }
    }

    /**
     * Modifier une information (côté admin)
     */
    public function updateInfo($id_content, $data)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE {$this->table}
                SET 
                    content_label = :content_label,
                    body = :body,
                    media_content = :media_content,
                    visible = :visible
                WHERE id_content = :id_content
            ");

            $stmt->execute([
                ':content_label' => $data['content_label'],
                ':body' => $data['body'],
                ':media_content' => $data['media_content'] ?? null,
                ':visible' => isset($data['visible']) ? (int) $data['visible'] : 1,
                ':id_content' => $id_content,
            ]);

            return $stmt->rowCount(); // Retourne combien de lignes ont été modifiées
        } catch (PDOException $e) {
            error_log("Erreur SQL dans updateInfo: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprimer une information (côté admin)
     */
    public function deleteInfo($id_content)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_content = :id_content");
            $stmt->execute([':id_content' => $id_content]);
            return $stmt->rowCount() > 0; // true si au moins 1 ligne supprimée
        } catch (PDOException $e) {
            error_log("Erreur SQL dans deleteInfo: " . $e->getMessage());
            return false;
        }
    }

    public function toggleVisibility($id_content)
    {
        try {
            // inverse la visibilité : visible = NOT visible
            $stmt = $this->pdo->prepare("
                UPDATE {$this->table} 
                SET visible = NOT visible 
                WHERE id_content = :id_content
            ");
            $stmt->execute([':id_content' => $id_content]);
            return $stmt->rowCount() > 0; // true si au moins une ligne modifiée
        } catch (PDOException $e) {
            error_log("Erreur SQL dans toggleVisibility: " . $e->getMessage());
            return false;
        }
    }
}

?>