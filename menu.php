<?php if( $viewStockOnly === FALSE ) : ?>
<ul class="nav navbar-nav">
  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cubes"></i>&nbsp; ระบบคลังสินค้า</a>
    <ul class="dropdown-menu">
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i>&nbsp; รับสินค้าเข้า</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=receive_product"><i class="fa fa-download"></i>&nbsp; รับสินค้า จากการซื้อ</a></li>
          <li><a href="index.php?content=receive_transform"><i class="fa fa-download"></i>&nbsp; รับสินค้า จากการแปรสภาพ</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i>&nbsp; รับคืนสินค้า</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=return_order"><i class="fa fa-download"></i>&nbsp; รับคืนสินค้า จากการขาย(ลดหนี้)</a></li>
          <li><a href="index.php?content=support_return"><i class="fa fa-download"></i>&nbsp; รับคืนสินค้า จากอภินันท์</a></li>
          <li><a href="index.php?content=sponsor_return"><i class="fa fa-download"></i>&nbsp; รับคืนสินค้า จากสปอนเซอร์</a></li>
          <li><a href="index.php?content=lend_return"><i class="fa fa-download"></i>&nbsp; รับคืนสินค้า จากการยืม</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-upload"></i>&nbsp; ส่งคืนสินค้า</a>
      	<ul class="dropdown-menu">
          <li><a href="index.php?content=return_received"><i class="fa fa-upload"></i>&nbsp; ส่งคืนสินค้า(ลดหนี้ซื้อ)</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="index.php?content=order_transform"><i class="fa fa-upload"></i>&nbsp; เบิกสินค้าเพื่อแปรสภาพ</a></li>
      <li><a href="index.php?content=order_support"><i class="fa fa-upload"></i>&nbsp; เบิกอภินันทนาการ</a></li>
      <li><a href="index.php?content=order_lend"><i class="fa fa-upload"></i>&nbsp; ยืมสินค้า</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=transfer"><i class="fa fa-recycle"></i>&nbsp; โอนสินค้าระหว่างคลัง</a></li>
      <li><a href="index.php?content=move"><i class="fa fa-recycle"></i>&nbsp; ย้ายพื้นที่จัดเก็บ</a></li>
      <li><a href="index.php?content=ProductCheck"><i class="fa fa-check-square-o"></i>&nbsp; ตรวจนับสินค้า</a></li>
      <li><a href="index.php?content=adjust"><i class="fa fa-magic"></i>&nbsp; ปรับปรุงสต็อก</a></li>
      <li><a href="index.php?content=buffer_zone"><span class="glyphicon glyphicon-tasks"></span>&nbsp; ตรวจสอบ BUFFER ZONE</a></li>
      <li><a href="index.php?content=cancle_zone"><span class="glyphicon glyphicon-tasks"></span>&nbsp; ตรวจสอบ CANCLE ZONE</a></li>
      <li><a href="index.php?content=stock_movement"><span class="glyphicon glyphicon-tasks"></span>&nbsp; ตรวจสอบ MOVEMENT</a></li>
      <li><a href="index.php?content=stock"><span class="glyphicon glyphicon-tasks"></span>&nbsp; ตรวจสอบ STOCK</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-bag"></i>&nbsp; ระบบขาย</a>
    <ul class="dropdown-menu">
      <li><a href="index.php?content=order"><i class="fa fa-shopping-bag"></i>&nbsp; ออเดอร์</a></li>
      <li><a href="index.php?content=order_sponsor"><i class="fa fa-ticket"></i>&nbsp; สปอนเซอร์ สโมสร</a></li>
      <li><a href="index.php?content=order_consign"><i class="fa fa-cloud-upload"></i>&nbsp; ฝากขาย [SO]</a></li>
      <li><a href="index.php?content=order_consignment"><i class="fa fa-cloud-upload"></i>&nbsp; ฝากขาย [TR]</a></li>
      <li><a href="index.php?content=order_online"><i class="fa fa-desktop"></i>&nbsp; ขายออนไลน์</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=prepare"><i class="fa fa-shopping-basket"></i>&nbsp; จัดสินค้า</a></li>
      <li><a href="index.php?content=qc"><i class="fa fa-check-square-o"></i>&nbsp; ตรวจสินค้า</a></li>
      <li><a href="index.php?content=bill"><i class="fa fa-file-text-o"></i>&nbsp; ออเดอร์รอเปิดบิล</a></li>
      <li><a href="index.php?content=order_closed"><i class="fa fa-file-text-o"></i>&nbsp; ออเดอร์เปิดบิลแล้ว</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=order_monitor"><i class="fa fa-shopping-bag"></i>&nbsp; ติดตามออเดอร์</a></li>
      <li><a href="index.php?content=order_expire"><i class="fa fa-history"></i>&nbsp; ตรวจสอบอายุออเดอร์</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-credit-card"></i>&nbsp; ระบบบัญชี</a>
    <ul class="dropdown-menu">
      <li><a href="index.php?content=consign_check"><i class="fa fa-check-square-o"></i>&nbsp; กระทบยอดสินค้าฝากขาย</a></li>
      <li><a href="index.php?content=consign"><i class="fa fa-check-square-o"></i>&nbsp; ตัดยอดฝากขาย</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=payment_order"><i class="fa fa-check-square-o"></i>&nbsp; ตรวจสอบยอดชำระเงิน</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i>&nbsp; ระบบซื้อ</a>
  	<ul class="dropdown-menu">
      <li><a href="index.php?content=po"><i class="fa fa-archive"></i>&nbsp; ใบสั่งซื้อ(PO)</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน</a>
  	<ul class="dropdown-menu">
    	<li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ระบบคลังสินค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=received_by_document"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน การรับสินค้าจากการซื้อแยกตามเอกสาร</a></li>
          <li><a href="index.php?content=received_transform_by_document"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน การรับสินค้าแปรสภาพแยกตามเอกสาร</a></li>
          <li><a href="index.php?content=stock_balance"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน สินค้าคงเหลือ</a></li>
          <li><a href="index.php?content=current_stock"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน สินค้าคงเหลือปัจจุบัน</a></li>
          <li><a href="index.php?content=stock_balance_by_zone"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน สินค้าคงเหลือแยกตามโซน</a></li>
          <li><a href="index.php?content=stock_balance_compare_warehouse"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน สินค้าคงเหลือเปรียบเทียบคลัง</a></li>
          <li><a href="index.php?content=stock_balance_by_warehouse"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน สินค้าคงเหลือแยกตามคลัง</a></li>
          <li><a href="index.php?content=stock_year"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน สินค้าคงเหลือแยกตามปีสินค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=stock_movement_by_warehouse"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน ความเคลื่อนไหวสินค้าแยกตามคลังสินค้า</a></li>
          <li><a href="index.php?content=stock_movement_by_product"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน ความเคลื่อนไหวสินค้าแยกตามสินค้า</a></li>
          <li><a href="index.php?content=stock_not_move"><i class="fa fa-bar-chart"></i>&nbsp;รายงาน สินค้าไม่เคลื่อนไหว</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ระบบขาย</a>
      	<ul class="dropdown-menu">
          <li><a href="index.php?content=sale_by_customer_order"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดขายแยกตามลูกค้า แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=sale_by_customer_items"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดขายแยกตามลูกค้า แสดงรายการสินค้า</a></li>
          <li><a href="index.php?content=sale_deep_analyz"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ขายแบบละเอียด</a></li>
          <li><a href="index.php?content=sale_by_channels_show_reference"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ยอดขายแยกตางช่องทางการขาย แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=sale_online_channels_details"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ออเดอร์ออนไลน์ แสดงรายละเอียดการจัดส่ง</a></li>
        </ul>
      </li>

       <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ระบบซื้อ</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=po_backlog"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ใบสั่งซื้อค้างรับ</a></li>
          <li><a href="index.php?content=po_history_by_product"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดการสั่งซื้อ ตามรุ่นสินค้า</a></li>
          <li><a href="index.php?content=po_detail"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน รายละเอียดใบสั่งซื้อ</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ติดตาม</a>
      	<ul class="dropdown-menu">
        	<!--<li><a href="index.php?content=stock_backlogs"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สินค้าค้างส่ง</a></li>-->
          <li><a href="index.php?content=lend_not_return"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน การยืมสินค้ายังไม่ส่งคืน</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ตรวจสอบ</a>
      	<ul class="dropdown-menu">
          <li><a href="index.php?content=sponsor_summary_by_budget"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ</a></li>
          <li><a href="index.php?content=sponsor_by_customer_order"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=sponsor_by_customer_items"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงรายการสินค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=support_summary_by_budget"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดอภินันท์แยกตามผู้รับ</a></li>
          <li><a href="index.php?content=support_by_customer_order"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดอถินันท์แยกตามผู้รับ แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=support_by_customer_items"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน สรุปยอดอภินันท์แยกตามผู้รับ แสดงรายการสินค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=permission_by_employee"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน ตรวจสอบสิทธิ์ แยกตามพนักงาน</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน วิเคราะห์</a>
      	<ul class="dropdown-menu">

        </ul>
      </li>
    </ul>
  </li><!-- รายงาน -->

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i>&nbsp; การตั้งค่า</a>
    <ul class="dropdown-menu">
    	<li><a href="index.php?content=config"><i class="fa fa-cogs"></i>&nbsp; การตั้งค่า</a></li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i>&nbsp; นโยบายส่วนลด</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=discount_policy"><i class="fa fa-cog"></i>&nbsp; เพิ่ม/แก้ไข นโยบายส่วนลด</a></li>
          <li><a href="index.php?content=discount_rule"><i class="fa fa-cog"></i>&nbsp; เพิ่ม/แก้ไข เงื่อนไขส่วนลด</a></li>
        </ul>
      </li>

      <li class="divider"></li>
      <li><a href="index.php?content=popup"><i class="fa fa-bullhorn"></i>&nbsp; การแจ้งข่าว</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=Profile"><i class="fa fa-folder"></i>&nbsp; เพิ่ม/แก้ไข โปรไฟล์</a></li>
      <li><a href="index.php?content=securable"><i class="fa fa-unlock-alt"></i>&nbsp; กำหนดสิทธิ์</a></li>
    </ul>
  </li>

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-database"></i>&nbsp; ฐานข้อมูล</a>
  	<ul class="dropdown-menu">
  		<li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tags"></i>&nbsp; สินค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=product"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข สินค้า</a></li>
          <li><a href="index.php?content=style"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข รุ่นสินค้า</a></li>
          <li><a href="index.php?content=category"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข หมวดหมู่สินค้า</a></li>
          <li><a href="index.php?content=product_group"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข กลุ่มสินค้า</a></li>
          <li><a href="index.php?content=product_sub_group"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข กลุ่มย่อยสินค้า</a></li>
          <li><a href="index.php?content=kind"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข ประเภทสินค้า</a></li>
          <li><a href="index.php?content=type"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข ชนิดสินค้า</a></li>
          <li><a href="index.php?content=brand"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข ยี่ห้อสินค้า</a></li>
          <li><a href="index.php?content=product_tab"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข แถบเสดงสินค้า</a></li>
          <li><a href="index.php?content=unit"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข หน่วยนับ</a></li>
          <li><a href="index.php?content=color"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข สี</a></li>
          <li><a href="index.php?content=size"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข ไซด์</a></li>
          <li><a href="index.php?content=barcode"><i class="fa fa-tags"></i>&nbsp; เพิ่ม/แก้ไข บาร์โค้ด</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i>&nbsp; คลังสินค้า</a>
        <ul class="dropdown-menu">
        	<li><a href="index.php?content=zone"><i class="fa fa-map-marker"></i>&nbsp; เพิ่ม/แก้ไข โซนสินค้า</a></li>
      		<li><a href="index.php?content=warehouse"><i class="fa fa-home"></i>&nbsp; เพิ่ม/แก้ไข คลังสินค้า</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i>&nbsp; ลูกค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=customer"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข ลูกค้า</a></li>
          <li><a href="index.php?content=customer_address"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข ที่อยู่ลูกค้า</a></li>
          <li><a href="index.php?content=customer_kind"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข ประเภทลูกค้า</a></li>
          <li><a href="index.php?content=customer_type"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข ชนิดลูกค้า</a></li>
          <li><a href="index.php?content=customer_group"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข กลุ่มลูกค้า</a></li>
          <li><a href="index.php?content=customer_class"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข เกรดลูกค้า</a></li>
          <li><a href="index.php?content=customer_area"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข เขตลูกค้า</a></li>
          <li><a href="index.php?content=customer_transfer"><i class="fa fa-user"></i>&nbsp; โอน/ย้าย ลูกค้า</a></li>
          <li><a href="index.php?content=customer_credit"><i class="fa fa-user"></i> &nbsp; วงเงินเครดิต</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shield"></i> &nbsp; สปอนเซอร์</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=sponsor"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข รายชื่อสปอนเซอร์</a></li>
          <li><a href="index.php?content=sponsor_approver"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข ผู้อนุมัติงบสปอนเซอร์</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>&nbsp; พนักงาน</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=Employee"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข พนักงาน</a></li>
          <li><a href="index.php?content=sale_group"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข ทีมขาย</a></li>
          <li><a href="index.php?content=sale"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข พนักงานขาย</a></li>
          <li><a href="index.php?content=support"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข  รายชื่ออภินันทนาการ</a></li>
          <li><a href="index.php?content=support_approver"><i class="fa fa-user"></i>&nbsp; เพิ่ม/แก้ไข ผู้อนุมัติงบอภินันท์</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i>&nbsp; ผู้จำหน่าย</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=supplier"><i class='fa fa-user'></i>&nbsp; เพิ่ม/แก้ไข ผู้จำหน่าย</a></li>
          <li><a href="index.php?content=supplier_group"><i class='fa fa-users'></i>&nbsp; เพิ่ม/แก้ไข กลุ่มผู้จำหน่าย</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-star"></i>&nbsp; การตลาด</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=channels"><i class='fa fa-star'></i>&nbsp; เพิ่ม/แก้ไข ช่องทางการขาย</a></li>
          <li><a href="index.php?content=payment_method"><i class='fa fa-star'></i>&nbsp; เพิ่ม/แก้ไข ช่องทางการชำระเงิน</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-truck"></i>&nbsp; จัดส่ง</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=sender"><i class="fa fa-truck"></i>&nbsp; เพิ่ม/แก้ไข รายชื่อผู้จัดส่ง</a></li>
          <li><a href="index.php?content=transport"><i class="fa fa-truck"></i>&nbsp; เชื่อมโยงการจัดส่งของลูกค้า</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bank"></i>&nbsp; บริษัท</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=branch"><i class="fa fa-bank"></i>&nbsp; เพิ่ม/แก้ไข สาขา</a></li>
          <li><a href="index.php?content=bank_account"><i class="fa fa-bank"></i>&nbsp; เพิ่ม/แก้ไข บัญชีธนาคาร</a></li>
        </ul>
      </li>
      <li><a href="index.php?content=syncData"><i class="fa fa-exchange"></i>&nbsp; นำเข้าข้อมูลจาก formula</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=export_product_db"><i class="fa fa-database"></i>&nbsp; ส่งออกรายการสินค้า นำเข้า POS</a></li>
      <li><a href="index.php?content=import_stock_zone"><i class='fa fa-database'></i>&nbsp; บันทึกยอดสินค้าคงเหลือยกมาต้นงวด</a></li>
      <li><a href="index.php?content=export_order_dhl"><i class="fa fa-database"></i>&nbsp; ส่งออกออเดอร์ นำเข้า DHL</a></li>
      <!--<li><a href="index.php?content=test_run"><i class='fa fa-database'></i>&nbsp; ทดสอบระบบ</a></li> -->

    </ul>
  </li>

<!--            <li><a href="../doc/index.php" target="_blank"><i class="fa fa-book"></i>&nbsp; คู่มือการใช้งาน</a></li> -->
</ul>
<?php endif; ?>
