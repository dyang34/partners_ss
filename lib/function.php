<?php
function encode_pass($str,$s_key) {
    $s_vector_iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB), MCRYPT_RAND);
    
    ### 암호화 ####
    $en_str = mcrypt_encrypt(MCRYPT_3DES, $s_key, $str, MCRYPT_MODE_ECB, $s_vector_iv);
    //암호화된 값은 binary 데이터이므로, ascii로 처리하기 위해서는 별도의 변환이 필요하다
    $en_base64 = base64_encode($en_str);  //base64 encoding을 한 경우 => SVzBe9MN9Htf7zEtp+Rn3g==
    $en_hex = bin2hex($en_str);  //hex로 변환한 경우 => 495cc17bd30df47b5fef312da7e467de
    
    return $en_hex;
}

function decode_pass($str,$s_key) {
    $s_vector_iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB), MCRYPT_RAND);
    
    $de_str = pack("H*", $str); //hex로 변환한 ascii를 binary로 변환
    $out_str = mcrypt_decrypt(MCRYPT_3DES, $s_key, $de_str, MCRYPT_MODE_ECB, $s_vector_iv);
    
    return $out_str;
}

function get_default_member_no($company_type) {
    switch($company_type) {
        case "1":
            $rtn_member_no = 25;
            break;
        default:
            $rtn_member_no = ((int)$company_type)*10000;
            break;
    }

    return $rtn_member_no;
}
?>