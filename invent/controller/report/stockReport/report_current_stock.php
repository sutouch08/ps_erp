<?php
$pg = new product_group();
$pd = new product();

//--- จำนวนคงเหลือ มูลค่าคงเหลือ
$qr = dbQuery("SELECT SUM(qty), SUM(qty * cost) FROM tbl_stock JOIN tbl_product ON tbl_stock.id_product = tbl_product.id ");
list($sumQty, $sumCost) = dbFetchArray($qr);

//--- จำนวนรุ่นสินค้า
$qr = dbQuery("SELECT count(id) FROM tbl_product_style");
list($sumStyle) = dbFetchArray($qr);

//--- จำนวนรายการสินค้า
$qr = dbQuery("SELECT count(id) FROM tbl_product");
list($sumItem) = dbFetchArray($qr);


$page = '';
$page .= '<div class="row">';
$page .= '  <div class="col-sm-3 padding-5 first">';
$page .= '   <div class="icon-box i-blue">Qty</div>';
$page .= '   <div class="info-box c-blue">'.number($sumQty).'</div>';
$page .= '  </div>';
$page .= '  <div class="col-sm-3 padding-5">';
$page .= '    <div class="icon-box i-green">Cost</div>';
$page .= '    <div class="info-box c-green">'.number($sumCost, 2).'</div>';
$page .= '  </div>';
$page .= '  <div class="col-sm-3 padding-5">';
$page .= '    <div class="icon-box i-orange">Items</div>';
$page .= '    <div class="info-box c-orange">'.number($sumItem).'</div>';
$page .= '  </div>';
$page .= '  <div class="col-sm-3 padding-5 last">';
$page .= '    <div class="icon-box i-red">Style</div>';
$page .= '    <div class="info-box c-red">'.number($sumStyle).'</div>';
$page .= '  </div>';
$page .= '</div>';
$page .= '<hr/>';


$qs = $pg->getProductGroup();

$page .= '<div class="row">';
$page .= '  <div class="col-sm-2 padding-right-0" style="padding-top:15px;">';
$page .= '    <ul id="myTab1" class="setting-tabs">';

if(dbNumRows($qs) > 0)
{
  $i = 1;
  while($rs = dbFetchObject($qs))
  {
    $page .= '<li class="li-block '.($i == 1 ? 'active' : '').'">';
    $page .= ' <a href="#'.$rs->code.'" data-toggle="tab">'.$rs->name.'</a>';
    $page .= '</li>';
    $i++;
  }
}

$page .= '    </ul>';
$page .= '  </div>';
$page .= '  <div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">';
$page .= '    <div class="tab-content">';

$qs = $pg->getProductGroup();
if(dbNumRows($qs) > 0)
{
  $i = 1;
  while($rs = dbFetchObject($qs))
  {
    $page .= '<div role="tabpanel" class="tab-pane '.($i == 1 ? 'active' : '').'" id="'.$rs->code.'">';

    //--- จำนวนคงเหลือ มูลค่าคงเหลือ
    $qr = dbQuery("SELECT SUM(qty), SUM(qty * cost) FROM tbl_stock JOIN tbl_product ON tbl_stock.id_product = tbl_product.id WHERE id_group = '".$rs->id."'");
    list($sumQty, $sumCost) = dbFetchArray($qr);

    //--- จำนวนรุ่นสินค้า
    $qr = dbQuery("SELECT DISTINCT id_style FROM tbl_product WHERE id_group = '".$rs->id."' ");
    $sumStyle = dbNumRows($qr);

    //--- จำนวนรายการสินค้า
    $qr = dbQuery("SELECT count(id) FROM tbl_product WHERE id_group = '".$rs->id."' ");
    list($sumItem) = dbFetchArray($qr);

    $page .= '<div class="row">';
    $page .= '  <div class="col-sm-3 padding-5 first">';
    $page .= '   <div class="sub-icon i-blue">Qty</div>';
    $page .= '   <div class="sub-info c-blue font-size-16">'.number($sumQty).'</div>';
    $page .= '  </div>';
    $page .= '  <div class="col-sm-3 padding-5">';
    $page .= '    <div class="sub-icon i-green">Cost</div>';
    $page .= '    <div class="sub-info c-green font-size-16">'.number($sumCost, 2).'</div>';
    $page .= '  </div>';
    $page .= '  <div class="col-sm-3 padding-5">';
    $page .= '    <div class="sub-icon i-orange">Items</div>';
    $page .= '    <div class="sub-info c-orange font-size-16">'.number($sumItem).'</div>';
    $page .= '  </div>';
    $page .= '  <div class="col-sm-3 padding-5 last">';
    $page .= '    <div class="sub-icon i-red">Style</div>';
    $page .= '    <div class="sub-info c-red">'.number($sumStyle).'</div>';
    $page .= '  </div>';
    $page .= '</div>';
    $page .= '<hr/>';


    $qr  = "SELECT style.code, pd.id, pd.id_style, SUM(stock.qty) AS qty, SUM(stock.qty * pd.cost) AS amount ";
    $qr .= "FROM tbl_stock AS stock ";
    $qr .= "JOIN tbl_product AS pd ON stock.id_product = pd.id ";
    $qr .= "JOIN tbl_product_style AS style ON pd.id_style = style.id ";
    $qr .= "WHERE pd.id_group = '".$rs->id."' ";
    $qr .= "GROUP BY pd.id_style ORDER BY style.code ASC";

    //echo $qr;
    $qa = dbQuery($qr);
    $img = new image();
    while($rb = dbFetchObject($qa))
    {
      $page .= '<div class="item2 col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center margin-bottom-15">';
  		$page .= 	'<div class="product padding-5">';
  		$page .= 		'<div class="image">';
  		$page .= 			'<a href="javascript:void(0)" onclick="getData(\''.$rb->id_style.'\')">';
  		$page .=			'<img src="'.$img->getProductImage($rb->id, 2).'" class="img-responsoive" />';
  		$page .=			'</a>';
  		$page .= 		'</div>';
  		$page .= 		'<div class="description font-size-12">' . $rb->code . '</div>';
  		$page .= 		'<div class="price text-center">';
  		$page .=			'<span class="red font-size-10">' . number($rb->qty) . '</span>';
  		$page .=			' | ';
  		$page .=			'<span class="blue font-size-10">' . number($rb->amount, 2) . '</span>';
  		$page .=		'</div>';
  		$page .= 	'</div>';
  		$page .= '</div>';
    }

    $page .= '</div>';
    $i++;
  }
}


echo $page;
?>
