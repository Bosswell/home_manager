<?php

namespace App\EventListener;

use App\Http\ApiResponse;
use App\Serializer\Normalizer\NormalizerFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionListener
{
    private NormalizerFactory $normalizerFactory;
    private string $env;

    public function __construct(NormalizerFactory $normalizerFactory, ParameterBagInterface $bag)
    {
        $this->normalizerFactory = $normalizerFactory;
        $this->env = $bag->get('kernel.environment');
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = $this->createApiResponse($exception);
        $event->setResponse($response);
    }

    private function createApiResponse(Throwable $exception): Response
    {
        $normalizer = $this->normalizerFactory->getNormalizer($exception);
        $statusCode = $exception->getCode();

        if ($statusCode < 100) {
            $statusCode = 500;
        }

        try {
            $errors = $normalizer ? $normalizer->normalize($exception) : [];
        } catch (Throwable $e) {
            $errors = [];
        }

        return new ApiResponse(
            $this->env === 'dev' ? $this->buildDebugMessage($exception) : $exception->getMessage(),
            $statusCode,
            null,
            $errors,
            [],
            false
        );
    }

    private function buildDebugMessage(Throwable $ex): string
    {
        return sprintf(
            "%s has occurred in file [ %s ] on line %d with message [ %s ]. Stacktrace: %s",
            get_class($ex),
            $ex->getFile(),
            $ex->getLine(),
            $ex->getMessage(),
            $ex->getTraceAsString()
        );
    }
}
