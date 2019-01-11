<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];
$from = fromDate($_GET['fromDate']);
$to  = toDate($_GET['toDate']);
$allWh = $_GET['allWarehouse'];
$WH = isset($_GET['wh']) ? $_GET['wh'] : FALSE;
$wh_in = "";
$wh_code = "";

$mv = new movement();

if($allWh == 0 && $WH != FALSE)
{
  if(!empty($WH)){
    $i = 1;
    foreach($WH as $id_warehouse)
    {
      $warehouse = new warehouse($id_warehouse);
      $wh_in .= $i == 1 ? "'".$warehouse->id."'" : ", '".$warehouse->id."'";
      $wh_code .= $i == 1 ? $warehouse->code : ", ".$warehouse->code;
      $i++;
    }
  }
}


//---- ข้อมูลเงื่อนไข
$bf_date = date('Y-m-d', strtotime("-1day $from"));
$wh_title = $allWh == 0 ? $wh_code : 'ทั้งหมด';

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Stock Card');

$excel->getActiveSheet()->setCellValue('A1', 'รายงานความเคลื่อนไหวสินค้า');
$excel->getActiveSheet()->mergeCells('A1:F1');

$excel->getActiveSheet()->setCellValue('A2', 'คลัง :');
$excel->getActiveSheet()->setCellValue('B2', $wh_title);
$excel->getActiveSheet()->mergeCells('B2:F2');

$excel->getActiveSheet()->setCellValue('A3', 'สินค้า :');
$excel->getActiveSheet()->setCellValue('B3', $pdFrom.' - '.$pdTo);
$excel->getActiveSheet()->mergeCells('B3:F3');

$excel->getActiveSheet()->setCellValue('A4', 'วันที่ :');
$excel->getActiveSheet()->setCellValue('B4', thaiDate($from, '/').' - '.thaiDate($to, '/'));
$excel->getActiveSheet()->mergeCells('B4:F4');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

$row = 6;



$qp  = "SELECT pd.id, pd.code, pd.name FROM tbl_product AS pd ";
$qp .= "LEFT JOIN tbl_product_style AS ps ON pd.id_style = ps.id ";
$qp .= "WHERE pd.code >= '".$pdFrom."' ";
$qp .= "AND pd.code <= '".$pdTo."' ";
$qp .= "ORDER BY ps.code ASC, pd.code ASC";

$qc = dbQuery($qp);
if(dbNumRows($qc) > 0)
{
  while($pr = dbFetchObject($qc))
  {
    $sRow = $row;
    $balance = $mv->getStockBalance($pr->id, $wh_in, $bf_date);
    $header  = $pr->code .' : '. $pr->name;
    $excel->getActiveSheet()->setCellValue('A'.$row, $header);
    $excel->getActiveSheet()->mergeCells('A'.$row.':F'.$row);
    $excel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
    $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical('center');
    $row++;

    $excel->getActiveSheet()->setCellValue('A'.$row, 'วันที่');
    $excel->getActiveSheet()->setCellValue('B'.$row, 'เลขที่เอกสาร');
    $excel->getActiveSheet()->setCellValue('C'.$row, 'คลังสินค้า');
    $excel->getActiveSheet()->setCellValue('D'.$row, 'เข้า');
    $excel->getActiveSheet()->setCellValue('E'.$row, 'ออก');
    $excel->getActiveSheet()->setCellValue('F'.$row, 'คงเหลือ');
    $excel->getActiveSheet()->getStyle('A'.$row.':F'.$row)->getAlignment()->setHorizontal('center');
    $row++;

    $excel->getActiveSheet()->setCellValue('A'.$row, thaiDate($bf_date));
    $excel->getActiveSheet()->setCellValue('B'.$row, 'ยอดยกมา');
    $excel->getActiveSheet()->mergeCells('B'.$row.':E'.$row);
    $excel->getActiveSheet()->setCellValue('F'.$row, $balance);
    $excel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode('#,##0');
    $row++;


    $qr  = "SELECT mv.reference, SUM(mv.move_in) AS move_in, SUM(mv.move_out) AS move_out, mv.date_upd, wh.name AS wh_name ";
    $qr .= "FROM tbl_stock_movement AS mv ";
    $qr .= "LEFT JOIN tbl_warehouse AS wh ON mv.id_warehouse = wh.id ";
    $qr .= "WHERE mv.id_product = '".$pr->id."' ";
    $qr .= "AND mv.date_upd >= '".$from."' AND mv.date_upd <= '".$to."' ";

    if($allWh == 0)
    {
      $qr .= "AND mv.id_warehouse IN(".$wh_in.") ";
    }
    $qr .= "GROUP BY mv.reference, mv.id_warehouse ";

    $qr .= "ORDER BY mv.date_upd ASC";

    //echo $qr;

    $qs = dbQuery($qr);
    if(dbNumRows($qs) > 0)
    {
      while($rs = dbFetchObject($qs))
      {
        $balance += ($rs->move_in - $rs->move_out);
        $excel->getActiveSheet()->setCellValue('A'.$row, thaiDate($rs->date_upd,'/'));
        $excel->getActiveSheet()->setCellValue('B'.$row, $rs->reference);
        $excel->getActiveSheet()->setCellValue('C'.$row, $rs->wh_name);
        $excel->getActiveSheet()->setCellValue('D'.$row, $rs->move_in);
        $excel->getActiveSheet()->setCellValue('E'.$row, $rs->move_out);
        $excel->getActiveSheet()->setCellValue('F'.$row, $balance);
        $excel->getActiveSheet()->getStyle('D'.$row.':F'.$row)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,##0);_(* "-"_);_(@_)');

        $row++;
      } //--- end while $rs
    } //--- end if dbNumRows $qs

    $excel->getActiveSheet()->getStyle('A'.$sRow.':F'.($row-1))->getBorders()->getAllBorders()->setBorderStyle('thin');

    $row++;
    $row++;

  } //--- end while
} //-- end if

/*
echo '<pre>';
print_r($excel);
echo '</pre>';
*/

setToken($_GET['token']);
$file_name = "Stock Card.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

 ?>
