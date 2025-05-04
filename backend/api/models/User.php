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

            // Récupérer l'utilisateur actuel
            $currentUser = $this->getUserById($id_user);

            foreach ($data as $field => $value) {
                if (!in_array($field, $allowedFields)) continue;

                // Vérifier l'unicité de l'email uniquement si l'email a changé
                if ($field === 'email' && $value !== $currentUser['email']) {
                    $existingUser = $this->getUserByEmail($value);
                    if ($existingUser && $existingUser['id_user'] != $id_user) {
                        return ["error" => "Email déjà utilisé"];
                    }
                }

                // Vérifier l'unicité du username uniquement si le username a changé
                if ($field === 'username' && $value !== $currentUser['username']) {
                    $existingUser = $this->getUserByUsername($value);
                    if ($existingUser && $existingUser['id_user'] != $id_user) {
                        return ["error" => "Nom d'utilisateur déjà pris"];
                    }
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
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_user = :id_user");
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ["message" => "Utilisateur supprimé avec succès"];
            } else {
                return ["error" => "Utilisateur non trouvé ou déjà supprimé"];
            }
        } catch (PDOException $e) {
            error_log("Erreur SQL dans deleteUser: " . $e->getMessage());
            return ["error" => "Erreur lors de la suppression"];
        }
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT id_user, username, email, is_admin, is_active, created_at FROM {$this->table} ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getAllUsersSafe: " . $e->getMessage());
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

    public function toggleIsActiveUser($id_user)
    {
        try {
            // Récupérer l'état actuel
            $stmt = $this->pdo->prepare("SELECT is_active FROM {$this->table} WHERE id_user = :id_user");
            $stmt->execute([':id_user' => $id_user]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ["error" => "Utilisateur non trouvé"];
            }

            $newStatus = $user['is_active'] ? 0 : 1;

            // Mise à jour
            $updateStmt = $this->pdo->prepare("UPDATE {$this->table} SET is_active = :newStatus WHERE id_user = :id_user");
            $updateStmt->execute([
                ':newStatus' => $newStatus,
                ':id_user' => $id_user
            ]);

            return ["message" => "État du compte mis à jour", "is_active" => $newStatus];
        } catch (PDOException $e) {
            error_log("Erreur SQL dans toggleIsActiveUser: " . $e->getMessage());
            return ["error" => "Erreur serveur lors de la mise à jour"];
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


    public function getUser($criteria)
    {
        try {
            if (empty($criteria)) {
                return ["error" => "Aucun critère fourni"];
            }

            $conditions = [];
            $params = [];

            if (isset($criteria['id_user'])) {
                $conditions[] = 'id_user = :id_user';
                $params[':id_user'] = $criteria['id_user'];
            }
            if (isset($criteria['username'])) {
                $conditions[] = 'username = :username';
                $params[':username'] = $criteria['username'];
            }
            if (isset($criteria['email'])) {
                $conditions[] = 'email = :email';
                $params[':email'] = $criteria['email'];
            }

            if (empty($conditions)) {
                return ["error" => "Critères invalides"];
            }

            $sql = "SELECT id_user, username, email, is_admin, is_active, created_at, updated_at 
                    FROM users 
                    WHERE " . implode(' OR ', $conditions) . " LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ["error" => "Utilisateur non trouvé"];
            }

            return $user;
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getUser: " . $e->getMessage());
            return ["error" => "Erreur serveur"];
        }
    }

    public function getUserByIdWithPassword($id_user)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id_user, username, email, is_admin, password, created_at FROM {$this->table} WHERE id_user = :id_user AND deleted_at IS NULL LIMIT 1");
            $stmt->execute([':id_user' => $id_user]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return null;
        }
    }
}
