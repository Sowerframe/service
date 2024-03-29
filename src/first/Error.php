<?php declare (strict_types = 1); 
#coding: utf-8
# +-------------------------------------------------------------------
# | 错误和异常处理
# +-------------------------------------------------------------------
# | Copyright (c) 2017-2019 Sower rights reserved.
# +-------------------------------------------------------------------
# +-------------------------------------------------------------------
namespace sower\service\first;
use sower\App;
use sower\console\Output as ConsoleOutput;
use sower\exception\ErrorException;
use sower\exception\Handle;
use Throwable;
class Error
{
    /** @var App */
    protected $app;

    /**
     * 注册异常处理
     * @access public
     * @param App $app
     * @return void
     */
    public function init(App $app)
    {
        $this->app = $app;
        error_reporting(E_ALL);
        set_error_handler([$this, 'appError']);
        set_exception_handler([$this, 'appException']);
        register_shutdown_function([$this, 'appShutdown']);
    }

    /**
     * Exception Handler
     * @access public
     * @param \Throwable $e
     */
    public function appException(Throwable $e): void
    {
        $handler = $this->getExceptionHandler();

        $handler->report($e);

        if ($this->app->runningInConsole()) {
            $handler->renderForConsole(new ConsoleOutput, $e);
        } else {
            $handler->render($this->app->request, $e)->setCookie($this->app->cookie)->send();
        }
    }

    /**
     * Error Handler
     * @access public
     * @param integer $errno   错误编号
     * @param string  $errstr  详细错误信息
     * @param string  $errfile 出错的文件
     * @param integer $errline 出错行号
     * @throws ErrorException
     */
    public function appError(int $errno, string $errstr, string $errfile = '', int $errline = 0): void
    {
        $exception = new ErrorException($errno, $errstr, $errfile, $errline);

        if (error_reporting() & $errno) {
            // 将错误信息托管至 sower\exception\ErrorException
            throw $exception;
        }
    }

    /**
     * Shutdown Handler
     * @access public
     */
    public function appShutdown(): void
    {
        if (!is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            // 将错误信息托管至Sower\ErrorException
            $exception = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);

            $this->appException($exception);
        }
    }

    /**
     * 确定错误类型是否致命
     *
     * @access protected
     * @param int $type
     * @return bool
     */
    protected function isFatal(int $type): bool
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    /**
     * Get an instance of the exception handler.
     *
     * @access protected
     * @return Handle
     */
    protected function getExceptionHandler()
    {
        return $this->app->make(Handle::class);
    }
}
