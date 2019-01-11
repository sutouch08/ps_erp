<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
include '../function/report_helper.php';

if(isset($_GET['permissionByEmployee']) && isset($_GET['export']))
{
  set_time_limit(600);
  $qr = "SELECT * FROM tbl_employee ";
  $qr .= "WHERE id_employee > 0 AND active = 1 ";
  $qr .= "ORDER BY id_employee ASC ";

  $qm  = dbQuery($qr);
  $qs  = dbQuery($qr);
  $tab = dbNumRows($qs);

  $excel = new PHPExcel();
  $index = 0;

  if(dbNumRows($qm) > 0)
  {

    $excel->setActiveSheetIndex($index);
    $excel->getActiveSheet()->setTitle('รายชื่อพนักงาน');

    $excel->getActiveSheet()->setCellValue('A1', 'รายชื่อพนักงาน');
    $excel->getActiveSheet()->mergeCells('A1:B1');
    $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');

    $excel->getActiveSheet()->setCellValue('A2', 'ลำดับ');
    $excel->getActiveSheet()->setCellValue('B2', 'พนักงาน');

    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $row = 3;
    $no = 1;
    while($rs = dbFetchObject($qm))
    {
      $excel->getActiveSheet()->setCellValue('A'.$row, $no);
      $excel->getActiveSheet()->setCellValue('B'.$row, $rs->first_name.' '.$rs->last_name);
      $row++;
      $no++;
    }

    $index++;
  }


  if($tab > 0)
  {
    while($cs = dbFetchObject($qs))
    {
      $excel->createSheet();
      $excel->setActiveSheetIndex($index);
      $excel->getActiveSheet()->setTitle(strval($index));

      $excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
      $excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
      $excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
      $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
      $excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
      $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

      $excel->getActiveSheet()->setCellValue('A1', 'รายงานตรวจสอบสิทธิ์');
      $excel->getActiveSheet()->mergeCells('A1:F1');
      $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');

      $excel->getActiveSheet()->setCellValue('A2', 'พนักงาน');
      $excel->getActiveSheet()->setCellValue('B2', $cs->first_name.' '.$cs->last_name);
      $excel->getActiveSheet()->setCellValue('C2', 'สิทธิ์');
      $excel->getActiveSheet()->mergeCells('C2:F2');
      $excel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal('center');

      $excel->getActiveSheet()->setCellValue('A3', 'ลำดับ');
      $excel->getActiveSheet()->setCellValue('B3', 'เมนู');
      $excel->getActiveSheet()->setCellValue('C3', 'ดู');
      $excel->getActiveSheet()->setCellValue('D3', 'เพิ่ม');
      $excel->getActiveSheet()->setCellValue('E3', 'แก้ไข');
      $excel->getActiveSheet()->setCellValue('F3', 'ลบ');
      $excel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal('center');

      $qa  = "SELECT ac.view, ac.add, ac.edit, ac.delete, tab.tab_name ";
      $qa .= "FROM tbl_access AS ac ";
      $qa .= "LEFT JOIN tbl_tab AS tab ON ac.id_tab = tab.id_tab ";
      $qa .= "WHERE ac.id_profile = ".$cs->id_profile." ";
      $qa .= "ORDER BY tab.id_group ASC, tab.position ASC";

      $qb = dbQuery($qa);

      $no = 1;
      $row = 4;
      while($rs = dbFetchObject($qb))
      {
        $excel->getActiveSheet()->setCellValue('A'.$row, $no);
        $excel->getActiveSheet()->setCellValue('B'.$row, $rs->tab_name);
        $excel->getActiveSheet()->setCellValue('C'.$row, ($rs->view == 1 ? 'Y' : 'N'));
        $excel->getActiveSheet()->setCellValue('D'.$row, ($rs->add == 1 ? 'Y' : 'N'));
        $excel->getActiveSheet()->setCellValue('E'.$row, ($rs->edit == 1 ? 'Y' : 'N'));
        $excel->getActiveSheet()->setCellValue('F'.$row, ($rs->delete == 1 ? 'Y' : 'N'));

        $row++;
        $no++;
      }

      $excel->getActiveSheet()->getStyle('C4:F'.$row)->getAlignment()->setHorizontal('center');

      $index++;
    }

  }

  setToken($_GET['token']);
  $file_name = "premission.xlsx";
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
  header('Content-Disposition: attachment;filename="'.$file_name.'"');
  $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
  $writer->save('php://output');
}


 ?>
