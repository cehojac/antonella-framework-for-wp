<?php
/**
* No modify this file !!!
*/
namespace CH;

use CH\Config;

class Request
{
    public $post_data=array();
    public $get_data=array();

    public function __construct()
    {
        $config=new Config();
        $this->process($config->post,'post');
        $this->process($config->get,'get');
    }


    public function  process($datas,$from)
    {
       foreach ($datas as $key => $data)
       {
           if ($from=='post'&&isset($_POST[$key]))
           {
               call_user_func_array($data,$_POST);
           }

           if ($from='get'&&isset($_GET[$key]))
           {
               call_user_func_array($data,$_GET);
           }
       }
    }


}
