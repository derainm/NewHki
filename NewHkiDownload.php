<?php
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_FILES); // Check if file is here
    print_r($_POST);  // Check if inputs are here
    echo "</pre>";
    exit;
}
*/
if (isset($_POST['file_hex']) && !empty($_POST['file_hex'])) {
    
    // 1. Recover the file data from the hidden input
    $source_data_string = hex2bin($_POST['file_hex']);
    $decompressed_data = gzinflate($source_data_string);

    if ($decompressed_data === false) {
        die("Error: Decompression failed.");
    }

    // 2. Apply modifications from the inputs
    foreach ($_POST as $key => $value) {
        if (str_contains($key, '-') )
        {

            $parts = explode('-', $key);
            //echo $key . '<br>';
            // Assign and cast to int
            $langId = isset($parts[0]) ? intval($parts[0]) : 0;
            $pos = isset($parts[1]) ? intval($parts[1]) : 0; 
            $key  = $parts[2] ;      //$key
            $ctrl = $parts[3] ;      //$ctrl
            $alt  = $parts[4] ;      //$alt 
            $shift = $parts[5] ;      //$shift
 
            
            $target_offset = $pos - 4;

            if ($target_offset >= 0)// && $target_offset < strlen($decompressed_data)) 
            {
                if( true ||$key!= '00000000')
                { 
                    /*
                          ['key', t.int32],//-4
                          ['stringId', t.int32],//0
                          ['ctrl', t.bool],//1
                          ['alt', t.bool],//2
                          ['shift', t.bool],//3
                          ['mouse', t.int8]//4
                    */
                    $target_offset = $pos - 4;
                    $hex_byte =  substr($key, 0, 2);
                    $decompressed_data[$target_offset] = chr(hexdec($hex_byte));


                    $decompressed_data[$pos + 4] = chr(hexdec($ctrl));
                    $decompressed_data[$pos + 5] = chr(hexdec($alt));
                    $decompressed_data[$pos + 6] = chr(hexdec($shift));
                    //$decompressed_data[$pos + 7] = todo mouse;

           /*
                       
                  if($langId  == 19301)
                    {

               
                        
                        echo '$key: ' .$key .'<br>';  
                        exit;
                    }
   */

 
                }
            }
 

        }
    }
 
 
    // 3. Re-compress
    $recompressed_data = gzdeflate($decompressed_data);//, 9

    // 4. Download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="player0.hki"');
    echo $recompressed_data;
    exit;
} else {
    echo "No file data found. Please upload the file again.";
}

?>