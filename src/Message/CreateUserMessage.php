<?php

namespace App\Message;


final class CreateUserMessage
{
    private string $email;
    private string $password;
    private string $confirmPassword;

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

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = (string)$email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = (string)$password;
    }

    /**
     * @param mixed $confirmPassword
     */
    public function setConfirmPassword($confirmPassword): void
    {
        $this->confirmPassword = (string)$confirmPassword;
    }
}
