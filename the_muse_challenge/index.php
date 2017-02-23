
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
    <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.1.1.min.js"><\/script>')</script>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>

    <body>

    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">Assessment</a>

      
    </nav>

    <div class="container">
        

      <div class="starter-template">
          <br/>
          <br/>
          <br/>
        <h1>Data Engineer Assignment</h1>
        
          <form name="insertData" action="index.php" method="POST">
              <div class="form-group">
                <label for="inptPageNumber">Please enter the number of pages you'd Like to retrieve</label>
                  <input type="number" id="inptPageNumber" name="inptPageNumber" class="form-control" required>
              </div>
              <button type="submit" name="btnInsertData" id="btnInsertData"  class="btn btn-primary">Get Data</button>
                  
          </form>
          <br/>
          <br/>
          <form name="deleteData" action="index.php" method="POST">
              <div class="form-group">
                <button type="submit" name="btnDeleteData" id="btnDeletetData"  class="btn btn-danger">Delete Data</button>
              </div>
          </form>
          <br/>
          <br/>
          <form name="tData" action="index.php" method="POST">
              <button type="submit" name="btnSelectData" id="btnSelectData" class="btn btn-primary" onclick="selectData()">Answer query</button>
              
          
          </form>
          
<?php
    include 'functions.php';
    
    /*if Delete Data button was pressed*/      
    if((isset($_POST['btnDeleteData']) )){
        
        try{
            
            deleteData();
            echo "Delete Successful";
        } catch (Exception $e) {
		  echo 'Caught exception: ',  $e->getMessage(), "\n";
	    }
        
        
        
    }
    
    /*if Answer Query button was pressed*/      
    if((isset($_POST['btnSelectData']) )){
        
        $job_count = selectData();
        echo "How many jobs with the location \"New York City Metro Area\" were published from September 1st to 30th 2016? That would be: " . $job_count;
        
    }
    
    /*if Get Data button was pressed*/      
    if (isset($_POST['inptPageNumber']) && is_numeric($_POST['inptPageNumber']) && isset($_POST['btnInsertData']) ){
        
        /*get the desired number of pages*/
        $pageNumber = $_POST['inptPageNumber'];
        
        /*find the highest page number*/
        $maxPages = getMaxPageCount();
        
        if($pageNumber > $maxPages){
            $pageNumber = $maxPages;
        }
        
        /*loop through the pages start from the first page all the way to the max page number*/
        try {
            for($currentPage=0; $currentPage < $pageNumber; $currentPage++){

                pullJobData($currentPage);

            }
            echo "insert successful";
        } catch (Exception $e) {
		  echo 'Caught exception: ',  $e->getMessage(), "\n";
	    }
        
    }
    
          
?>

        </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="bootstrap-4.0.0-alpha.6/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap-4.0.0-alpha.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-4.0.0-alpha.6/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="muse_problems.js"></script>
  </body>
</html>
