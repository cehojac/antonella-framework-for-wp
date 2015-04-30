<?php 
namespace App;
use App\config;
use mysqli;
config::database();

class database
{
    static function get($query,$charset)
    {
        $data=array(); 
        $charset?$charset:"ISO-8859-15";
        if(CH_DB_ACCESS==''||CH_DB_ACCESS=='mysqli')
        {
            $mysqli = new mysqli(CH_DB_HOST, CH_DB_USER, CH_DB_PASSWORD, CH_DB_NAME);

            /* verificar conexion */
            if (mysqli_connect_errno())
            {
                echo "Could not connect: ". mysqli_connect_error();
                exit();
            }

            if ($rs = $mysqli->query($query)) 
            {
                $i=1;
                while ($fila = $rs->fetch_assoc()) 
                {
                    foreach ($fila as $col_value) 
                    {
                       $data[$i][]=html_entity_decode(mb_convert_encoding($col_value,CH_DB_CHARSET,$charset));
                    }
                    $i++;
                }
                
                $rs->close();
            }
            $mysqli->close();
            return $data;
        }
        else
        {
            $link = mysql_connect(CH_DB_HOST, CH_DB_USER, CH_DB_PASSWORD)
            or die('Could not connect: ' . mysql_error());
            // 'Connected successfully';
            mysql_select_db(CH_DB_NAME) or die('Could not select database');
            $result = mysql_query($query) or die('Query failed: ' . mysql_error());
            $i=1;
            if (mysql_fetch_array($result))
            {
                while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
                {

                    foreach ($line as $col_value) 
                    {
                        $data[$i][]=html_entity_decode(mb_convert_encoding($col_value,CH_DB_CHARSET,$charset));

                    }
                    $i++;

                }
                mysql_free_result($result);
                mysql_close($link);
                return $data;
            }
            else
            {
                return true;
            }
           
        }
    }
    
    static function delete($table,$col,$signal,$id)
    {
        $query="DELETE FROM ".$table." WHERE ".$col." ".$signal."'".$id."'";
        //die($query);
        $mysqli = new mysqli(CH_DB_HOST, CH_DB_USER, CH_DB_PASSWORD, CH_DB_NAME);

            /* verificar conexion */
            if (mysqli_connect_errno())
            {
                echo "Could not connect: ". mysqli_connect_error();
                exit();
            }

            $mysqli->query($query); 
    }
    static function insert($table,$columns,$vars)
    {
        //die (var_dump($vars));
        $cols=implode(", ",$columns);
        foreach ($vars as $str)
        {
            $str = "'".$str."'";
            $var[]=$str;
        }   
        $val= implode(",",$var);
        $query="INSERT INTO ".$table." (".(string)$cols.") VALUES (".(string)$val.");";
        
        $mysqli = new mysqli(CH_DB_HOST, CH_DB_USER, CH_DB_PASSWORD, CH_DB_NAME);

            /* verificar conexion */
            if (mysqli_connect_errno())
            {
                echo "Could not connect: ". mysqli_connect_error();
                exit();
            }

            ($mysqli->query($query));
        
    }
    
    
    
}
?>