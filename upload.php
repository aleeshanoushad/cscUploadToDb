<?php
require_once('dbConfig.php'); 

if(isset($_POST['importSubmit'])){


        $col_code = ($_POST['code']) ? $_POST['code'] : 1;
        $col_name = ($_POST['name']) ? $_POST['name'] : 2;
        $col_dep = ($_POST['dep']) ? $_POST['dep'] : 3;
        $col_age = ($_POST['age']) ? $_POST['age'] : 4;
        $col_exp = ($_POST['exp']) ? $_POST['exp'] : 5;
   
    
        // Allowed mime types
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        
        // Validate whether selected file is a CSV file
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
            
            // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
                
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            // fgetcsv($csvFile);

            $linecount = count(file($_FILES['file']['tmp_name']));
            
            $line = fgetcsv($csvFile);
            if(count($line) < 5 || $linecount > 20){
                $qstring = '?status=limitexceed';

            }else{
                // Parse data from CSV file line by line
                while(($line = fgetcsv($csvFile)) !== FALSE){

                        // Get row data
                    
                        $employee_code  = $line[$col_code - 1];
                        $employee_name   = $line[$col_name - 1];
                        $department  = $line[$col_dep - 1];
                        $dob = $line[$col_age - 1];
                        $join_date = $line[$col_exp - 1];

                        if(!empty($employee_code) && !empty($employee_name) && !empty($department) && !empty($dob) && !empty($join_date))
                        {
                            $today = date("Y-m-d");
                            $diff = date_diff(date_create($dob), date_create($today));
                            $age = $diff->format('%y');
                            $diffExperience = date_diff(date_create($join_date), date_create($today));
                            
                            $experience = $diffExperience->format('%y years %m months');
                            
                            // Check whether member already exists in the database with the same email
                            $prevQuery = "SELECT count(employee_code) as cnt FROM employees WHERE employee_code = '".$employee_code."'";
                            $stmt1 = $pdo->prepare($prevQuery);
                            $stmt1->execute();
                            $count = $stmt1->fetch();
                            
                            if($count['cnt']  > 0){
                                // Update member data in the database
                                $data4 =[
                                    ':employee_code' => $employee_code,
                                    ':employee_name' =>$employee_name,
                                    ':age' =>$age,
                                    ':department' =>$department,
                                    ':experienece' =>$experience
                                ];
                                
                                $sql4 = "UPDATE employees SET employee_name=:employee_name,age=:age,department=:department,experience=:experienece WHERE employee_code=:employee_code";
                                $stmt4= $pdo->prepare($sql4);
                                $result4 = $stmt4->execute($data4);

                            }else{
                                // Insert member data in the database
                                
                                $data =[
                                    ':employee_code' => $employee_code,
                                    ':employee_name' =>$employee_name,
                                    ':age' =>$age,
                                    ':department' =>$department,
                                    ':experienece' =>$experience
                                ];
                                $stmt_insrt= $pdo->prepare("INSERT INTO employees (employee_code,employee_name,age,department,experience) VALUES (:employee_code,:employee_name,:age,:department,:experienece)");
                                $result =$stmt_insrt->execute($data); 
                            }
                        }else{
                            $qstring = '?status=empty';
                        }
                }
            
            
                // Close opened CSV file
                fclose($csvFile);
                
                $qstring = '?status=succ';
            }
            }else{
                $qstring = '?status=err';
            }
        }else{
            $qstring = '?status=invalid_file';
        }
    }
    
    // Redirect to the listing page
    header("Location: test.php".$qstring);
?>