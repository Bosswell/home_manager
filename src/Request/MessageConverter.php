<?php


namespace App\Request;

use App\SerializerFactory;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class MessageConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerFactory $serializerFactory)
    {
        $this->serializer = $serializerFactory->getInstance();
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $body = $request->getContent();

        $message = $this->serializer->deserialize($body, $configuration->getClass(), 'JSON');
        $request->attributes->set($configuration->getName(), $message);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        if ((bool)preg_match('/App\\\Message\\\.*/', $configuration->getClass())) {
            return true;
        }

        return false;
    }
}