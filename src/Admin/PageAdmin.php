<?php

namespace CH\Admin;

class PageAdmin extends Admin
{
    public static function index()
    {
        //  return view('index', ['name' => 'John Doe']);
    }

    public static function DashboardExample($post, $callback_args)
    {
        echo 'Hello World, this is my first Dashboard Widget!';
    }
}
