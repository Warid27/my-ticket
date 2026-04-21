<?php

require_once 'app/core/BaseModel.php';

class UserModel extends BaseModel
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password', 'role'];

    public function findByEmail(string $email): ?array
    {
        return $this->queryOne("SELECT * FROM users WHERE email = ?", [$email]);
    }
}