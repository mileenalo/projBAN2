<?php

declare(strict_types=1);

//

class Logs {

    /** @var number */
    public number $invoice_id;

    /** @var number */
    public number $number;

    /** @var number */
    public number $serie;

    /** @var String */
    public String $key;

    /** @var String */
    public String $date;

    /** @var number */
    public number $price;

    /**
     * @param  $name
     */
    public function createLog($userId, $message){
        $db = new Database();

        $date = date("Y-m-d H:m:s");

        $log = $db->_exec("INSERT INTO tb_logs (lg_userId, lg_date, lg_description)
                             VALUES ({$userId}, '{$date}', '{$message}') ");
        
        if($log == true){
            return $log;
        }else{
            return 0;
        }
    }

    /**
     * @param  $name
     */
    public function listLog(){
        include("./db.php");
        $db = new Database();
        
        $listLogs = $db->_query("SELECT * FROM tb_logs 
                            INNER JOIN tb_users ON lg_userId = usu_userId ORDER BY lg_logId");
        
        return $listLogs;
    }

}
