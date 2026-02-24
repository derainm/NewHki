<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="hkiIcon.svg">
    <title>Aoe2 New HKI</title>
    <!--
        <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">  
   
    <link rel="stylesheet" href="index.css">  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"> 

    <link rel="stylesheet" href="newhki.css"> 
    <script src="newhki.js" defer></script>
</head>
<body>


<div class="container Page">
      <form action = "" method = "POST" enctype = "multipart/form-data"  id="sub">
         Edite a hotkey file
         <input type = "file" name = "hki" id="hki" accept=".hki"   class="form-control"  /> <br>
         <input type = "submit" class="btn btn-primary btn-lg"  />   
      </form>
   </div> 
   



        <form action="NewHkiDownload.php" method="POST" enctype="multipart/form-data">

            <?php
            $hex_data = ""; 
            if(isset($_FILES['hki']) && $_FILES['hki']['error'] === UPLOAD_ERR_OK) {
                // Read the file and convert to hex so it can stay in an HTML input safely
                $file_content = file_get_contents($_FILES['hki']['tmp_name']);
                $hex_data = bin2hex($file_content); // Temporary storage
                
                // ... your code to parse the file and show the <input> rows ...
            }
            if(isset($_GET['hki']) && !isset($_FILES['hki'])  )
            { 
                //echo $_GET['hki'];
                $file_content = file_get_contents($_GET['hki']);
                $hex_data = bin2hex($file_content); // Temporary storage 
                //echo $hex_data;
            }


            ?>
            <input type="hidden" name="file_hex" value="<?php echo $hex_data; ?>">

            <!--class="submit-section" -->                        
            <div class="submit-section container" >
                    <button type="submit" class="btn-save">Download Edite .hki File 
                    </button>

            </div>
                

    <?php  


/**
 * Reads the entire INI file into a fast associative array
 */
function loadLanguageFile($filename) {
    if (!file_exists($filename)) return [];

    $content = file_get_contents($filename);
    
    // Convert encoding once
    $encoding = mb_detect_encoding($content, ['UTF-16', 'UTF-8', 'ISO-8859-1']);
    if ($encoding !== 'UTF-8') {
        $content = mb_convert_encoding($content, 'UTF-8', $encoding);
    }

    $lines = preg_split('/\r\n|\r|\n/', $content);
    $lookup = [];

    foreach ($lines as $line) {
        $line = trim(preg_replace('/^\xEF\xBB\xBF/', '', $line));
        if (strpos($line, '=') !== false) {
            list($id, $text) = explode('=', $line, 2);
            $cleanId = trim($id);
            $cleanText = str_replace('\n', PHP_EOL, trim($text));
            $lookup[$cleanId] = $cleanText;
        }
    }
    return $lookup;
}

 

