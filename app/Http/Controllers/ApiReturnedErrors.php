<?php

namespace App\Http\Controllers;




class ApiReturnedErrors extends Controller
{

    private static  $NOT_FOUND = 404;
    private static $YEEEP = 403;

    private static $ERRORS_MSGS = [
        404 => "Not Found",
        403 => "Another Error"
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Función que dado el error de entrada devuelve el codigo http de salida
     */
    private static function getErrorCode($errorCode)
    {
        if (isset(self::$$errorCode))
            return self::$$errorCode;
        return self::$NOT_FOUND;
    }

    /**
     * Function that returns error structure whenever there is an error
     * @param string $errorCode example NOT_FOUND
     * @param string $additionalInfo Additional info to display
     */
    public static function returnErrorArray(string $errorCode, string $additionalInfo = '')
    {
        $returnHttpCode = static::getErrorCode(strtoupper($errorCode));
        $msgError = self::$ERRORS_MSGS[$returnHttpCode];

        return response([
            'status' => 'ERROR',
            'error' => $returnHttpCode . ' ' . $msgError . (!empty($additionalInfo) ? ' ' . $additionalInfo : '')
        ], $returnHttpCode);
    }

    //
}
