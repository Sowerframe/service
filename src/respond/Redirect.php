<?php
#coding: utf-8
# +-------------------------------------------------------------------
# | Redirect Response
# +-------------------------------------------------------------------
# | Copyright (c) 2017-2019 Sower rights reserved.
# +-------------------------------------------------------------------
# +-------------------------------------------------------------------
namespace sower\service\respond;
use sower\Request;
use sower\Response;
use sower\Route;
use sower\Session;
class Redirect extends Response
{

    protected $options = [];

    // URL参数
    protected $params = [];
    protected $routes;
    protected $request;

    public function __construct(Route $routes, Request $request, Session $session, $data = '', int $code = 302)
    {
        parent::__construct($data, $code);
        $this->routes   = $routes;
        $this->request = $request;
        $this->session = $session;

        $this->cacheControl('no-cache,must-revalidate');
    }

    /**
     * 处理数据
     * @access protected
     * @param  mixed $data 要处理的数据
     * @return string
     */
    protected function output($data): string
    {
        $this->header['Location'] = $this->getTargetUrl();

        return '';
    }

    /**
     * 重定向传值（通过Session）
     * @access protected
     * @param  string|array  $name 变量名或者数组
     * @param  mixed         $value 值
     * @return $this
     */
    public function with($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->session->flash($key, $val);
            }
        } else {
            $this->session->flash($name, $value);
        }

        return $this;
    }

    /**
     * 获取跳转地址
     * @access public
     * @return string
     */
    public function getTargetUrl()
    {
        if (strpos($this->data, '://') || (0 === strpos($this->data, '/') && empty($this->params))) {
            return $this->data;
        } else {
            return $this->routes->buildUrl($this->data, $this->params);
        }
    }

    public function params($params = [])
    {
        $this->params = $params;

        return $this;
    }

    /**
     * 记住当前url后跳转
     * @access public
     * @return $this
     */
    public function remember()
    {
        $this->session->set('redirect_url', $this->request->url());

        return $this;
    }

    /**
     * 跳转到上次记住的url
     * @access public
     * @return $this
     */
    public function restore()
    {
        if ($this->session->has('redirect_url')) {
            $this->data = $this->session->get('redirect_url');
            $this->session->delete('redirect_url');
        }

        return $this;
    }
}
