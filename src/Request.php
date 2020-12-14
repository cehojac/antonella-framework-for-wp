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
    /**
     * Index function
     * Create the process data
     * @return void
     */
    public function __construct()
    {
        $config=new Config();
        $this->process($config->post,'post');
        $this->process($config->get,'get');
    }
    /**
     * process function
     * process the request input (POST and GET)
     * @param array $datas the config array (post and get)
     * @return void
     */
    public function  process($datas){
        require_once( ABSPATH . 'wp-includes/pluggable.php' );
        foreach ($datas as $key => $data){
            if (isset($_REQUEST[$key])){
                call_user_func_array($data,$_REQUEST);
            }
            else{
                continue;
            }
        }
    }
}
