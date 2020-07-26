<?php


namespace App\Http\Request;

use App\Serializer\SerializerFactory;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        if ($request->getContentType() !== 'json') {
            throw new HttpException(
                Response::HTTP_NOT_ACCEPTABLE,
                'Invalid request. Make sure that you are using application/json content type'
            );
        }

        $message = $this->serializer->deserialize($body, $configuration->getClass(), 'json');
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