<?php

$sc = 'no_address';

if( isset( $_POST['id_customer'] ) )
{
  $id_customer  = $_POST['id_customer'];

  //--- จำนวนที่อยู่  transport_helper.php
  $adds 	      = countAddress($id_customer);

  //--- จำนวนผู้จัดส่ง transport_helper.php
  $sds		      = countSender($id_customer);

  //--- มีที่อยู่เดียว และผู้จัดส่งเดียว
  if($adds == 1 && $sds == 1 )
  {
    $sc = 1;
  }

  //--- มีที่อยู่ แต่ ไม่มีผู้จัดส่ง
  else if( $adds >= 1 && $sds < 1 )
  {

    $sc  = 'no_sender';

  }
  //--- มีที่อยู่มากกว่า 1 หรือ ผู้จัดส่งมากกว่า 1
  else
  {
    //--- มีที่อยู่มากกว่า 1 ที่
    if( $adds >= 1 )
    {
      $add  = '<tr>';
      $add .=   '<td colspan="2">';
      $add .=     '<strong>เลือกที่อยู่สำหรับจัดส่ง</strong>';
      $add .=   '</td>';
      $add .= '<tr>';

      //--- ได้ที่อยู่กลับมาเป็น array ถ้าไม่มีเป็น FALSE
      //--- transport_helper.php
      $ds	  = getAllCustomerAddress($id_customer);

      $n    = 1;
      if( $ds !== FALSE )
      {
        while( $rs = dbFetchArray($ds) )
        {
          $se = $n == 1 ? 'checked' : '';
          $add .= '<tr>';
          $add .=   '<td class="width-35">';
          $add .=     '<label>';
          $add .=       '<input type="radio" name="id_address" style="margin-left:15px; margin-right:15px;" value="'.$rs['id_address'].'" '.$se.' />';
          $add .=       $rs['alias'];
          $add .=     '</label>';
          $add .=   '</td>';
          $add .=   '<td>';
          $add .=     $rs['address1'].' '.$rs['address2'].' จังหวัด '.$rs['city'];
          $add .=   '</td>';
          $add .= '</tr>';
          $n++;
        }
      }
    }


    //--- มีผู้จัดส่งมากกว่า 1
    if( $sds >= 1 )
    {
      $dds  = '<tr>';
      $dds .=   '<td colspan="2">';
      $dds .=     '<strong>เลือกผู้ให้บริการจัดส่ง</strong>';
      $dds .=   '</td>';
      $dds .= '<tr>';

      $dd = getAllSender($id_customer); //--- transport_helper

      //--- กำหนดให้มีผู้จัดส่งได้ไม่เกิน 3 รายเท่านั้น
      if( $dd !== FALSE )
      {
        //--- ผู้จัดส่งรายหลัก
        $dds .= '<tr >';
        $dds .=   '<td colspan="2">';
        $dds .=     '<label>';
        $dds .=       '<input type="radio" style="margin-left:15px; margin-right:15px;" name="id_sender" value="'.$dd['main_sender'].'" checked />';
        $dds .=       sender_name($dd['main_sender']); //---  transport_helper
        $dds .=     '</label>';
        $dds .=   '</td>';
        $dds .= '<tr>';


        //--- รายที่ 2
        if( $dd['second_sender'] != 0 )
        {
          $dds .= '<tr>';
          $dds .=   '<td colspan="2">';
          $dds .=     '<label>';
          $dds .=       '<input type="radio" stu;e="margin-left:15px; margin-right:15px;" name="id_sender" value="'.$dd['second_sender'].'" />';
          $dds .=       sender_name($dd['second_sender']); //---  transport_helper
          $dds .=     '</label>';
          $dds .=   '</td>';
          $dds .= '<tr>';
        }


        //--- รายที่ 3
        if( $dd['third_sender'] != 0 )
        {
          $dds .= '<tr>';
          $dds .=   '<td colspan="2">';
          $dds .=     '<label>';
          $dds .=       '<input type="radio" stu;e="margin-left:15px; margin-right:15px;" name="id_sender" value="'.$dd['third_sender'].'" />';
          $dds .=       sender_name($dd['third_sender']); //---  transport_helper
          $dds .=     '</label>';
          $dds .=   '</td>';
          $dds .= '<tr>';
        }

      } //--- end if $ds
    }

    //--- ประกอบร่าง
    if( $adds >= 1 && $sds >= 1 )
    {
      $sc = '<table class="table table-bordered">';
      $sc .= $add;
      $sc .= $dds;
      $sc .= '</table>';
    }
  }
}


echo $sc;

 ?>
