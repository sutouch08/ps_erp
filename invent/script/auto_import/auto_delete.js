$(document).ready(function() {
  syncData();
});


function syncData(){
  var step = [
    {'name' : 'Brand', 'url' : 'controller/fileController.php?clearFile&brand'},
    {'name' : 'Unit', 'url' : 'controller/fileController.php?clearFile&unit'},
    {'name' : 'PDGroup', 'url' : 'controller/fileController.php?clearFile&product_group'},
    {'name' : 'Color', 'url' : 'controller/fileController.php?clearFile&color'},
    {'name' : 'Size', 'url' : 'controller/fileController.php?clearFile&size'},
    {'name' : 'Style', 'url' : 'controller/fileController.php?clearFile&style'},
    {'name' : 'Product', 'url' : 'controller/fileController.php?clearFile&product'},
    {'name' : 'Barcode', 'url' : 'controller/fileController.php?clearFile&barcode'},
    {'name' : 'SaleGroup', 'url' : 'controller/fileController.php?clearFile&saleGroup'},
    {'name' : 'Sale', 'url' : 'controller/fileController.php?clearFile&sale'},
    {'name' : 'Area', 'url' : 'controller/fileController.php?clearFile&customerArea'},
    {'name' : 'CusGroup', 'url' : 'controller/fileController.php?clearFile&customerGroup'},
    {'name' : 'Customer', 'url' : 'controller/fileController.php?clearFile&customer'},
    {'name' : 'Credit', 'url' : 'controller/fileController.php?clearFile&customerCredit'},
    {'name' : 'SupGroup', 'url' : 'controller/fileController.php?clearFile&supplierGroup'},
    {'name' : 'Supplier', 'url' : 'controller/fileController.php?clearFile&supplier'},
    {'name' : 'Warehouse', 'url' : 'controller/fileController.php?clearFile&warehouse'},
    {'name' : 'PO', 'url' : 'controller/fileController.php?clearFile&po'},
    {'name' : 'BM', 'url' : 'controller/fileController.php?clearFile&BM'},
    {'name' : 'SM', 'url' : 'controller/fileController.php?clearFile&SM'},
    {'name' : 'SO', 'url' : 'controller/fileController.php?clearFile&AJ'},
    {'name' : 'SO', 'url' : 'controller/fileController.php?clearFile&BI'},
    {'name' : 'SO', 'url' : 'controller/fileController.php?clearFile&FR'},
    {'name' : 'SO', 'url' : 'controller/fileController.php?clearFile&SO'},
    {'name' : 'SO', 'url' : 'controller/fileController.php?clearFile&TR'},
    {'name' : 'SO', 'url' : 'controller/fileController.php?clearFile&WR'}
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
