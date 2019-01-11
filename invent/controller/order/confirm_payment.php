<?php
    require "../function/bank_helper.php";
        $sc 			= 'fail';
        $file 			= isset( $_FILES['image'] ) ? $_FILES['image'] : FALSE;
        $id_order 		= $_POST['id_order'];
        $id_acc			= $_POST['id_account'];
        $accNo			= getAccountNo($id_acc);
        $date			= $_POST['payDate'];
        $h				= $_POST['payHour'];
        $m				= $_POST['payMin'];
        $dhm			= $date .' '.$h.':'.$m.':00';
        $payDate		= dbDate($dhm, TRUE);
        $date_add 		= date('Y-m-d H:i:s');
        $order			= new order($id_order);
        
        $id_emp			= getCookie('user_id');
        //-------  บันทึกรายการ -----//
        $payment = new payment(); 
        $arr = array(
                    "id_order"	=> $order->id,
                    "order_amount"	=> $_POST['orderAmount'],
                    "pay_amount"	=> $_POST['payAmount'],
                    "paydate"	=> $payDate,
                    "id_account"	=> $id_acc,
                    "acc_no"		=> $accNo,
                    "id_employee"	=> $id_emp,
                    "date_add"	=> $date_add
                );
        
        if( $payment->isExists($order->id) === FALSE )
        {
            $cs = $payment->add($arr);
        }
        else
        {
            $cs = $payment->update($order->id, $arr);	
        }
        
        if( $cs )
        {
            $order->stateChange($order->id, 2); //--- แจ้งชำระเงิน
            $sc = 'success';
        }
        
        //----- Upload image -----//	
        if( $file !== FALSE )
        {	
            $image_path 	= "../../img/payment/";
            $image 			= new upload($file);
            if($image->uploaded)
            {
                $image->file_new_name_body	= $order->reference;
                $image->file_overwrite 			= TRUE;
                $image->auto_create_dir 		= FALSE;
                $image->image_convert 			= "jpg";
                $image->process($image_path);
                if( ! $image->processed)
                {
                    $sc = $image->error;
                }
            }
            $image->clean();
        }
        echo $sc;

?>