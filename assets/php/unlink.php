<?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        include('functions.php');

        print_r('Form submitted:  unlink file: ');
        var_dump($_POST['file']);
            echo "<br>";
        print_r('Server received: unlink file:  ');
        var_dump($_POST['file']);
            echo "<br>";
        print_r('Server attempting to unlink:  ');
        var_dump($_POST['file']);



$file = ($_POST['file']);

    $today = date("D d M Y | H:i:s");

    echo "<br><br>";

        // check if log file exists in config.json:

    if(in_array_recursive($file, $logs)){

        // check if log file exists:

        if(is_file($file)){

                // copy log file:

            $newfile = "$file.bak";

            if (!copy($file, $newfile)) {

                    // copy log file failed:

                echo "Copy log file: FAIL: $newfile";

                $fh = fopen($file, 'a');
                fwrite($fh, "$today | ERROR: Logarr was unable to copy log file:  $file\n");
                
                fclose($fh);

            }

            else {

                    // copy log file success:

                echo "Copy log file: success: $newfile";
                    echo "<br>";

                sleep(2);

                    // delete orginal log file:
                    
                $delete = unlink($file);

                sleep(2);


                if($delete == true) {

                    echo "Delete original log file: success: $file\n"; 

                        echo "<br>"; 

                    $newlogfile = $file;

                        // Write log entry in new log file:

                    $current = $today . " | Logarr created new log file: " . $newlogfile . "\n";
                    
                    $createfile = file_put_contents($newlogfile, $current);

                    if($createfile == true) {
                    
                        echo "Create new log file: success: " . $newlogfile; 

                            echo "<br>"; 

                    }

                    else {
                    
                        echo "Create new log file: FAIL: " . $newlogfile;

                            echo "<br>"; 
                    }

                }

                else {
                    echo "Delete original log file: FAIL: $file\n";

                        echo "<br>"; 

                        //write log file entry if unlink of original log file fails:

                    $fh = fopen($file, 'a');
                    fwrite($fh, "$today | Logarr delete original log file: FAIL (ERROR):  $file\n");
                    fclose($fh);

                        //remove copied log file if unlink of original log file fails: 
                    
                    $deletefail = unlink($newfile);

                    if($deletefail == true) {

                        echo "Delete log file backup: Success: $newfile";

                    }

                    else {

                        echo "Delete log file backup: FAIL: $newfile";
                    }
                        
                }
            }
        }

        else {

            echo 'file: ' . $file . ' does not exist.';
        
        }

    } 
    
        // Deny access if log file does NOT exist in config.json:

    else {
        echo 'ERROR:  Illegal File';
    }


?>