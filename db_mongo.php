<?php

class DBMongo{
    
    public function search($field, $search, $table){
	    try{
	    	$conn = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");
        }catch (MongoDBDriverExceptionException $e) {
	    	echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
	    	echo $e->getMessage();
	    	exit();
	    }

        $filter = [$field => $search];
        $query = new MongoDB\Driver\Query($filter, );
        $rows = $conn->executeQuery("db_projeto.".$table, $query);

        return $rows;
        /*foreach ($rows as $row) {
            echo "$row->_id : $row->cs_name\n";
        }*/

    }
}
	
?>
