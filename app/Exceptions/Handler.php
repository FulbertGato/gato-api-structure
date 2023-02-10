<?php

namespace App\Exceptions;

use App\Enums\ErrorCodes;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use App\Traits\ApiResponse;
use Exception;

class Handler extends ExceptionHandler
{
    use ApiResponse;


    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            return false;
        });
        $this->renderable(function (Exception $exception, $request) {
            return $this->handleException($request, $exception);
        });
    }

    public function handleException($request, Exception $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse([], "La méthode n'est pas autorisée", ErrorCodes::method_not_allowed, 405);
        }
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse([], "La ressource n'existe pas", ErrorCodes::page_not_found, 404);
        }
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->errorResponse([], "Vous n'êtes pas autorisé à accéder à cette ressource", ErrorCodes::invalid_token, 401);
        }
        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return $this->errorResponse([], "Vous n'êtes pas autorisé à accéder à cette ressource", ErrorCodes::invalid_token, 401);
        }
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->errorResponse($exception->errors(), "Une erreur est survenue lors de l'execution de la requête", ErrorCodes::invalid_request, 400);
        }
        if ($exception instanceof HttpException) {
            return $this->errorResponse([], "Une erreur est survenue lors de l'execution de la requête", ErrorCodes::bad_request, 500);
        }
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->errorResponse($exception->getMessage(), 409);
            }
        }
        if (config('app.debug')) {
            try {
                return parent::render($request, $exception);
            } catch (Throwable $e) {
                $errosData = array('message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine(), 'trace' => $e->getTrace(),);
                return $this->errorResponse($errosData, $e->getMessage(), 500, 500);
            }
        }
        return $this->errorResponse([], "Une erreur est survenue lors de l'execution de la requête", 500, 500);

    }
}

