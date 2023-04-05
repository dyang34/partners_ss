<?php
$file_name = $_REQUEST['file_name'];
$file_real_name = $_REQUEST['file_real_name'];
$type = $_REQUEST['type'];

if(empty($file_real_name)) {
    $file_real_name = $file_name;
}

$file_real_name = iconv('UTF-8','CP949',$file_real_name);
//$file_real_name = basename($file_real_name);

if (file_exists($file_name)) {
    if ($type=="pdf") {
        header('Content-Type: application/pdf');
    } else {
        header('Content-Description: File Transfer');
    }
    header('Content-Disposition: attachment; filename="'.$file_real_name.'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length:'.filesize($file_name));
    readfile($file_name);
    exit;
} else {
    echo "File not found.";
}
?>