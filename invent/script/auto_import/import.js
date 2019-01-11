var startSync;
var endSync;
var month = ['01','02','03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];


function syncData(){
  var step = [
    {'name' : 'Brand', 'el' : 'pdBrand', 'url' : 'controller/interfaceController.php?syncMaster&brand'},
    {'name' : 'Unit', 'el' : 'pdUnit', 'url' : 'controller/interfaceController.php?syncMaster&unit'},
    {'name' : 'PDGroup', 'el' : 'pdGroup', 'url' : 'controller/interfaceController.php?syncMaster&product_group'},
    {'name' : 'Color', 'el' : 'pdColor', 'url' : 'controller/interfaceController.php?syncMaster&color'},
    {'name' : 'Size', 'el' : 'pdSize', 'url' : 'controller/interfaceController.php?syncMaster&size'},
    {'name' : 'Style', 'el' : 'pdStyle', 'url' : 'controller/interfaceController.php?syncMaster&style'},
    {'name' : 'Product', 'el' : 'product', 'url' : 'controller/interfaceController.php?syncMaster&product'},
    {'name' : 'Barcode', 'el' : 'pdBarcode', 'url' : 'controller/interfaceController.php?syncMaster&barcode'},
    {'name' : 'SaleGroup', 'el' : 'saleGroup', 'url' : 'controller/interfaceController.php?syncMaster&saleGroup'},
    {'name' : 'Sale', 'el' : 'saleMan', 'url' : 'controller/interfaceController.php?syncMaster&sale'},
    {'name' : 'Area', 'el' : 'cusArea', 'url' : 'controller/interfaceController.php?syncMaster&customerArea'},
    {'name' : 'CusGroup', 'el' : 'cusGroup', 'url' : 'controller/interfaceController.php?syncMaster&customerGroup'},
    {'name' : 'Customer', 'el' : 'customer', 'url' : 'controller/interfaceController.php?syncMaster&customer'},
    {'name' : 'Credit', 'el' : 'cusCredit', 'url' : 'controller/interfaceController.php?syncMaster&customerCredit'},
    {'name' : 'SupGroup', 'el' : 'supGroup', 'url' : 'controller/interfaceController.php?syncMaster&supplierGroup'},
    {'name' : 'Supplier', 'el' : 'supplier', 'url' : 'controller/interfaceController.php?syncMaster&supplier'},
    {'name' : 'Warehouse', 'el' : 'warehouse', 'url' : 'controller/interfaceController.php?syncMaster&warehouse'},
    {'name' : 'PO', 'el' : 'po', 'url' : 'controller/interfaceController.php?syncDocument&po'},
    {'name' : 'BM', 'el' : 'bm', 'url' : 'controller/interfaceController.php?syncDocument&BM'},
    {'name' : 'SM', 'el' : 'sm', 'url' : 'controller/interfaceController.php?syncDocument&SM'}
  ];
  resetLabel();
  setTimeout(function(){
    startlog();
    importData(step, 0);
  }, 100);
}


function importData(step, index){
  var ds = step[index];
  var el = ds.el;
  index++;
  changeStatus(el, 1);
  $.ajax({
    url: ds.url,
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        changeStatus(el, 2);
      }else{
        changeStatus(el, rs);
      }

      if(index == step.length){
        addlog();
        window.close();
      }else{
        importData(step, index);
      }
    }
  });
}



function resetLabel(){
  $('.result').each(function(index, el) {
    $(this).text('รอดำเนินการ');
  });
}


function getTimeLog(){
  var date = new Date();
  var times = date.getFullYear() + '-'+ month[date.getMonth()] + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
  return times;
}


function startlog(){
  startSync = getTimeLog();
}

function endlog(){
  endSync = getTimeLog();
}


function addlog(){
  endlog();
  var syncLog = '<tr><td> start sync @ '+ startSync + '  End sync @ ' + endSync +'</td></tr>';
  $('#result-table').prepend(syncLog);
}



function changeStatus(el, status){
  var label = '';

  if( status == 1){
    label = '<span class="blue">กำลังนำเข้า</span>';
  }else if(status == 2){
    label = '<span class="green">สำเร็จ</span>';
  }else{
    label = '<span class="red">'+status+'</span>';
  }

  $('#'+el).html(label);
}
