<?php 
include_once('excelUpload.php');
include_once('./crypt/decrypt.php');
include_once('./crypt/encrypt.php');
$sw = new ExcelUpload();
$Decrypt = new Decrypt();
$Encrypt = new Encrypt();
$id = $Encrypt -> aes_encrypt($_GET['eno']);
$result = $sw -> select_info($id);

if(isset($result)){
    $data['eno'] = $Decrypt -> aes_decrypt($result['eno']);
    $data['team'] = $Decrypt -> aes_decrypt($result['team']);
    $data['name'] = $Decrypt -> aes_decrypt($result['name']);
} else {
    $data['eno'] = null;
    $data['team'] = null;
    $data['name'] = null;
}
echo json_encode($data);

?>