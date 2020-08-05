<?php

namespace App\Message;


final class CreateUserMessage
{
    private string $email;
    private string $password;
    private string $confirmPassword;
    private string $fullName;

    public function __construct(?array $data = null)
    {
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->confirmPassword = $data['confirmPassword'] ?? '';
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setEmail($email): void
    {
        $this->email = (string)$email;
    }

    public function setPassword($password): void
    {
        $this->password = (string)$password;
    }

    public function setConfirmPassword($confirmPassword): void
    {
        $this->confirmPassword = (string)$confirmPassword;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }
}
