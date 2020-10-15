<?php

namespace App\Command;

use App\Message\CreateUserMessage;
use App\Service\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


class CreateUserCommand extends Command
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        parent::__construct('app:create-user');

        $this->userManager = $userManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Email address: ');
        $email = $helper->ask($input, $output, $question);

        $question = new Question('Password: ');
        $question->setHidden(true);
        $password = $helper->ask($input, $output, $question);

        $question = new Question('Confirm password: ');
        $question->setHidden(true);
        $confirmPassword = $helper->ask($input, $output, $question);

        $message = new CreateUserMessage([
            'email' => $email,
            'password' => $password,
            'confirmPassword' => $confirmPassword
        ]);

        $this->userManager->createUser($message);

        return Command::SUCCESS;
    }
}