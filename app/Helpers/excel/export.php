<?php


	require('php-excel-reader/excel_reader2.php');
	require('SpreadsheetReader.php');

function excelToArray($Filepath){
    $result = [];
    date_default_timezone_set('UTC');
    $StartMem = memory_get_usage();

    try {
        $Spreadsheet = new SpreadsheetReader($Filepath);
        $BaseMem = memory_get_usage();
        $Sheets = $Spreadsheet->Sheets();
        foreach ($Sheets as $Index => $Name) {
            $Spreadsheet->ChangeSheet($Index);
            foreach ($Spreadsheet as $Key => $Row) {
                $result[] =$Row;
            }
        }
    } catch (Exception $E) {
        echo $E->getMessage();
    }
    file_put_contents('test1.txt',json_encode($result));

    return $result;
}
?>
