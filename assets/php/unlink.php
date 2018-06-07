<?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        include ('../config/config.php');

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

        // check if log file exists in config.php:

    if(in_array($file, $logs)){ 

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
                
                echo "<script type='text/javascript'>";
                    echo "console.log('ERROR: Logarr was unable to copy log file:  $file');";
                echo "</script>";

            }

            else {

                    // copy log file success:

                echo "Copy log file: success: $newfile";
                    echo "<br>";

                echo "<script type='text/javascript'>";
                    echo "console.log('Copy log file: success: $newfile');";
                echo "</script>";

                sleep(2);

                    // delete orginal log file:
                    
                $delete = unlink($file);

                sleep(2);

                if($delete == true) {

                    echo "Delete original log file: success: $file\n";

                    echo "<script type='text/javascript'>";
                        echo "console.log('Delete original log file: success: $file');";
                    echo "</script>";

                        echo "<br>"; 

                    $newlogfile = $file;

                        // Write log entry in new log file:

                    $current = $today . " | Logarr created new log file: " . $newlogfile . "\n";
                    
                    $createfile = file_put_contents($newlogfile, $current);

                    if($createfile == true) {
                    
                        echo "Create new log file: success: " . $newlogfile; 

                            echo "<br>";

                        echo "<script type='text/javascript'>";
                            echo "console.log('Create new log file: success:  $newlogfile');";
                        echo "</script>";
                    }

                    else {
                    
                        echo "Create new log file: FAIL: " . $newlogfile;

                            echo "<br>";

                        echo "<script type='text/javascript'>";
                            echo "console.log('ERROR: Create new log file: FAIL:  $newlogfile');";
                        echo "</script>";
                    }
                }

                else {
                    echo "Delete original log file: FAIL: $file\n";

                        echo "<br>"; 

                    echo "<script type='text/javascript'>";
                        echo "console.log('ERROR: Delete original log file: FAIL: $file');";
                    echo "</script>";

                        //write log file entry if unlink of original log file fails:

                    $fh = fopen($file, 'a');
                    fwrite($fh, "$today | Logarr delete original log file: FAIL (ERROR):  $file\n");
                    fclose($fh);

                        //remove copied log file if unlink of original log file fails: 
                    
                    $deletefail = unlink($newfile);

                    if($deletefail == true) {

                        echo "Delete log file backup: Success: $newfile";

                        echo "<script type='text/javascript'>";
                            echo "console.log('Delete log file backup: Success: $newfile');";
                        echo "</script>";

                    }

                    else {

                        echo "Delete log file backup: FAIL: $newfile";

                        echo "<script type='text/javascript'>";
                            echo "console.log('ERROR: Delete log file backup: FAIL: $newfile');";
                        echo "</script>";
                    }
                }
            }
        }

        else {

            echo 'file: ' . $file . ' does not exist.';

            echo "<script type='text/javascript'>";
                echo "console.log('ERROR: file: '" . $file . "' does not exist.');";
            echo "</script>";
        
        }
    } 
    
        // Deny access if log file does NOT exist in config.php:

    else {
        echo 'ERROR:  Illegal File';

        echo "<script type='text/javascript'>";
            echo "console.log('ERROR:  Illegal File');";
        echo "</script>";
    }

?>