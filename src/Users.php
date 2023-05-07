<?php

namespace CH;

class Users
{
    public $user;
    public function __construct()
    {
         $this->user = wp_get_current_user();
    }
}
