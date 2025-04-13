<?php

class User
{
    private $pdo;
    private $table = 'users';

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }

    public function createUser($username, $email, $password, $is_admin = false)
    {
        try {
            if ($this->getUserByEmail($email)) {
                return ["error" => "Email déjà utilisé"];
            }

            if ($this->getUserByUsername($username)) {
                return ["error" => "Nom d'utilisateur déjà pris"];
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO {$this->table} (username, email, password, is_admin, terms_accepted, accepted_terms_at, created_at, updated_at)
                    VALUES (:username, :email, :password, :is_admin, 1, NOW(), NOW(), NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':is_admin' => $is_admin ? 1 : 0
            ]);

            return ["message" => "Utilisateur créé avec succès"];

        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return ["error" => "Erreur lors de la création de l'utilisateur"];
        }
    }

    public function updateUser($id_user, $data)
    {
        try {
            $allowedFields = ['username', 'email', 'password', 'is_admin'];
            $updates = [];
            $params = [':id_user' => $id_user];

            foreach ($data as $field => $value) {
                if (!in_array($field, $allowedFields)) continue;

                if ($field === 'email' && $this->getUserByEmail($value)) {
                    return ["error" => "Email déjà utilisé"];
                }

                if ($field === 'username' && $this->getUserByUsername($value)) {
                    return ["error" => "Nom d'utilisateur déjà pris"];
                }

                if ($field === 'password') {
                    $value = password_hash($value, PASSWORD_BCRYPT);
                }

                $updates[] = "$field = :$field";
                $params[":$field"] = $value;
            }

            if (empty($updates)) {
                return ["error" => "Aucun champ à mettre à jour"];
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id_user = :id_user";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return ["message" => "Utilisateur mis à jour"];

        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return ["error" => "Erreur lors de la mise à jour"];
        }
    }

    public function deleteUser($id_user)
    {
        try {
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id_user = :id_user";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_user' => $id_user]);

            return ["message" => "Utilisateur supprimé (soft delete)"];
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return ["error" => "Erreur lors de la suppression"];
        }
    }

    public function getAllUsers()
    {
        try {
            $sql = "SELECT id_user, username, email, is_admin, is_active, created_at FROM {$this->table} WHERE deleted_at IS NULL";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return [];
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email AND deleted_at IS NULL LIMIT 1");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getUserByEmail: " . $e->getMessage());
            return null;
        }
    }

    public function getUserByUsername($username)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = :username AND deleted_at IS NULL LIMIT 1");
            $stmt->execute([':username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return null;
        }
    }

    public function getUserById($id_user)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id_user, username, email, is_admin, created_at FROM {$this->table} WHERE id_user = :id_user AND deleted_at IS NULL LIMIT 1");
            $stmt->execute([':id_user' => $id_user]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return null;
        }
    }

    public function changeStatus($id_user)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT is_active FROM {$this->table} WHERE id_user = :id_user");
            $stmt->execute([':id_user' => $id_user]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ["error" => "Utilisateur non trouvé"];
            }

            $newStatus = $user['is_active'] ? 0 : 1;

            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET is_active = :status WHERE id_user = :id_user");
            $stmt->execute([':status' => $newStatus, ':id_user' => $id_user]);

            return ["message" => "Statut mis à jour", "new_status" => $newStatus];

        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return ["error" => "Erreur lors de la mise à jour du statut"];
        }
    }

    public function resetPassword($email, $newPassword)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id_user FROM {$this->table} WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ["error" => "Email non trouvé"];
            }

            $hashed = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET password = :pwd, updated_at = NOW() WHERE email = :email");
            $stmt->execute([':pwd' => $hashed, ':email' => $email]);

            return ["message" => "Mot de passe mis à jour"];

        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return ["error" => "Erreur lors du changement de mot de passe"];
        }
    }
}
