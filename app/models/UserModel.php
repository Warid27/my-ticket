<?php

require_once 'app/core/BaseModel.php';

class UserModel extends BaseModel
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password', 'role', 'password_reset_token', 'password_reset_expires'];

    public function findByEmail(string $email): ?array
    {
        return $this->queryOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function findByPasswordResetToken(string $token): ?array
    {
        return $this->queryOne("SELECT * FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()", [$token]);
    }

    public function setPasswordResetToken(int $userId, string $token, string $expires): bool
    {
        $result = $this->update($userId, [
            'password_reset_token' => $token,
            'password_reset_expires' => $expires
        ]);
        return $result !== false;
    }

    public function clearPasswordResetToken(int $userId): bool
    {
        $result = $this->update($userId, [
            'password_reset_token' => null,
            'password_reset_expires' => null
        ]);
        return $result !== false;
    }

    public function verifyPassword(int $userId, string $password): bool
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }
}