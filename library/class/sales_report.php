<?php
class sales_report
{

  public $role = 1;


  public function __construct()
  {

  }

  public function getTodaySales($id)
  {
    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".date('Y-m-d 00:00:00')."' ";
    $qr .= "AND date_add <= '".date('Y-m-d 23:59:59')."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;
  }



  public function getYesterdaySales($id)
  {
    $day = date('Y-m-d',strtotime("-1 day"));
    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".fromDate($day)."' ";
    $qr .= "AND date_add <= '".toDate($day)."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;

  }



  public function getThisWeekSales($id)
  {
    $from = date('Y-m-d 00:00:00', strtotime("Monday this week"));
    $to = date('Y-m-d 23:59:59', strtotime("Sunday this week"));

    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".$from."' ";
    $qr .= "AND date_add <= '".$to."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;

  }




  public function getLastWeekSales($id)
  {
    $from = date('Y-m-d 00:00:00', strtotime("Monday last week"));
    $to = date('Y-m-d 23:59:59', strtotime("Sunday last week"));

    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".$from."' ";
    $qr .= "AND date_add <= '".$to."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;

  }



  public function getThisMonthSales($id)
  {
    $from = date('Y-m-01 00:00:00');
    $to = date('Y-m-t 23:59:59');

    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".$from."' ";
    $qr .= "AND date_add <= '".$to."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;
  }


  public function getLastMonthSales($id)
  {
    $from = date('Y-m-01 00:00:00', strtotime("last month"));
    $to = date('Y-m-t 23:59:59', strtotime("last month"));

    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".$from."' ";
    $qr .= "AND date_add <= '".$to."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;
  }



  public function getThisYearSales($id)
  {
    $from = date('Y-01-01 00:00:00');
    $to = date('Y-12-31 23:59:59');

    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".$from."' ";
    $qr .= "AND date_add <= '".$to."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;
  }



  public function getLastYearSales($id)
  {
    $from = date('Y-01-01 00:00:00', strtotime("last year"));
    $to = date('Y-12-31 23:59:59', strtotime("last year"));

    $qr  = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold ";
    $qr .= "WHERE id_sale = '".$id."' ";
    $qr .= "AND id_role IN(".$this->role.") ";
    $qr .= "AND date_add >= '".$from."' ";
    $qr .= "AND date_add <= '".$to."' ";
    $qs = dbQuery($qr);
    $rs = dbFetchObject($qs);

    return is_null($rs->amount) ? 0.00 : $rs->amount;
  }








} //--- end class




 ?>
