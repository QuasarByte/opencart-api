<?php

abstract class QuasarByteOCRestBaseController extends Controller
{
    function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language('api/quasarbyte/ocrest/v1/qbocrest_base_controller');
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if ($error != null) {
            $this->errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    public function errorHandler($errno, $errstr, $errfile, $errline) {

        header('php-error-handler: ' . base64_encode(
                json_encode(
                    array('errno' => $errno,
                        'errstr' => $errstr,
                        'errfile' => $errfile,
                        'errline' => $errline,
                    )
                )
            ), false);

        ob_flush();

        if ($errno & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_RECOVERABLE_ERROR | E_STRICT | E_USER_ERROR)) {
            http_response_code(500);
            exit();
        }
        return true;
    }

    protected function changeErrorHandler()
    {
        $this->changeErrorHandlerInternal();
    }

    private function changeErrorHandlerInternal()
    {
        ob_start();
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 'Off');
        set_error_handler([$this, 'errorHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

}