$(document).ready(function() {
  syncData();
});


function syncData(){
  var step = [
    {'name' : 'Brand', 'url' : 'controller/interfaceController.php?syncMaster&brand'},
    {'name' : 'Unit', 'url' : 'controller/interfaceController.php?syncMaster&unit'},
    {'name' : 'PDGroup', 'url' : 'controller/interfaceController.php?syncMaster&product_group'},
    {'name' : 'Color', 'url' : 'controller/interfaceController.php?syncMaster&color'},
    {'name' : 'Size', 'url' : 'controller/interfaceController.php?syncMaster&size'},
    {'name' : 'Style', 'url' : 'controller/interfaceController.php?syncMaster&style'},
    {'name' : 'Product', 'url' : 'controller/interfaceController.php?syncMaster&product'},
    {'name' : 'Barcode', 'url' : 'controller/interfaceController.php?syncMaster&barcode'},
    {'name' : 'SaleGroup', 'url' : 'controller/interfaceController.php?syncMaster&saleGroup'},
    {'name' : 'Sale', 'url' : 'controller/interfaceController.php?syncMaster&sale'},
    {'name' : 'Area', 'url' : 'controller/interfaceController.php?syncMaster&customerArea'},
    {'name' : 'CusGroup', 'url' : 'controller/interfaceController.php?syncMaster&customerGroup'},
    {'name' : 'Customer', 'url' : 'controller/interfaceController.php?syncMaster&customer'},
    {'name' : 'Credit', 'url' : 'controller/interfaceController.php?syncMaster&customerCredit'},
    {'name' : 'SupGroup', 'url' : 'controller/interfaceController.php?syncMaster&supplierGroup'},
    {'name' : 'Supplier', 'url' : 'controller/interfaceController.php?syncMaster&supplier'},
    {'name' : 'Warehouse', 'url' : 'controller/interfaceController.php?syncMaster&warehouse'},
    {'name' : 'PO', 'url' : 'controller/interfaceController.php?syncDocument&po'},
    {'name' : 'BM', 'url' : 'controller/interfaceController.php?syncDocument&BM'},
    {'name' : 'SM', 'url' : 'controller/interfaceController.php?syncDocument&SM'}
  ];

  setTimeout(function(){
    importData(step, 0);
  }, 100);
}


function importData(step, index){
  var ds = step[index];

  $.ajax({
    url: ds.url,
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      $('body').append('import : '+ds.name+' => '+rs+'<br/>');
      if(index == (step.length)){
        window.close();
      }else{
        importData(step, index);
      }
    }
  });
index++;
}
