<?php
/**
* No modify this file !!!
*/

namespace Antonella\CH;

class Request
{
    public $post_data = [];
    public $get_data = [];

    /**
     * Index function
     * Create the process data.
     *
     * @return void
     */
    public function __construct()
    {
        $config = new Config();
        $this->process($config->post, 'post');
        $this->process($config->get, 'get');
    }

    /*
    public static function index(){
        $config=new Config();
        $request = new Request();
        $request->process($config->post);
        $request->process($config->get);
    }*/

    /**
     * process function
     * process the request input (POST and GET).
     *
     * @param [type] $datas the config array (post and get)
     *
     * @return void
     */
    public function process($datas)
    {
        require_once ABSPATH.'wp-includes/pluggable.php';
        foreach ($datas as $key => $data) {
            if (isset($_REQUEST[$key])) {
                call_user_func_array($data, $_REQUEST);
            } else {
                continue;
            }
        }
    }
}
