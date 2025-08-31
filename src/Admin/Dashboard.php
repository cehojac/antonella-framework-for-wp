<?php 
namespace CH\Admin;

use CH\Config;

class Dashboard
{
    var $array_dashboard;
    var $files_dashboard;
    public function __construct()
    {
        $this->array_dashboard = Config::get('admin.dashboard', []);
        $this->files_dashboard = Config::get('admin.files_dashboard', []);
    }

    public static function index()
    {
        $dashboard= new Dashboard;
        $datas=$dashboard->array_dashboard;
        if (count($datas)>0&&$datas[0]['name']<>'')
        {
            foreach($datas as $data)
            {
                wp_add_dashboard_widget($data['slug'],$data['name'],$data['function'],$data['callback'],$data['args']);
            }
        }
    }

    public function scripts($hook)
    {
        
        if( 'index.php' != $hook ) {
            return;
        }
        $datas=$this->files_dashboard;
        foreach($datas as $data)
        {
             wp_enqueue_style( 'dashboard-widget-styles', plugins_url( '', __FILE__ ) . $data, array(), '1.0.0' );
        }
	   
    }
}