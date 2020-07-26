<?php

namespace App;

use App\Service\ObjectValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    protected ObjectValidator $validator;

    public function __construct(ObjectValidator $validator)
    {
        $this->validator = $validator;
    }
}