<?php
namespace App\secure\forms;
    
class forms
{
    static public function maketoken($form,$type)
    {
        // make two methods for token. session or globals. DEFAULT: globals
        if($type=='session')
        {
            $token=md5(CH_KEY.time());
            $token_time=time();
            $_SESSION['chtoken']['token_'.$form] = array('token'=>$token,'time'=>$token_time);
            return $token;
        }
        else
        {
            $secret=CH_KEY;
            $sid= session_id();
            $token=md5($secret.$sid.$form);
            return $token;
        }
    }
    
    static public function verifytoken($form,$token,$delta_time=0,$type)
    {
        if($type=='session')
        {
            // verify if exist the token session
            if(!isset($_SESSION['chtoken']['token_'.$form])) {
                return false;
            }
            //compare token data
            if ($_SESSION['chtoken']['token_'.$form]['token'] !== $token) {
                return false;
            }

            //compare actual time and make time token
            if($delta_time > 0)
            {
                $token_age = time() - $_SESSION['chtoken']['token_'.$form]['time'];
                if($token_age >= $delta_time)
                {
                    return false;
                }
            }
            //else...
            return true;
        }
        else
        {
            $secret=CH_KEY;
            $sid= session_id();
            $correct=md5($secret.$sid.$form);
            return ($token==$correct);
        }
    }
}

?>