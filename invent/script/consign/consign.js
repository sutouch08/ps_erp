function goBack(){
  window.location.href = 'index.php?content=consign';
}


function goAdd(id){
  if( id === undefined){
    var link = 'index.php?content=consign&add=Y';
  }else{
    var link = 'index.php?content=consign&add=Y&id_consign='+id;
  }

  window.location.href = link;
}


function goDetail(id){
  window.location.href = 'index.php?content=consign&view_detail=Y&id_consign='+id;
}




function exportConsignSold(id){
  load_in();
  $.ajax({
    url:'controller/interfaceController.php?export&consignSold',
    type:'POST',
    cache:'false',
    data:{
      'id_consign' : id
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Saved',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          goDetail(id);
        }, 1200);
      }
    }
  });
}
