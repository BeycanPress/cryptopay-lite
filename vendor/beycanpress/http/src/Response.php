<?php

namespace BeycanPress\Http;

/**
 * A helper class to return meaningful and regular responses.
 * @link https://github.com/BeycanPress/response
 * @author BeycanPress
 * @version 0.1.0
 */
final class Response
{
    /**
     * HTTP Codes
     */
    private const HTTP_OK = 200;
    private const HTTP_BAD_REQUEST = 400;
    private const HTTP_UNAUTHORIZED = 401;
    private const HTTP_FORBIDDEN = 403;
    private const HTTP_NOT_FOUND = 404;
    private const HTTP_NOT_ACCEPTABLE = 406;
    private const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * HTTP Codes message
     */
    private static $statusTexts = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        406 => 'Not Acceptable',
        500 => 'Internal Server Error',
    ];

    /**
     * Method that prints the output as json to the screen
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    private static function json(array $data, int $statusCode) : void
    {
        http_response_code($statusCode);
        echo json_encode($data);
        die;
    }

    /**
     * Helper method to return some error types in ready form.
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @param int $responseCode
     * @return void
     */
    private static function readyErrorResponse(?string $message, ?string $errorCode, $data, int $responseCode) : void
    {
        $readyMessageText = isset(self::$statusTexts[$responseCode]) ? self::$statusTexts[$responseCode] : null;

        self::json([
            'success' => false,
            'errorCode' => $errorCode ? $errorCode : "ER".$responseCode,
            'message' => $message ? $message : $readyMessageText,
            'data' => $data,
        ], $responseCode);
    }

    /**
     * @param string|null $message
     * @param mixed $data
     * @return void
     */
    public static function success(?string $message = null, $data = null) : void
    {
        self::json([
            'success' => true,
            'errorCode' => null,
            'message' => $message,
            'data' => $data
        ], self::HTTP_OK);
    }

    /**
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @param int $responseCode
     * @return void
     */
    public static function error(?string $message = null, ?string $errorCode = null, $data = null, int $responseCode = 200) : void
    {
        self::readyErrorResponse($message, $errorCode, $data, $responseCode);
    }

    /**
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @return void
     */
    public static function badRequest(?string $message = null, ?string $errorCode = null, $data = null) : void
    {
        self::readyErrorResponse($message, $errorCode, $data, self::HTTP_BAD_REQUEST);
    }

    /**
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @return void
     */
    public static function unAuthorized(?string $message = null, ?string $errorCode = null, $data = null) : void
    {
        self::readyErrorResponse($message, $errorCode, $data, self::HTTP_UNAUTHORIZED);
    }

    /**
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @return void
     */
    public static function forbidden(?string $message = null, ?string $errorCode = null, $data = null) : void
    {
        self::readyErrorResponse($message, $errorCode, $data, self::HTTP_FORBIDDEN);
    }

    /**
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @return void
     */
    public static function notFound(?string $message = null, ?string $errorCode = null, $data = null) : void
    {
        self::readyErrorResponse($message, $errorCode, $data, self::HTTP_NOT_FOUND);
    }

    /**
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @return void
     */
    public static function notAcceptable(?string $message = null, ?string $errorCode = null, $data = null) : void
    {
        self::readyErrorResponse($message, $errorCode, $data, self::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param string|null $message
     * @param string|null $errorCode
     * @param mixed $data
     * @return void
     */
    public static function serverInternal(?string $message = null, ?string $errorCode = null, $data = null) : void
    {
        self::readyErrorResponse($message, $errorCode, $data, self::HTTP_INTERNAL_SERVER_ERROR);
    }
}