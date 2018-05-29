<?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        //$file=$_POST['file'];

        print_r('Form submitted:  unlink file: ');
        var_dump($_POST['file']);
            echo "<br>";
        print_r('Server received: unlink file:  ');
        var_dump($_POST['file']);
            echo "<br>";
        print_r('Server attempting to unlink:  ');
        var_dump($_POST['file']);


$file = ($_POST['file']);

    //echo "file: " ." $file\n";
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
                echo "Bug: You must reload this page before attempting to unlink another log. // CHANGE ME //"; 
            }

            else {
                echo "Delete fail: $file\n;";
                    echo "<br>"; 
                echo "Bug: You must reload this page before attempting to unlink another log.// CHANGE ME // "; 
            }
        }
    }

    else {

        echo 'file: ' . $file . ' does not exist.';
    
    }
?>