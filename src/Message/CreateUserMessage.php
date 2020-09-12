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
        $this->fullName = $data['fullName'] ?? '';
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setConfirmPassword($confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
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

    public function setPassword($password): void
    {
        $this->password = (string)$password;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }
}
