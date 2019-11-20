<?php declare (strict_types = 1);
#coding: utf-8
# +-------------------------------------------------------------------
# | 验证服务类
# +-------------------------------------------------------------------
# | Copyright (c) 2017-2019 Sower rights reserved.
# +-------------------------------------------------------------------
# +-------------------------------------------------------------------
namespace sower\service\validate;
use sower\Service;
use sower\Validate;
class ValidateService extends Service
{
    public function boot()
    {
        Validate::maker(function (Validate $validate) {
            $validate->setLang($this->app->lang);
            $validate->setDb($this->app->db);
            $validate->setRequest($this->app->request);
        });
    }
}
