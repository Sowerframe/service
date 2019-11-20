<?php
#coding: utf-8
# +-------------------------------------------------------------------
# | DataNotFoundException
# +-------------------------------------------------------------------
# | Copyright (c) 2017-2019 Sower rights reserved.
# +-------------------------------------------------------------------
# +-------------------------------------------------------------------
declare (strict_types = 1);
namespace sower\service\database\exception;
use sower\exception\DbException;
class DataNotFoundException extends DbException
{
    protected $table;

    /**
     * DbException constructor.
     * @access public
     * @param  string $message
     * @param  string $table
     * @param  array $config
     */
    public function __construct(string $message, string $table = '', array $config = [])
    {
        $this->message = $message;
        $this->table   = $table;

        $this->setData('Database Config', $config);
    }

    /**
     * 获取数据表名
     * @access public
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}
