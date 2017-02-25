<?php

    /*
        pull the data from the API by page
    */
    function getJsonData($pageNumber){
        
        $str = file_get_contents("https://api-v2.themuse.com/jobs?page=". $pageNumber );
        $json = json_decode($str, true);
        
        return $json;
    }


    /*
        returns the maximumum number of pages in the data source
    */
    function getMaxPageCount(){
    
        $json = getJsonData(1);
        return $json['page_count'];
    }


    /*
        Pulls the data from the api on the page specified by the parameter passed in.
        Loops through the data and insert into Db.
    */
    function pullJobData($pageNumber){
        
          
//        $str = file_get_contents("https://api-v2.themuse.com/jobs?page=". $pageNumber );
        $json = getJsonData($pageNumber);
        
        /*If the page Number that was passed in is greater than the total page count then set it to the maximum page number*/
            if($pageNumber >$json['page_count']){
                $pageNumber = $json['page_count']-1;
            }    

            /*loop through the results portion of JSON data, find columns needed to be inserted*/
            foreach($json['results'] as $row => $value){

                $contents =     getColumnData($value,'contents');
                $name =         getColumnData($value,'name');
                $type =         getColumnData($value,'type');
                $pub_date =     getColumnData($value,'publication_date');
                $short_name =   getColumnData($value,'short_name');
                $model_type =   getColumnData($value,'model_type');
                $id =           getColumnData($value,'id');
                
                $loc_name =     loopThroughData($value['locations'],'name');
                $category =     loopThroughData($value['categories'],'name');

                $level_name =   loopThroughData($value['levels'],'name');
                $level_short_name =     loopThroughData($value['levels'], 'short_name');

                $ref =                  getColumnData($value['refs'],'landing_page');

                $company_id =           getColumnData($value['company'],'id');
                $company_short_name =   getColumnData($value['company'],'name');
                $company_name =         getColumnData($value['company'],'name');



                insertData(
                    $contents,
                    $name,
                    $type,
                    $pub_date,
                    $short_name,
                    $model_type,
                    $id,
                    $loc_name,
                    $category,
                    $level_name,
                    $level_short_name,          
                    $ref,
                    $company_id,
                    $company_short_name,
                    $company_name);
                
        }

        
    }

    function insertAllData(){
        $maxPageCount = getMaxPageCount();
        
        try {
            for($currentPage=0; $currentPage < $maxPageCount; $currentPage++){

                pullJobData($currentPage);

            }
            echo "insert successful";
        } catch (Exception $e) {
		  echo 'Caught exception: ',  $e->getMessage(), "\n";
	    }
        
    }


    /*
        Check if array is empty and whether an empty array was passed to the function
    */
    function isEmpty($value, $search){
        
        if(!(is_null($value))){
            $value = (array) $value;
            if(array_key_exists($search, $value)){
                return $value;
            }else {
                return ' ';
            }
        }else{
            return ' ';
        }
    }
       
    /*
        Check if values passed in is in fact empty.
        Return empty string if true.
    */
    function isItEmpty($value){
        
        if(!(is_null($value))){
            return $value;
        }else{
            return '';
        }
    }
          

    function getServerName(){
        $servername = "localhost";
        return $servername;
    }

    function getUsername(){
        $username="db_user";
        return $username;
    }

    function getPassword(){
        $password="password";
        return $password;
    }
    
    function getDbName(){
        $dbname="the_muse_challenge";
        return $dbname;
    }

    /*
        Prepares and executes the query to answer the question of how many jobs with 'New York City Metro Area' were posted
        betweeen September 1,2016 and September 30, 2016
        
    */
    function selectData(){
         
            
        $conn = new mysqli(getServerName(), getUsername(), getPassword(), getDbName());
        if($conn->connect_error){
            die("Connection failed:" . $conn->connect_error);
        }
        
        $sql = "SELECT COUNT(*)RECORD_COUNT 
                FROM `STAGING_JOBS` 
                WHERE PUBLICATION_DATE BETWEEN '2016-09-01 00:00:01' AND '2016-09-30 23:59:59' 
                AND  LOCATION_NAME = 'New York City Metro Area'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
//                echo $row["RECORD_COUNT"];
//                print_r($row);
                $recordCount = $row["RECORD_COUNT"];
                return $recordCount;
            }
        }
        
    }

    function deleteData(){
        $conn = new mysqli(getServerName(), getUsername(), getPassword(), getDbName());
        if($conn->connect_error){
            die("Connection failed:" . $conn->connect_error);
        }
        
        $sql = "DELETE FROM `STAGING_JOBS`";
        if($result = $conn->query($sql) === TRUE){
            
        }else{
            throw new Exception("Error: " . $sql . "<br/>" . $conn->error);
        };
        
    }
    

    /*
        Uses data passed to the function to insert nto the STAGING_JOBS table.
        
    */
    function insertData(
            $contents,
            $name,
            $type,
            $pub_date,
            $short_name,
            $model_type,
            $id,
            $loc_name,
            $category,
            $level_name,
            $level_short_name,          
            $ref,
            $company_id,
            $company_short_name,
            $company_name){            
            
        
        //PDO object  used in order to gain access to a function to  escape the user input $db->quote
//        $db = new PDO('mysql:host=127.0.0.1;port=3306;dbname=the_muse_challenge;','db_user','password'); 
        $db = new PDO("mysql:host=". getServerName() .";port=3306;dbname=". getDbName() .";",getUsername(),getPassword()); 

            
        /*Initialize variable used to connect to db*/
        $conn = new mysqli(getServerName(), getUsername(), getPassword(), getDbName());
        if($conn->connect_error){
            die("Connection failed:" . $conn->connect_error);
        }
        
        /*construct insert statement*/
        $sql = "INSERT INTO STAGING_JOBS
        (CONTENTS, NAME, TYPE, PUBLICATION_DATE, SHORT_NAME, MODEL_TYPE, ID, LOCATION_NAME, CATEGORY_NAME, LEVEL_NAME, LEVEL_SHORT_NAME, REFS_LANDING_PAGE, COMPANY_ID, COMPANY_SHORT_NAME, COMPANY_NAME)
        VALUES(
                                    ".
            $db->quote($contents)               . "," .
            $db->quote($name)                   . "," .
            $db->quote($type)                   . ",
                                    REPLACE(REPLACE('" .
            $pub_date               . "','T',' '),'Z',' ')," .
            $db->quote($short_name)             . "," .
            $db->quote($model_type)             . ",'" .
            $id                     . "'," .
            $db->quote($loc_name)               . "," .
            $db->quote($category)               . "," .
            $db->quote($level_name)             . "," .
            $db->quote($level_short_name)       . "," .          
            $db->quote($ref)                    . "," .
            $db->quote($company_id)             . "," .
            $db->quote($company_short_name)     . "," .
            $db->quote($company_name)           . ")";
        
    
        //increase the time it takes for php to send timeout error
        set_time_limit(300); 
        
        /*
            Attempt to insert data. 
            If there's an error print out the sql as well as the error message.
        */
        if ($conn->query($sql) === TRUE){
//            echo "Record inserted successfully";
        }else {
            echo "Error: " . $sql . "<br/>" . $conn->error;
        }
        
        $conn->close();
    }
    
    /*
        returns the literal value in the array.
        A check is also performed to identify whether the array has any data and return an empty string otherwise.
    */
    function getColumnData($arrayList, $searchKey){
        
        if(array_key_exists($searchKey, $arrayList)){

            $columnData = $arrayList [$searchKey];
            return isItEmpty($columnData);
        }else {
            return '';
        }
    }

    /*
        Function used to go through parts of the array that may contain another array.
        If that section is also empty then an empty string is returned.
    */      
    function loopThroughData($arrayData, $arrayKey){
        $returnData='';
        foreach($arrayData as $row => $value){
            $returnData = getColumnData($value,$arrayKey);
        }
        return $returnData;
    }

?>