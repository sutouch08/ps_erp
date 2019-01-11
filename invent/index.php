<?php
require_once '../library/config.php';
require_once '../library/functions.php';
require_once 'function/tools.php';

if( !getConfig('CLOSED') )
{
	checkUser();
	$user_id = getCookie('user_id');

	$id_profile = getProfile($user_id);

	$viewStockOnly = isViewStockOnly($id_profile);

	$id_tab = '';

	$fast_qc = getConfig('FAST_QC');

	$content = 'main.php';

	$page = ( isset($_GET['content'] ) && $_GET['content'] != '' ) ? $_GET['content'] : '' ;

	switch($page){


		case 'test_run' :
			$content = 'test.php';
			$pageTitle = 'แสดงผลการทดสอบระบบ';
			break;


		case 'syncData' :
			$content = 'syncData.php';
			$pageTitle = 'นำเข้าข้อมูลจาก formula';
			break;

		case 'import_stock_zone' :
			$content = 'import_stock_zone.php';
			$pageTitle = 'บันทึกยอดสินค้าคงเหลือยกมาต้นงวด';
			break;

		case 'export_product_db' :
			$content = 'export_product_db.php';
			$pageTitle = 'ส่งออกฐานข้อมูลสินค้า';
			break;

		case 'export_order_dhl' :
			$content = 'export_order_dhl.php';
			$pageTitle = 'ส่งออกออเดอร์ไป DHL';
			break;

		case 'order_expire' :
			$content = 'order_expired.php';
			$pageTitle = 'ตรวจสอบอายุออเดอร์';
			break;


//**********  ระบบคลังสินค้า  **********//
		case 'receive_product':
			$content = 'receive_product.php';
			$pageTitle = 'รับสินค้าเข้า';
			break;

		case 'receive_transform' :
			$content 		= 'receive_transform.php';
			$pageTitle	= 'รับเข้าจากการแปรสภาพ';
			break;

		case 'return_order':
			$content = 'return_order.php';
			$pageTitle = 'รับคืนสินค้าจากการขาย(ลดหนี้)';
			break;

		case 'sponsor_return':
			$content = 'sponsor_return.php';
			$pageTitle = 'รับคืนสินค้าสปอนเซอร์';
			break;

		case 'support_return':
			$content = 'support_return.php';
			$pageTitle = 'รับคืนสินค้าอภินันท์';
			break;

		case 'lend_return' :
			$id_tab = 92;
			$content = 'lend_return.php';
			$pageTitle = 'รับคืนสินค้าจากการยืม';
			break;

		case 'return_received' :
			$content = 'return_received.php';
			$pageTitle = 'ส่งคืนสินค้า(ลดหนี้ซื้อ)';
			break;

		case 'order_transform' :
			$content	= 'order_transform.php';
			$pageTitle	= 'เบิกแปรสภาพ';
			break;

		case 'order_lend';
			$content = 'order_lend.php';
			$pageTitle = 'ยืมสินค้า';
			break;

		case 'move':
			$content = 'move.php';
			$pageTitle = 'ย้ายพื้นที่จัดเก็บ';
			break;

		case 'adjust':
			$content = 'adjust.php';
			$pageTitle = 'ปรับปรุงสต็อก';
			break;

		case 'transfer':
			$content = 'transfer.php';
			$pageTitle = 'โอนคลัง';
			break;

		case 'stock_movement' :
			$content = 'stock_movement.php';
			$pageTitle = 'ตรวจสอบความเคลื่อนไหวสินค้า';
			break;

		case 'buffer_zone' :
			$content = 'buffer_zone.php';
			$pageTitle = 'ตรวจสอบ Buffer Zone';
			break;

		case 'cancle_zone' :
			$content = 'cancle_zone.php';
			$pageTitle = 'ตรวจสอบ Cancle Zone';
			break;
		case 'stock' :
			$content = 'stock.php';
			$pageTitle = 'ตรวจสอบสต็อกสินค้า';
			break;

//**********  ระบบขาย  **********//
		case 'order':
			$content = 'order.php';
			$pageTitle= 'ออเดอร์';
			break;

		case 'order_online' :
			$content = 'order_online.php';
			$pageTitle = 'online Sale';
			break;

		case 'order_sponsor';
			$content = 'order_sponsor.php';
			$pageTitle = 'สปอนเซอร์สโมสร';
			break;

		case 'order_support' :
			$content = 'order_support.php';
			$pageTitle = 'เบิกอภินันทนาการ';
			break;

		case 'order_consign';
			$content = 'order_consign.php';
			$pageTitle = 'ฝากขาย [ใบกำกับภาษี]';
			break;

		case 'order_consignment';
			$content = 'order_consignment.php';
			$pageTitle = 'ฝากขาย [โอนคลัง]';
			break;

		case 'prepare':
			$content = 'prepare.php';
			$pageTitle = 'จัดสินค้า';
			break;

		case 'qc':
			$content = 'qc.php';
			$pageTitle = 'ตรวจสินค้า';
			break;

		case 'bill':
			$content = 'bill.php';
			$pageTitle = 'รายการรอเปิดบิล';
			break;

		case 'order_closed' :
			$content = 'order_closed.php';
			$pageTitle = 'รายการเปิดบิลแล้ว';
			break;

		case 'order_monitor' :
			$content = 'order_monitor.php';
			$pageTitle = 'ติดตามออเดอร์';
			break;

//**********  ระบบบัญชี  **********//

		case 'consign':
			$content = 'consign.php';
			$pageTitle = 'ตัดยอดฝากขาย';
			break;

		case 'consign_check' :
			$content = 'consign_check.php';
			$pageTitle = 'กระทบยอดสินค้าฝากขาย';
			break;

		case 'payment_order' :
			$content 	= 'payment_order.php';
			$pageTitle	= 'ตรวจสอบยอดชำระ';
			break;


//**********  ระบบซื้อ  **********//
		case 'po' :
			$content		= 'po.php';
			$pageTitle	= 'สั่งซื้อ';
			break;

//**********  รายงาน  **********//


//**********  การตั้งค่า  **********//
		case 'config';
			$content = 'setting.php';
			$pageTitle = 'การตั้งค่า';
			break;

		case 'popup' :
			$content = 'popup.php';
			$pageTitle = 'การแจ้งเตือน';
			break;

		case 'securable':
			$content = 'securable.php';
			$pageTitle = 'กำหนดสิทธิ์';
			break;

		case 'discount_policy' :
			$content = 'discount_policy.php';
			$pageTitle = 'เพิ่ม/แก้ไข นโยบายส่วนลด';
			break;

		case 'discount_rule' :
			$content = 'discount_rule.php';
			$pageTitle = 'เพิ่ม/แก้ไข เงื่อนไขส่วนลด';
			break;

//**********  ฐานข้อมูล  **********//
	//*****  สินค้า  *****//
		case 'product':
			$content = 'product.php';
			$pageTitle = 'รายการสินค้า';
			break;

		case 'style' :
			$content = 'style.php';
			$pageTitle = 'เพิ่ม/แก้ไข รุ่นสินค้า';
			break;

		case 'kind' :
			$content = 'product_kind.php';
			$pageTitle = 'เพิ่ม/แก้ไข ประเภทสินค้า';
			break;

		case 'type' :
			$content = 'product_type.php';
			$pageTitle = 'เพิ่ม/แก้ไข ชนิดสินค้า';
			break;

		case 'category':
			$content = 'product_category.php';
			$pageTitle = 'หมวดหมู่สินค้า';
			break;

		case 'product_group' :
			$content = 'product_group.php';
			$pageTitle = 'เพิ่ม/แก้ไข กลุ่มสินค้า';
			break;

		case 'product_sub_group' :
			$content = 'product_sub_group.php';
			$pageTitle = 'เพิ่ม/แก้ไข กลุ่มย่อยสินค้า';
			break;

		case 'product_tab' :
			$content = 'product_tab.php';
			$pageTitle = 'เพิ่ม/แก้ไข แถบแสดงสินค้า';
			break;

		case 'unit' :
			$content = 'unit.php';
			$pageTitle = 'หน่วยนับ';
			break;

		case 'color':
			$content = 'color.php';
			$pageTitle = 'เพิ่ม/แก้ไข สี';
			break;

		case 'color_group':
			$content = 'color_group.php';
			$pageTitle = 'กลุ่มสี';
			break;

		case 'size':
			$content = 'size.php';
			$pageTitle = 'เพิ่ม/แก้ไข ขนาดสินค้า';
			break;

		case 'brand' :
			$content 	= 'brand.php';
			$pageTitle = 'เพิ่ม/แก้ไข ยี่ห้อสินค้า';
			break;

		case 'barcode' :
			$content = 'barcode.php';
			$pageTitle = 'บาร์โค้ด';
			break;

	//*****  คลังสินค้า  *****//
		case 'warehouse':
			$content = 'warehouse.php';
			$pageTitle = 'เพิ่ม/แก้ไข คลังสินค้า';
			break;

		case 'zone':
			$content = 'zone.php';
			$pageTitle = 'เพิ่ม/แก้ไข โซนสินค้า';
			break;


	//*****  ลูกค้า  *****//
		case 'customer';
			$content='customer.php';
			$pageTitle='ข้อมูลลูกค้า';
			break;

		case 'customer_address':
			$content = 'customer_address.php';
			$pageTitle = 'เพิ่ม/แก้ไข ที่อยู่สำหรับจัดส่ง';
			break;

		case 'customer_group':
			$content = 'customer_group.php';
			$pageTitle = 'เพิ่ม/แก้ไข กลุ่มลูกค้า';
			break;

		case 'customer_kind':
			$content = 'customer_kind.php';
			$pageTitle = 'เพิ่ม/แก้ไข ประเภทลูกค้า';
			break;

		case 'customer_type':
			$content = 'customer_type.php';
			$pageTitle = 'เพิ่ม/แก้ไข ชนิดลูกค้า';
			break;

		case 'customer_class':
			$content = 'customer_class.php';
			$pageTitle = 'เพิ่ม/แก้ไข เกรดลูกค้า';
			break;

		case 'customer_area' :
			$content = 'customer_area.php';
			$pageTitle = 'เพิ่ม/แก้ไข เขตลูกค้า';
			break;

		case 'customer_credit' :
			$content = 'customer_credit.php';
			$pageTitle = 'วงเงินเครดิตคงเหลือ';
			break;


	//------- สปอนเซอร์ -----/
		case 'sponsor' :
			$content = 'sponsor.php';
			$pageTitle = 'เพิ่ม/แก้ไข รายชื่อสปอนเซอร์';
			break;

		case 'sponsor_approver' :
			$content = 'sponsor_approver.php';
			$pageTitle = 'เพิ่ม/แก้ไข ผู้อนุมัติงบสปอนเซอร์';
			break;

	//*****  พนักงาน  *****//
		case 'Employee':
			$content = 'employee.php';
			$pageTitle = 'พนักงาน';
			break;

		case 'sale_group' :
			$content = 'sale_group.php';
			$pageTitle = 'ทีมขาย';
			break;

		case 'sale';
			$content = 'sale.php';
			$pageTitle = 'พนักงานขาย';
			break;

		case 'Profile':
			$content = 'profile.php';
			$pageTitle = 'โปรไฟล์';
			break;

		case 'support' :
			$content = 'support.php';
			$pageTitle = 'เพิ่ม/แก้ไข รายชื่ออภินันทนาการ';
			break;

		case 'support_approver' :
			$content = 'support_approver.php';
			$pageTitle = 'เพิ่ม/แก้ไข ผู้อนุมัติงบอภินันทนาการ';
			break;

		//******** ผู้จำหน่าย  *******//
		case 'supplier' :
			$content 		= 'supplier.php';
			$pageTitle	= 'ผู้จำหน่าย';
		break;

		case 'supplier_group' :
			$content 		= 'supplier_group.php';
			$pageTitle	= 'กลุ่มผู้จำหน่าย';
			break;

	//*****  อื่นๆ  *****//
		case 'channels' :
			$content = 'channels.php';
			$pageTitle = 'ช่องทางการขาย';
			break;

		case 'payment_method' :
			$content = 'payment_method.php';
			$pageTitle = 'ช่องทางการชำระเงิน';
			break;

		case 'branch' :
			$content = 'branch.php';
			$pageTitle = 'สาขา';
			break;

		case 'sender' :
			$content = 'sender.php';
			$pageTitle = 'ผู้ให้บริการจัดส่ง';
			break;

		case 'transport' :
			$content = 'transport.php';
			$pageTitle = 'เพิ่มการจัดส่ง';
			break;

		case 'bank_account' :
			$content = 'bank_account.php';
			$pageTitle = 'บัญชีธนาคาร';
			break;


		//-------	รายงานระบบขาย
		case 'sale_deep_analyz' :
			$content = 'report/sales/sale_deep_analyz.php';
			$pageTitle = 'รายงานวิเคราะห์ขายแบบละเอียด';
			break;

		case 'sale_by_channels_show_reference' :
			$content = 'report/sales/sale_by_channels_show_reference.php';
			$pageTitle = 'รายงานยอดขายแยกตามช่องทางการขายแสดงเลขที่เอกสาร';
		break;

		case 'sale_online_channels_details' :
			$content = 'report/sales/sale_online_channels_details.php';
			$pageTitle = 'รายงาน ออเดอร์ออนไลน์ แสดงรายละเอียดการจัดส่ง (ไม่ใช่ยอดขาย)';
		break;

		case 'sale_by_customer_order' :
			$content = 'report/sales/sale_by_customer_order.php';
			$pageTitle = 'รายงาน สรุปยอดขายแยกลูกค้า แสดงเลขที่เอกสาร';
		break;

		case 'sale_by_customer_items' :
			$content = 'report/sales/sale_by_customer_items.php';
			$pageTitle = 'รายงาน สรุปยอดขายแยกลูกค้า แสดงรายการสินค้า';
		break;


		//---- รายงานตรวจสอบ
		case 'sponsor_by_customer_order' :
			$content = 'report/sales/sponsor_by_customer_order.php';
			$pageTitle = 'รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงเลขที่เอกสาร';
		break;

		case 'sponsor_summary_by_budget' :
			$content = 'report/sales/sponsor_summary_by_budget.php';
			$pageTitle = 'รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ';
		break;

		case 'sponsor_by_customer_items' :
			$content = 'report/sales/sponsor_by_customer_items.php';
			$pageTitle = 'รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงรายการสินค้า';
		break;

		case 'support_by_customer_order' :
			$content = 'report/sales/support_by_customer_order.php';
			$pageTitle = 'รายงาน สรุปยอดอภินันท์แยกตามผู้รับ แสดงเลขที่เอกสาร';
		break;

		case 'support_summary_by_budget' :
			$content = 'report/sales/support_summary_by_budget.php';
			$pageTitle = 'รายงาน สรุปยอดอภินันท์แยกตามผู้รับ';
		break;

		case 'support_by_customer_items' :
			$content = 'report/sales/support_by_customer_items.php';
			$pageTitle = 'รายงาน สรุปยอดอภินันท์แยกตามผู้รับ แสดงรายการสินค้า';
		break;

		case 'permission_by_employee' :
			$content = 'report/other/permission_by_employee.php';
			$pageTitle = 'รายงาน ตรวจสอบสิทธิ์แยกตามพนักงาน';
		break;


		//---------- รายงานติดตาม
		case 'lend_not_return' :
			$content = 'report/other/lend_not_return.php';
			$pageTitle = 'รายงาน การยืมสินค้ายังไม่ส่งคืน';
		break;

		//---------- รายงานระบบคลังสินค้า
		case 'stock_balance' :
			$content = 'report/stock/stock_balance.php';
			$pageTitle = 'รายงานสินค้าคงเหลือ';
			break;

		case 'current_stock' :
			$content = 'report/stock/current_stock.php';
			$pageTitle = 'รายงานสินค้าคงเหลือปัจจุบัน';
			break;

		case 'stock_balance_by_zone' :
			$content = 'report/stock/stock_balance_by_zone.php';
			$pageTitle = 'รายงานสินค้าคงเหลือแยกตามโซน';
			break;

		case 'stock_balance_compare_warehouse' :
			$content = 'report/stock/stock_balance_compare_warehouse.php';
			$pageTitle = 'รายงานสินค้าคงเหลือเปรียบเทียบคลัง';
			break;

		case 'stock_balance_by_warehouse' :
			$content = 'report/stock/stock_balance_by_warehouse.php';
			$pageTitle = 'รายงานสินค้าคงเหลือแยกตามคลัง';
		break;

		case 'received_by_document' :
			$content = 'report/stock/received_by_document.php';
			$pageTitle = 'รายงานการรับสินค้าแยกตามเลขที่เอกสาร';
			break;

		case 'received_transform_by_document' :
			$content = 'report/stock/received_transform_by_document.php';
			$pageTitle = 'รายงานการรับสินค้าแปรสภาพแยกตามเลขที่เอกสาร';
			break;

		case 'stock_movement_by_warehouse' :
			$content = 'report/stock/stock_movement_by_warehouse.php';
			$pageTitle = 'รายงานความเคลื่อนไหวสินค้าแยกตามคลังสินค้า';
			break;

		case 'stock_movement_by_product' :
			$content = 'report/stock/stock_movement_by_product.php';
			$pageTitle = 'รายงานความเคลื่อนไหวสินค้าแยกตามรายการสินค้า';
			break;

		case 'stock_not_move' :
			$content = 'report/stock/stock_not_move.php';
			$pageTitle = 'รายงานสินค้าไม่เคลื่อนไหว';
		break;

		case 'stock_year' :
			$content = 'report/stock/stock_year.php';
			$pageTitle = 'รายงานสินค้าคงเหลือแยกตามปีสินค้า';
		break;

		//---------- รายงานระบบซื้อ
		case 'po_backlog' :
			$content = 'report/purchase/po_backlog.php';
			$pageTitle = 'รายงานใบสั่งซื้อค้างรับ';
			break;

		case 'po_history_by_product' :
			$content = 'report/purchase/po_history_by_product.php';
			$pageTitle = 'รายงาน สรุปยอดการสั่งซื้อ ตามรุ่นสินค้า';
			break;

		case 'po_detail' :
			$content = 'report/purchase/po_detail.php';
			$pageTitle = 'รายงาน รายละเอียดใบสั่งซื้อ';
			break;

		default:
			$content = 'main.php';
			$pageTitle = 'Smart Inventory';
			break;




	}

	if( $viewStockOnly === TRUE )
	{
		$content = 'view_stock.php';
		$pageTitle = 'View Stock';
	}


	require_once 'template.php';
}
else
{
	require_once 'maintenance.php';
}
?>
