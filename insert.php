<?php 
include_once('./excelUpload.php');
include_once('./crypt/encrypt.php');
include_once('./crypt/decrypt.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
$sw = new ExcelUpload();
$Encrypt = new Encrypt();
$Decrypt = new Decrypt();
$txt = "";
$list = $_POST['list'];
$result['update'] = 0;
$result['insert'] = 0;
$result['failed'] = 0 ; 
$result['total'] = 0;  

for($i =0; $i < count($list); $i++){ 
    // 있는지 조회
    // 없으면 삽입 / 있으면 업데이트  

    // 암호화가 필요한 경우
    // $encrypted = [];
    // $encrypted['eno'] = $Encrypt -> aes_encrypt($list[$i]['사번']);
    // $encrypted['team'] = $Encrypt -> aes_encrypt($list[$i]['팀']);
    // $encrypted['name'] = $Encrypt -> aes_encrypt($list[$i]['이름']);
 
    // 암호화 예시
    // $list[$i]['title'] = $Encrypt -> aes_encrypt($list[$i]['title']);
    // $list[$i]['description'] = $Encrypt -> aes_encrypt($list[$i]['description']); 

    // 복호화 예시
    $list[$i]['title'] = $Decrypt -> aes_decrypt($list[$i]['title']);
    $list[$i]['description'] = $Decrypt -> aes_decrypt($list[$i]['description']); 

    $idValue = !isset($list[$i]['id']) ? 0 : $list[$i]['id'];
    $response = $sw -> select_info($idValue);  
    if(isset($response['id'])) {
        $response = $sw -> update_info($list[$i]);
        if($response){
            $result['update'] ++;
        } else {
            $result['failed'] ++;
        }
    } else {
        $response = $sw -> insert_info($list[$i]);
        if($response){
            $result['insert'] ++;
        } else {
            $result['failed'] ++;
        }
    }


    // $response = $sw -> select_info($encrypted['eno']);
    // if(isset($response['eno'])) {
    //     $response = $sw -> update_info($encrypted);
    //     if($response){
    //         $result['update'] ++;
    //     } else {
    //         $result['failed'] ++;
    //     }
    // } else {
    //     $response = $sw -> insert_info($encrypted);
    //     if($response){
    //         $result['insert'] ++;
    //     } else {
    //         $result['failed'] ++;
    //     }
    // }

}
$result['total'] = $sw -> get_total_employee();
echo json_encode($result);
?>