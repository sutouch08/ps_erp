<?php
require '../../library/config.php';
require '../../library/functions.php';
require "../function/tools.php";

///============================================= MASTER ==========================================///
//---------  Sync Product Group -------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['product_group'] ) )
{
	include "interface/clearFile/clearFileProductGroup.php";
}


//----------- Sync Unit ------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['unit'] ) )
{
	include "interface/clearFile/clearFileUnit.php";
}


//------------ Barcode --------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['barcode'] ) )
{
	include "interface/clearFile/clearFileBarcode.php";

}


//------------- Warehouse ---------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['warehouse'] ) )
{
	include "interface/clearFile/clearFileWarehouse.php";

}


//--------------------- Customer Group ------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['customerGroup'] ) )
{
	include "interface/clearFile/clearFileCustomerGroup.php";

}


//---------------------- Customer Area -------------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['customerArea'] ) )
{
	include "interface/clearFile/clearFileCustomerArea.php";

}


//----------------- Customer --------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['customer'] ) )
{
	include "interface/clearFile/clearFileCustomer.php";

}


//------------- Sale Team -----------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['saleGroup'] ) )
{
	include "interface/clearFile/clearFileSaleGroup.php";

}


//---------------  Sale  --------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['sale'] ) )
{
	include "interface/clearFile/clearFileSale.php";

}


//--------------  Supplier Group  -----------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['supplierGroup'] ) )
{
	include "interface/clearFile/clearFileSupplierGroup.php";

}


//------------------  Supplier  -----------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['supplier'] ) )
{
	include "interface/clearFile/clearFileSupplier.php";

}


//----------------- Product Style ------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['style'] ) )
{
	include "interface/clearFile/clearFileStyle.php";

}



//----------------- Product Color ------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['color'] ) )
{
	include "interface/clearFile/clearFileColor.php";

}



//----------------- Product Size ------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['size'] ) )
{
	include "interface/clearFile/clearFileSize.php";

}


//--------------- Product Brand ----------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['brand'] ) )
{
	include "interface/clearFile/clearFileBrand.php";

}


//---------------- Products -------------------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['product'] ) )
{
	include "interface/clearFile/clearFileProduct.php";

}


//---------------- Customer Credit -------------------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['customerCredit'] ) )
{
	include "interface/clearFile/clearFileCustomerCredit.php";

}



///==================================================== END MASTER ==========================================///


///=================================================== DOCUMENTS ==========================================///
if( isset( $_GET['clearFile'] ) && isset( $_GET['po'] ) )
{
	include "interface/clearFile/clearFilePO.php";

}


if( isset( $_GET['clearFile'] ) && isset( $_GET['SM'] ) )
{
	include 'interface/clearFile/clearFileSM.php';

}



if( isset( $_GET['clearFile'] ) && isset( $_GET['BM'] ) )
{
	include 'interface/clearFile/clearFileBM.php';

}


///=================================================== END DOCUMENTS =======================================///



//-------------------------- Clear Export Files ------------------------------//
if( isset( $_GET['clearFile'] ) && isset( $_GET['AJ'] ) )
{
	include 'interface/clearFile/clearFileAJ.php';

}


if( isset( $_GET['clearFile'] ) && isset( $_GET['BI'] ) )
{
	include 'interface/clearFile/clearFileBI.php';

}

if( isset( $_GET['clearFile'] ) && isset( $_GET['FR'] ) )
{
	include 'interface/clearFile/clearFileFR.php';

}


if( isset( $_GET['clearFile'] ) && isset( $_GET['SO'] ) )
{
	include 'interface/clearFile/clearFileSO.php';

}


if( isset( $_GET['clearFile'] ) && isset( $_GET['TR'] ) )
{
	include 'interface/clearFile/clearFileTR.php';

}


if( isset( $_GET['clearFile'] ) && isset( $_GET['WR'] ) )
{
	include 'interface/clearFile/clearFileWR.php';

}

?>
