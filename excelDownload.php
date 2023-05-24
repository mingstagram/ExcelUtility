<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/PHPExcel/Classes/PHPExcel.php";


$conn = new mysqli("localhost", "root", "1111", "xmldb");
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}else{
    //echo "conn";
}
mysqli_query($conn,"SET NAMES utf8");

$filename = $_POST["filename"];
$width = $_POST["width"];
$height = $_POST["height"];
$channel = $_POST["channel"];
$object1 = $_POST["object1"];
$object2 = $_POST["object2"];
$object3 = $_POST["object3"];
$object4 = $_POST["object4"];
$object5 = $_POST["object5"];
$object6 = $_POST["object6"];

// DB insert
// $sql = "INSERT INTO xmltable3 VALUES ('$filename','$width','$height','$channel','$object1','$object2','$object3','$object4','$object5','$object6');";
// $result=mysqli_query($conn, $sql);

/*
$style = [
    'borders' => [
		'allborders' => [
			'style' => PHPExcel_Style_Border::BORDER_THIN
		]
    ],
    'alignment' => [
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ] 
];

$cellColor = [
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => 'FFFF00']
    ]
]; 

$phpExcel = new PHPExcel(); 

$phpExcel->setActiveSheetIndex(0);
$sheet = $phpExcel -> getActiveSheet(); 
$sheet->getStyle("A1:J1")->applyFromArray($style);
$sheet->getStyle("A1:J1")->applyFromArray($cellColor);

$sheet->getColumnDimension("A")->setWidth(20);
    $sheet->getColumnDimension("B")->setWidth(20);
    $sheet->getColumnDimension("C")->setWidth(20);
    $sheet->getColumnDimension("D")->setWidth(20);
    $sheet->getColumnDimension("E")->setWidth(20);
    $sheet->getColumnDimension("F")->setWidth(20);
    $sheet->getColumnDimension("G")->setWidth(20);
    $sheet->getColumnDimension("H")->setWidth(20); 
    $sheet->getColumnDimension("I")->setWidth(20); 
    $sheet->getColumnDimension("J")->setWidth(20); 

$sheet ->setCellValue("A1", "Filename")
        ->setCellValue("B1", "Width")
        ->setCellValue("C1", "Height")
        ->setCellValue("D1", "channel")
        ->setCellValue("E1", "Object1")
        ->setCellValue("F1", "Object2")
        ->setCellValue("G1", "Object3")
        ->setCellValue("H1", "Object4")
        ->setCellValue("I1", "Object5")
        ->setCellValue("J1", "Object6");

// DB 데이터 추출
$sql = "select * from xmltable order by filename;";
$result=mysqli_query($conn, $sql);

$line = 2;
while($row=mysqli_fetch_array($result)){
    $sheet->getStyle("A".$line.":J".$line)->applyFromArray($style); 
    $sheet->setCellValue("A".$line, $row["filename"])
            ->setCellValue("B".$line, $row["width"])
            ->setCellValue("C".$line, $row["height"])
            ->setCellValue("D".$line, $row["depth"])
            ->setCellValue("E".$line, $row["helmet"])
            ->setCellValue("F".$line, $row["goggles"])
            ->setCellValue("G".$line, $row["mask"])
            ->setCellValue("H".$line, $row["vest"])
            ->setCellValue("I".$line, $row["belt"])
            ->setCellValue("J".$line, $row["vestplusbelt"]); 
            $line++; 
}

$filename = "xmldata"; 
 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
$objWriter->save('php://output');
*/

?>