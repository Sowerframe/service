<?php
#coding: utf-8
# +-------------------------------------------------------------------
# | PDO参数绑定异常
# +-------------------------------------------------------------------
# | Copyright (c) 2017-2019 Sower rights reserved.
# +-------------------------------------------------------------------
# +-------------------------------------------------------------------
declare (strict_types = 1);
namespace sower\service\database\exception;
use sower\exception\DbException;
class BindParamException extends DbException
{

    /**
     * BindParamException constructor.
     * @access public
     * @param  string $message
     * @param  array  $config
     * @param  string $sql
     * @param  array    $bind
     * @param  int    $code
     */
    public function __construct(string $message, array $config, string $sql, array $bind, int $code = 10502)
    {
        $this->setData('Bind Param', $bind);
        parent::__construct($message, $config, $sql, $code);
    }
}
