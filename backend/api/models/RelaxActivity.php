<?php

class RelaxActivity
{
    private $pdo;
    private $table = 'relax_activity';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer toutes les activités actives (pour utilisateurs)
     */
    public function getAllActiveRelaxActivities()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY id_activity DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getAllActiveRelaxActivities: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer toutes les activités (admin)
     */
    public function getAllRelaxActivities()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY id_activity DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getAllRelaxActivities: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer une activité précise par ID
     */
    public function getRelaxActivityById($id_activity)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_activity = :id_activity LIMIT 1");
            $stmt->execute([':id_activity' => $id_activity]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getActivityById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Créer une nouvelle activité (admin)
     */
    public function createRelaxActivity($data)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (activity_label, content, category, type, media_activity, is_active) 
                                        VALUES (:activity_label, :content, :category, :type, :media_activity, :is_active)");
            $stmt->execute([
                ':activity_label' => $data['activity_label'],
                ':content' => $data['content'],
                ':category' => $data['category'],
                ':type' => $data['type'],
                ':media_activity' => $data['media_activity'] ?? null,
                ':is_active' => $data['is_active'] ?? 1,
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur SQL dans createActivity: " . $e->getMessage());
            return ["error" => "Erreur lors de la création de l'activité."];
        }
    }

    /**
     * Modifier une activité existante (admin)
     */
    public function updateRelaxActivity($id_activity, $data)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE {$this->table}
                SET 
                    activity_label = :activity_label,
                    content = :content,
                    category = :category,
                    type = :type,
                    media_activity = :media_activity,
                    is_active = :is_active
                WHERE id_activity = :id_activity
            ");
            $stmt->execute([
                ':activity_label' => $data['activity_label'],
                ':content' => $data['content'],
                ':category' => $data['category'],
                ':type' => $data['type'],
                ':media_activity' => $data['media_activity'] ?? null,
                ':is_active' => $data['is_active'] ?? 1,
                ':id_activity' => $id_activity,
            ]);

            return $stmt->rowCount(); // Nombre de lignes modifiées
        } catch (PDOException $e) {
            error_log("Erreur SQL dans updateRelaxActivity: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprimer une activité (admin)
     */
    public function deleteRelaxActivity($id_activity)
    {
      try {
          $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_activity = :id_activity");
          $stmt->execute([':id_activity' => $id_activity]);
          return $stmt->rowCount(); // Nombre de lignes supprimées
      } catch (PDOException $e) {
          error_log("Erreur SQL dans deleteRelaxActivity: " . $e->getMessage());
          return null;
      }
    }

    /**
     * Activer/Désactiver une activité
     */
    public function toggleActiveRelaxActivity($id_activity)
    {
        try {
            // Récupérer l'état actuel
            $stmt = $this->pdo->prepare("SELECT is_active FROM {$this->table} WHERE id_activity = :id_activity");
            $stmt->execute([':id_activity' => $id_activity]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$current) {
                return ["error" => "Activité non trouvée"];
            }

            $newState = $current['is_active'] ? 0 : 1;

            // Mettre à jour le champ
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET is_active = :newState WHERE id_activity = :id_activity");
            $stmt->execute([
                ':newState' => $newState,
                ':id_activity' => $id_activity,
            ]);

            return ["success" => "Activité mise à jour", "is_active" => $newState];
        } catch (PDOException $e) {
            error_log("Erreur SQL dans toggleActiveRelaxActivity: " . $e->getMessage());
            return ["error" => "Erreur lors de la mise à jour de l'activité"];
        }
    }
}

?>
