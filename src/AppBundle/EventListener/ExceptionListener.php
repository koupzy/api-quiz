<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        if ($exception instanceof MethodNotAllowedHttpException && $request->headers->has('Origin') && $request->isMethod('OPTIONS')) {
            $response = new Response();
            $response->headers->set('Access-Control-Max-Age', 3600);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', $request->headers->get('Access-Control-Request-Method'));
            $response->headers->set('Access-Control-Allow-Headers', $request->headers->get('Access-Control-Request-Headers'));

            $response->headers->set('X-Status-Code', 200);
            $event->setResponse($response);
        }
    }
}