function convertVkHexToName__V2($hex) {
    
 
    $key = $hex['key']??'';
//echo $key.'<br>';


    $cleanHex = substr($key , 0, 2);
    
    // 2. Convert Hex to Decimal
    $decimal = hexdec($cleanHex);

    $subKey = '';


    if(($hex['ctrl']??'') == '01')
    {
        $subKey .= "CTRL + "; 
    }
    if(($hex['alt']??'') == '01')
    {
        $subKey .= "ALT + "; 
    }
    if(($hex['shift']??'') == '01')
    {
        $subKey .= "SHIFT + "; 
    }
    //todo mouse


    /*
    // Mapping Modifier Keys (V1)
    // Common bitmask values for modifiers
    if ($V3 == '01000000') {
        $subKey = "CTRL + ";
    } 
    elseif ($V3 == '00000100') {
        $subKey = "SHIFT + ";
    } 

    elseif ($V3 == '0400') {
        $subKey = "WIN + ";
    } elseif ($V3 == '0101') {
        $subKey = "CTRL + ALT + ";
    }
    if($V3 == '08000000')
    {
         $subKey = 'CTRL + ';
    }
    if ($V3 == '00010000') {//00000010000
        $subKey = "ALT + ";
    }  
    if($V1 == '00000100')
    {
         $subKey = 'SHIFT + '; 
    }

    //if($hex == 'dc000000')
*/

    // 3. Define Special Keys
$specialKeys = [


    0xFB =>  'Extra Button 2',
    0xFC =>  'Extra Button 1',
    0xFD =>  'Middle Button',

    0xFF => 'Mouse Wheel Up',
    0xFE => 'Mouse Wheel Down',

    // System Keys
    0x08 => 'Backspace',
    0x09 => 'Tab',
    0x0D => 'Enter',
    0x10 => 'Shift',
    0x11 => 'Ctrl',
    0x12 => 'Alt',
    0x13 => 'Pause',
    0x14 => 'Caps Lock',
    0x1B => 'Esc',
    0x20 => 'Space',
    0x21 => 'Page Up',
    0x22 => 'Page Down',
    0x23 => 'End',
    0x24 => 'Home',
    0x2E => 'Delete',
    0x2C => 'Print Screen',
    0x2D => 'Insert',
    
    // Navigation
    0x25 => 'Left',
    0x26 => 'Up',
    0x27 => 'Right',
    0x28 => 'Down',

    // Numpad
    0x60 => 'Num 0',
    0x61 => 'Num 1',
    0x62 => 'Num 2',
    0x63 => 'Num 3',
    0x64 => 'Num 4',
    0x65 => 'Num 5',
    0x66 => 'Num 6',
    0x67 => 'Num 7',
    0x68 => 'Num 8',
    0x69 => 'Num 9',
    0x6A => '*',
    0x6B => 'Num +',
    0x6D => 'Num -',
    0x6E => '.',
    0x6F => '/',

    // Function Keys
    0x70 => 'F1',
    0x71 => 'F2',
    0x72 => 'F3',
    0x73 => 'F4',
    0x74 => 'F5',
    0x75 => 'F6',
    0x76 => 'F7',
    0x77 => 'F8',
    0x78 => 'F9',
    0x79 => 'F10',
    0x7A => 'F11',
    0x7B => 'F12',

    // OEM Symbols (Standard US Layout)
    
    0xBA => ';',
    0xBB => '=', // Often treated as '+' with shift
    0xBC => ',',
    0xBD => '-',
    0xBE => '.',
    0xBF => '/',
    0xC0 => '`',
    0xDB => ')',
    //0xDC => '\\',

    0xDC => '*',
    0xDD => '(',
    0xDE => "'",
    
    // Meta / Windows Keys
    0x5B => 'Left Windows',
    0x5C => 'Right Windows',
    0x5D => 'Apps/Menu',

    0x36=> '^',
/*
    0x05=> 'Extra Button 1',
    0x05=> 'Extra Button 2',
    */
];
 
    // 4. Return special name or Character
    if (isset($specialKeys[$decimal])) {
        return $subKey . $specialKeys[$decimal];
    }

    // Returns A-Z, 0-9, etc.
    return $subKey . chr($decimal);
}
 

/*
info from
https://github.com/genie-js/genie-hki/blob/default/index.js
when we unflate the .hki we saw vk hex code + this information

                          ['key', t.int32],//-4
                          ['stringId', t.int32],//0
                          ['ctrl', t.bool],//1
                          ['alt', t.bool],//2
                          ['shift', t.bool],//3
                          ['mouse', t.int8]//4
*/
function getDataAtOffsets__V2($data, $stringId,$next=false) { 
        $target = pack('v', $stringId); 
        $pos = strpos($data, $target);

        if ($pos === false || $pos < 8) {
            return null; // Not found or insufficient preceding data
        }

        if($next)
        { 
            $pos += 12;
        }
 
        return [
            'target_pos' => $pos,
            'pos_key'   => $pos - 4,
            'key' => bin2hex(substr($data, $pos - 4, 2)),  //'key'
            'pos_ctrl'   => $pos+4,
            'ctrl' => bin2hex(substr($data,  $pos+4, 1)), 
            'pos_alt'   => $pos+5,
            'alt' => bin2hex(substr($data, $pos+5, 1)), 
            'pos_shift'   => $pos+6,
            'shift' => bin2hex(substr($data, $pos+6, 1)), 
            'pos_mouse' => $pos+7,
            'mouse' =>  bin2hex(substr($data, $pos+7, 2)), 
        ];
    }

 


    $xmlString = file_get_contents('Aoe2PatchHotkey.xml');

    if(isset($_GET['hkiVersions']) && $_GET['hkiVersions'] == 'v16RC')
    {
        $xmlString = file_get_contents('hotkey.xml');
    }

    // Remove BOM if it exists
    $xmlString = str_replace("\xEF\xBB\xBF", '', $xmlString); 

    $xml = simplexml_load_string(trim($xmlString));


        $decompressed_data = '';
        if(isset($_FILES['hki']))
        {
            if ($_FILES['hki']['error'] === UPLOAD_ERR_OK) 
            { 
                $filename = $_FILES['hki']['tmp_name'];//name 
                //echo "File  : " . $filename . "<br>";
                 // 1. Read the entire compressed file content into a string
                $source_data_string = file_get_contents($filename);
                if ($source_data_string === false) {
                    die("Error: Could not read file '$filename'.");
                }
                // 2. Decompress the string using gzinflate()
                // gzinflate() only accepts the string data (and an optional max_length argument)
                $decompressed_data = gzinflate($source_data_string);
            }
  


      }
      if(isset($_GET['hki'])  && !isset($_FILES['hki']))
      {
        
                $filename = $_GET['hki'];//name 
                //echo "File  : " . $filename . "<br>";
                 // 1. Read the entire compressed file content into a string
                $source_data_string = file_get_contents($filename);
                if ($source_data_string === false) {
                    die("Error: Could not read file '$filename'.");
                }
                // 2. Decompress the string using gzinflate()
                // gzinflate() only accepts the string data (and an optional max_length argument)
                $decompressed_data = gzinflate($source_data_string);

      }


