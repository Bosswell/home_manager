<?php

namespace App\Http\Request;

use App\Serializer\SerializerFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;


class QueryMessageConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;
    private string $env;

    public function __construct(SerializerFactory $serializerFactory, ParameterBagInterface $bag)
    {
        $this->serializer = $serializerFactory->getInstance();
        $this->env = $bag->get('kernel.environment');
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $options = $request->query->get('options');

        try {
            $class = $configuration->getClass();

            if ($options) {
                $message = $this->serializer->deserialize($options, $class, 'json');
            } else {
                $message = new $class();
            }

            $request->attributes->set($configuration->getName(), $message);
        } catch (\Throwable $ex) {
            if ($this->env === 'dev') {
                throw $ex;
            }

            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid request');
        }

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        if ($configuration->getConverter() !== 'query_message_converter') {
            return false;
        }

        if ((bool)preg_match('/App\\\Message\\\.*/', $configuration->getClass())) {
            return true;
        }

        return false;
    }
}