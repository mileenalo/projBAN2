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
        $db = new DBMongo(); 

        $date = date("Y-m-d H:m:s");

        $doc = [ "lg_userId" => $userId, "lg_date" => $date, "lg_description" => $message ];
        $table = "tb_logs";

        $log = $db->insert($doc, $table);
    
        return $log;
    }

    /**
     * @param  $name
     */
    public function listLog(){
        include("./db_mongo.php");
        
        $aggregate = [
            'aggregate' => 'tb_logs',
            'cursor' => new stdClass,
            'pipeline' => [
                ['$lookup' => ["from" => "tb_users","localField" => "lg_userId","foreignField" => "_id","as" => "user_log"]],
            ], 
        ];
        
        $db = new DBMongo(); 

        $listLogs = $db->innerSelect($aggregate);
        
        return $listLogs;
    
    }

}
