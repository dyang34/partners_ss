<? 
header("Content-Type:text/html; charset=UTF-8");
$save_file = $_REQUEST['save_file'];
$filename = $_REQUEST['file_name'];

if ($save_file=="") {
?>
<script>
alert('해당파일이 없습니다.1');
history.go(-1);
</script>
<?	
    exit;
}

$server_filename = "/home/toursafe/public_html/board/free/photo/".$save_file;

if (!file_exists($server_filename) || !is_readable($server_filename)) {
?>
<script>
	alert('없는파일입니다.2');
	history.go(-1);
</script>
<?php
    exit;
}

if (($filesize = filesize($server_filename)) == 0) {
?>
<script>
	alert('없는파일입니다.3');
	history.go(-1);
</script>
<?php
    exit;
}

if (($fp = @fopen($server_filename, 'rb')) === false) {
?>
<script>
	alert('없는파일입니다.4');
	history.go(-1);
</script>
<?php
    exit;
}
	
 // 파일명에 사용할 수 없는 문자를 모두 제거하거나 안전한 문자로 치환한다.
$illegal = array('\\', '/', '<', '>', '{', '}', ':', ';', '|', '"', '~', '`', '@', '#', '$', '%', '^', '&', '*', '?');
$replace = array('', '', '(', ')', '(', ')', '_', ',', '_', '', '_', '\'', '_', '_', '_', '_', '_', '_', '', '');
$filename = str_replace($illegal, $replace, $filename);
$filename = preg_replace('/([\\x00-\\x1f\\x7f\\xff]+)/', '', $filename);

// 유니코드가 허용하는 다양한 공백 문자들을 모두 일반 공백 문자(0x20)로 치환한다.
$filename = trim(preg_replace('/[\\pZ\\pC]+/u', ' ', $filename));

// 위에서 치환하다가 앞뒤에 점이 남거나 대체 문자가 중복된 경우를 정리한다.
$filename = trim($filename, ' .-_');
$filename = preg_replace('/__+/', '_', $filename);
if ($filename == '') {
?>
<script>
	alert('없는파일입니다.5');
	history.go(-1);
</script>
<?php
    exit;
}

// 브라우저의 User-Agent 값을 받아온다.
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$old_ie = (bool)preg_match('#MSIE [3-8]\.#', $ua);

// 파일명에 숫자와 영문 등만 포함된 경우 브라우저와 무관하게 그냥 헤더에 넣는다.
if (preg_match('/^[a-zA-Z0-9_.-]+$/', $filename)) {
    $header = 'filename="' . $filename . '"';
}

// IE 9 미만 또는 Firefox 5 미만의 경우.
elseif ($old_ie || preg_match('#Firefox/(\d+)\.#', $ua, $matches) && $matches[1] < 5) {
    $header = 'filename="' . rawurlencode($filename) . '"';
}

// Chrome 11 미만의 경우.
elseif (preg_match('#Chrome/(\d+)\.#', $ua, $matches) && $matches[1] < 11) {
    $header = 'filename=' . $filename;
}
// Safari 6 미만의 경우.
elseif (preg_match('#Safari/(\d+)\.#', $ua, $matches) && $matches[1] < 6) {
    $header = 'filename=' . $filename;
}
// 안드로이드 브라우저의 경우. (버전에 따라 여전히 한글은 깨질 수 있다. IE보다 못한 녀석!)
elseif (preg_match('#Android #', $ua, $matches)) {
    $header = 'filename="' . $filename . '"';
}
// 그 밖의 브라우저들은 RFC2231/5987 표준을 준수하는 것으로 가정한다.
// 단, 만약에 대비하여 Firefox 구 버전 형태의 filename 정보를 한 번 더 넣어준다.
else {
    $header = "filename*=UTF-8''" . rawurlencode($filename) . '; filename="' . rawurlencode($filename) . '"';
}

// 캐싱이 금지된 경우...
if (!$expires) {
    // 익스플로러 8 이하 버전은 SSL 사용시 no-cache 및 pragma 헤더를 알아듣지 못한다.
    // 그냥 알아듣지 못할 뿐 아니라 완전 황당하게 오작동하는 경우도 있으므로
    // 캐싱 금지를 원할 경우 아래와 같은 헤더를 사용해야 한다.
    if ($old_ie) {
        header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0');
        header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
    }
    // 그 밖의 브라우저들은 말을 잘 듣는 착한 어린이!
    else {
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
    }
}

// 캐싱이 허용된 경우...
else {
    header('Cache-Control: max-age=' . (int)$expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (int)$expires) . ' GMT');
}

// 이어받기를 요청한 경우 여기서 처리해 준다.
if (isset($_SERVER['HTTP_RANGE']) && preg_match('/^bytes=(\d+)-/', $_SERVER['HTTP_RANGE'], $matches)) {
    $range_start = $matches[1];
    if ($range_start < 0 || $range_start > $filesize) {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        return false;
    }
    header('HTTP/1.1 206 Partial Content');
    header('Content-Range: bytes ' . $range_start . '-' . ($filesize - 1) . '/' . $filesize);
    header('Content-Length: ' . ($filesize - $range_start));
} else {
    $range_start = 0;
    header('Content-Length: ' . $filesize);
}

// 나머지 모든 헤더를 전송한다.
header('Accept-Ranges: bytes');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; ' . $header);

// 출력 버퍼를 비운다.
// 파일 앞뒤에 불필요한 내용이 붙는 것을 막고, 메모리 사용량을 줄이는 효과가 있다.
while (ob_get_level()) {
    ob_end_clean();
}

// 파일을 64KB마다 끊어서 전송하고 출력 버퍼를 비운다.
// readfile() 함수 사용시 메모리 누수가 발생하는 경우가 가끔 있다.
$block_size = 16 * 1024;
$speed_sleep = $speed_limit > 0 ? round(($block_size / $speed_limit / 1024) * 1000000) : 0;

$buffer = '';
if ($range_start > 0) {
    fseek($fp, $range_start);
    $alignment = (ceil($range_start / $block_size) * $block_size) - $range_start;
    if ($alignment > 0) {
        $buffer = fread($fp, $alignment);
        echo $buffer; unset($buffer); flush();
    }
}
while (!feof($fp)) {
    $buffer = fread($fp, $block_size);
    echo $buffer; unset($buffer); flush();
    usleep($speed_sleep);
}
fclose($fp);
// 전송에 성공했으면 true를 반환한다.
return true;
?>