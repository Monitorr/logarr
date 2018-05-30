<?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

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

 if(is_file($file)){

        $fh = fopen($file, 'a');
        fwrite($fh, "$file File deleted and backed up");
        fclose($fh);

        if($fh == true) {
            echo "Write: success: $file\n"; 
                echo "<br>";
        }

        else {
            echo "Write: fail: $file\n"; 
                echo "<br>";
        };

        $newfile = "$file.bak";

        if (!copy($file, $newfile)) {
            echo "Copy: fail: $newfile";
        }

        else {
            echo "Copy: success: $newfile";
                echo "<br>";

            sleep(2);
                
            $delete = unlink($file);

            sleep(2);


            if($delete == true) {
                echo "Delete success: $file\n"; 
                    echo "<br>"; 

                $newlogfile = $file;

                $current = $today . " | Logarr created new log file: " . $newlogfile . "\n";
                
                $createfile = file_put_contents($newlogfile, $current);


                if($createfile == true) {
                
                    echo $today . " | Logarr created new log file: " . $newlogfile; 

                        echo "<br>"; 

                    echo " Put this in a modal?? // CHANGE ME";
                }

                else {
                
                    echo "Logarr was unable to create new log file: " . $newlogfile; 
                }

            }

            else {
                echo "Delete fail: $file\n;";
                    echo "<br>"; 
            }
        }
    }

    else {

        echo 'file: ' . $file . ' does not exist.';
    
    }
?>