$langLookup = loadLanguageFile('lang.ini');

echo '<div class="master-grid">';
// Wrap all cards in one single flex-wrap container
echo '<div class="group-row">'; 

foreach ($xml->Hotkey_Groups as $group) {
    echo '<div class="group-card">';
    echo '<div class="group-header">'.  $langLookup[(string)$group['langIdText']] . ' </div>'; //getDataFromFile('lang.ini',$group['langIdText'])


        $hotkeys = [];
        foreach ($group->Hotkey as $hotkey) {
            $hotkeys[] = $hotkey;
        }

        usort($hotkeys, function($a, $b) use ($langLookup) {
            $idA = (string)$a['langIdText'];
            $idB = (string)$b['langIdText'];

            // Get translation from memory, or fallback to the ID if missing
            $textA = $langLookup[$idA] ?? $idA;
            $textB = $langLookup[$idB] ?? $idB;

            return strnatcasecmp($textA, $textB); // Natural sort (handles numbers better)
        });

    foreach ( $hotkeys as $hotkey) 
    {   
        $result = []; 
        $langId =  $hotkey['langIdText']; 
        $id = $hotkey['id']; 
        $isNext= false;
        $NextLib = '' ;
        if(true || isset($_FILES['hki']))
        {

            $isNext = 
                  ($langId  == '19026' && $id == '29')
                ||($langId  == '19023' && $id == '24')
                ||($langId  == '19068' && $id == '47')
                ||($langId  == '19024' && $id == '26') ;


            $NextLib = '' . ($isNext? ' 2':'');

            $result =getDataAtOffsets__V2($decompressed_data,$langId,$isNext); 
            $val = convertVkHexToName__V2($result);  
            $target_pos = $result['target_pos']?? null;

            $keyVal = '';
        
            $text = $langLookup[(string)$langId] ?? (string)$hotkey['Name'];

//debug
/*
            if($langId  == '19298')
            {
                echo "<pre>";
                print_r($result); // Check if file is here 
                echo "</pre>";
            }
*/
            $key = $result['key']??'00000000';
            $ctrl = $result['ctrl']??'00';
            $alt = $result['alt']??'00';
            $shift = $result['shift']??'00';
            $keyVal =  $key . '-' .  $ctrl . '-' . $alt  . '-' .  $shift;

            echo '<div class="input-row">
                    <span class="key-label">'.$text. $NextLib .'</span>
                    <input type="text" class="shortcut-input" name="'. $langId .'-' . $target_pos . '-'  .  $keyVal . '" id="'. $langId  . $NextLib .'" placeholder="Empty" readonly value="'.  $val  .'" >
                  </div>';  

        }
    }
    echo '</div>'; // close group-card
}

echo '</div>'; // close group-row
echo '</div>'; // close master-grid
 

    ?>

     </form>  <!--   ///////////////////   -->
 
<div>
    <button id="kb-toggle-btn" class="toggle-btn">+</button>
    <span style="font-size: 0.8rem; color: #8b949e;"> Keyboard</span>
</div>

<div class="keyboard-case" id="kb-container">
    <div class="keyboard-plate" id="kb-grid"></div>
</div>


</body>
</html>