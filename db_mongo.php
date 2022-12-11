<?php

class DBMongo{
    
    public function search($search, $table){
	    try{
	    	$conn = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");
        }catch (MongoDBDriverExceptionException $e) {
	    	echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
	    	echo $e->getMessage();
	    	exit();
	    }

        $filter = ['_id' => new MongoDB\BSON\ObjectId($search)];

        $query = new MongoDB\Driver\Query($filter, );
        $rows = $conn->executeQuery("db_projeto.".$table, $query);

        return $rows;

    }

    public function search2($field, $search, $table){
	    try{
	    	$conn = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");
        }catch (MongoDBDriverExceptionException $e) {
	    	echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
	    	echo $e->getMessage();
	    	exit();
	    }

        $filter = [$field => new MongoDB\BSON\ObjectId($search)];

        $query = new MongoDB\Driver\Query($filter, );
        $rows = $conn->executeQuery("db_projeto.".$table, $query);

        return $rows;

    }

    public function search3($field1, $search1, $field2, $search2, $table){
	    try{
	    	$conn = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");
        }catch (MongoDBDriverExceptionException $e) {
	    	echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
	    	echo $e->getMessage();
	    	exit();
	    }

        $filter = [$field1 => $search1, $field2 => $search2];

        $query = new MongoDB\Driver\Query($filter, );
        $rows = $conn->executeQuery("db_projeto.".$table, $query);

        return $rows;

    }

    public function searchAll($field, $table){
        try{
            $conn = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");
        }catch (MongoDBDriverExceptionException $e) {
            echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
            echo $e->getMessage();
            exit();
        }
        
        //$filter = [$field => $search];
        $query = new MongoDB\Driver\Query([], [$field => 1]);
        $rows = $conn->executeQuery("db_projeto.".$table, $query);
        
        return $rows;        
    }

    public function insert($doc, $table){
        $bulk = new MongoDB\Driver\BulkWrite;       
        $insItem = $bulk->insert($doc);

        $manager = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");

        $result = $manager->executeBulkWrite("db_projeto.".$table, $bulk);

        $aId = $insItem;
        echo $aId;
        return $aId;
    }

    public function update($id, $doc, $table){
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => $doc],
        );

        $manager = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");

        $result = $manager->executeBulkWrite("db_projeto.".$table, $bulk);
        
        return true;
    }
    public function update2($id, $id2, $field, $doc, $table){
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => new MongoDB\BSON\ObjectId($id), $field => MongoDB\BSON\ObjectId($id2)],
            ['$set' => $doc],
        );

        $manager = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");

        $result = $manager->executeBulkWrite("db_projeto.".$table, $bulk);
        
        return true;
    }

    public function delete($id, $table){
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($id)],);
       
        $manager = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");

        $result = $manager->executeBulkWrite("db_projeto.".$table, $bulk);

        return true;
    }

    public function delete2($id, $field, $table){
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete([$field => new MongoDB\BSON\ObjectId($id)],);
       
        $manager = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");

        $result = $manager->executeBulkWrite("db_projeto.".$table, $bulk);

        return true;
    }

    public function innerSelect($aggregate){

        $results = $this->getCollection()->aggregate($aggregate);

        return $results;
    }
}

?>
