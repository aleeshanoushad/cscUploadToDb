<?php require_once('dbConfig.php');

// Get status message
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Members data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        case 'limitexceed':
            $statusType = 'alert-danger';
            $statusMsg = 'CSV file with minimum 5 column and maximum 20 rows are only  allowed';
            break;
        case 'empty':
            $statusType = 'alert-danger';
            $statusMsg = 'Empty columns Not Allowed';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
  <style>
    .form__group {
  position: relative;
  padding: 15px 0 0;
  margin-top: 10px;
  width: 50%;
}

.form__field {
  font-family: inherit;
  width: 40%;
  border: 0;
  border-bottom: 2px solid gray;
  outline: 0;
  font-size: 1.3rem;
  color: gray;
  padding: 7px 0;
  background: transparent;
  transition: border-color 0.2s;

}

.form__label {
  position: absolute;
  top: 0;
  display: block;
  transition: 0.2s;
  font-size: 1rem;
  color: gray;
}

.form__field:focus {
    position: absolute;
    top: 0;
    left: 55px;
    display: block;
    /* transition: 0.2s; */
    font-size: 14px;
    color: gray;
    font-weight:700;    
  }
  .form__label {
  padding-bottom: 6px;  
  font-weight: 700;
  border-width: 3px;
  border-image: linear-gradient(to right, primary,secondary);
  border-image-slice: 1;
}
  </style>
  </head>
  <body>

    <div class="container" style="margin-top: 5%;">

      <div class="row">
        <!-- Display status message -->
            <?php if(!empty($statusMsg)){ ?>
            <div class="col-lg-12">
                <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
            </div>
            <?php } ?>

          <div class="col-lg-12">
                          <div class="panel panel-default">
                  <div class="panel-heading">
                      <p style="display: inline-block;padding-top: 7px;"><b>Employees List</b></p>
                      <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');" class="btn btn-success" style="float: right;">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Import
                      </a>
                  </div>
                  <div class="panel-body">
                      <form action="upload.php" method="post" enctype="multipart/form-data" id="importFrm" style="display: none;text-align: center;
                      margin-bottom: 10px;
                      padding: 10px;
                      border: 2px dashed #007bff;
                  ">
                  <div class="row" style="margin-bottom: 5%;">
                            <div class="col-md-6 form__group field">
                              <input type="input" class="form__field" placeholder="Column number for employee code" name="code" id='name' required />
                            
                            </div>
                            <div class="col-md-6 form__group field">
                              <input type="input" class="form__field" placeholder="Column number for employee name" name="name" id='name' required />
                              
                            </div>
                            <div class="col-md-6 form__group field">
                              <input type="input" class="form__field" placeholder="Column number for department" name="dep" id='name' required />
                              
                            </div>
                            <div class="col-md-6 form__group field">
                              <input type="input" class="form__field" placeholder="Column number for dob" name="age" id='age' required />
                              
                            </div>
                            <div class="col-md-6 form__group field">
                              <input type="input" class="form__field" placeholder="Column number for joining date" name="exp" id='name' required />
                              
                            </div>
                </div>
                          <input type="file" name="file" style="
                          display: inline-block;">
                          <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
                      </form>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Employee code</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Age</th>
                            <th>Experinece in Organisation</th>
                          </tr>
                        </thead>
                        <tbody>
                    <?php 
                    
                    $stmt = $pdo->prepare("SELECT * FROM employees ORDER BY id");
                    $stmt->execute();
                    while($rows = $stmt->fetch()){   ?>
                          
                          <tr>
                            <td><?= $rows['employee_code']?></td>
                            <td><?= $rows['employee_name']?></td>
                            <td><?= $rows['department']?></td>
                            <td><?= $rows['age']?></td>
                            <td><?= $rows['experience']?></td>
                          </tr>
                          <tr>
                            
                    
                    <?php } ?>
                        </tbody>
                      </table>
                      </div>
                  </div>
             </div>
          </div>
      </div>
      <script type="text/javascript">


        function formToggle(ID){
            var element = document.getElementById(ID);
            if(element.style.display === "none"){
                element.style.display = "block";
            }else{
                element.style.display = "none";
            }
        }
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
                                                        
  </body>
</html>