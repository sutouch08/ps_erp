function goBack(){
  window.location.href = 'index.php?content=discount_rule';
}


function goAdd(id){
  if(id === undefined){
    window.location.href = 'index.php?content=discount_rule&add=Y';
  }else{
    window.location.href = 'index.php?content=discount_rule&add=Y&id_rule='+id;
  }
}


function goEdit(id){
  window.location.href = 'index.php?content=discount_rule&add=Y&id_rule='+id;
}


function viewDetail(id){
  window.location.href = 'index.php?content=discount_rule&viewDetail&id_rule='+id;
}
