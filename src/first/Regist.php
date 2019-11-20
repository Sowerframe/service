<?php declare (strict_types = 1);
#coding: utf-8
# +-------------------------------------------------------------------
# | 注册系统服务
# +-------------------------------------------------------------------
# | Copyright (c) 2017-2019 Sower rights reserved.
# +-------------------------------------------------------------------
# +-------------------------------------------------------------------
namespace sower\service\first;
use sower\App;
use sower\driver\model\service\Server as ModelService;
use sower\service\PaginatorService;
use sower\service\validate\ValidateService;
class Regist
{

    protected $services = [
        ValidateService::class,
        ModelService::class,
    ];

    public function init(App $app)
    {
        $file = $app->getRootPath() . 'config' . DIRECTORY_SEPARATOR . 'services.php';

        $services = $this->services;

        if (is_file($file)) {
            $services = array_merge($services, include $file);
        }

        foreach ($services as $service) {
            if (class_exists($service)) {
                $app->register($service);
            }
        }
    }
}
