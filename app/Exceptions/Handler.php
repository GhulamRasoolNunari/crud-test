<?php

namespace App\Exceptions;

use App\Traits\ApiResponseSender;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseSender;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Mappings between exception class name and their codes
     *
     * @const array
     */
    public const EXCEPTION_CLASS_CODE_MAP = [
        'basic' => Response::HTTP_INTERNAL_SERVER_ERROR,
        QueryException::class => Response::HTTP_INTERNAL_SERVER_ERROR,
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
        ModelNotFoundException::class => Response::HTTP_NOT_FOUND,
        AuthorizationException::class => Response::HTTP_FORBIDDEN,
        AuthenticationException::class => Response::HTTP_UNAUTHORIZED,
        JWTException::class => Response::HTTP_UNAUTHORIZED,
        MethodNotAllowedHttpException::class => Response::HTTP_METHOD_NOT_ALLOWED,
        ValidationException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
    ];
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @note $e (and not $exception) because parent class uses $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse
    {
        if (
            $e instanceof QueryException ||
            $e instanceof AuthorizationException ||
            $e instanceof AuthenticationException ||
            $e instanceof MethodNotAllowedHttpException
        ) {
            $message = $e->getMessage();

            $code = self::EXCEPTION_CLASS_CODE_MAP[get_class($e)] ?? self::EXCEPTION_CLASS_CODE_MAP['basic'];
            return $this->errorResponse($message, $code);
        }

        if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));
            $code = self::EXCEPTION_CLASS_CODE_MAP[get_class($e)];

            return $this->errorResponse(
                "Does not exist any instance of $model with the given id",
                $code
            );
        }

        if ($e instanceof ValidationException) {
            $errors = $e->errors();
            $code = self::EXCEPTION_CLASS_CODE_MAP[get_class($e)];
            return $this->errorResponse($errors, $code);
        }

        if ($e instanceof NotFoundHttpException) {
            $code = $e->getStatusCode();
            $message = Response::$statusTexts[$code];

            return $this->errorResponse($message, $code);
        }

        if ($e instanceof ClientException) {
            $message = $e->getResponse()->getBody();
            $code = $e->getResponse()->getStatusCode();

            return $this->errorMessage($message, $code);
        }

        if ($e instanceof RequestException) {
            $message = $e->response->json()['error'];
            $code = $e->getCode();

            return $this->errorResponse($message, $code);
        }

        if (env('APP_DEBUG')) {
            return $this->errorResponse(
                ['message' => $e->getMessage()],
                self::EXCEPTION_CLASS_CODE_MAP['basic']
            );
        }
        
        return $this->errorResponse($e->getMessage(), self::EXCEPTION_CLASS_CODE_MAP['basic']);
    }
}
