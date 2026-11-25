<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;
use Slim\Views\Twig;

class HttpErrorHandler extends SlimErrorHandler
{
    private ?Twig $view = null;

    public function setTwig(Twig $view): void
    {
        $this->view = $view;
    }

    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;

        // Default error type
        $errorType = ActionError::SERVER_ERROR;
        $errorMessage = 'Terjadi kesalahan internal.';

        // Mapping tipe error dari exception
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $errorMessage = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $errorType = ActionError::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $errorType = ActionError::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $errorType = ActionError::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $errorType = ActionError::INSUFFICIENT_PRIVILEGES;
            } elseif ($exception instanceof HttpBadRequestException) {
                $errorType = ActionError::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $errorType = ActionError::NOT_IMPLEMENTED;
            }
        }

        // Jika bukan HttpException dan detail error ditampilkan
        if (!($exception instanceof HttpException) && $this->displayErrorDetails && $exception instanceof Throwable) {
            $errorMessage = $exception->getMessage();
        }

        // Jika ada view dan template error tersedia, render pakai Twig
        if ($this->view instanceof Twig) {
            $template = match ($statusCode) {
                404 => 'errors/404.twig',
                403 => 'errors/403.twig',
                default => 'errors/500.twig',
            };

            try {
                $response = $this->responseFactory->createResponse($statusCode);
                return $this->view->render($response, $template, [
                    'message' => $errorMessage,
                    'code' => $statusCode,
                ]);
            } catch (\Throwable $e) {
                // Jika render gagal, lanjutkan ke JSON
            }
        }

        // Fallback JSON
        $error = new ActionError($errorType, $errorMessage);
        $payload = new ActionPayload($statusCode, null, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
