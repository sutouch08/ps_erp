<!-- Fixed navbar start -->
<?php
$id_sale = getCookie('sale_id');
$id_emp = getCookie('user_id');
?>
<input type="hidden" id="id_sale" value="<?php echo $id_sale; ?>" />
<input type="hidden" id="id_employee" value="<?php echo $id_emp; ?>" />

<div class="navbar navbar-tshop navbar-fixed-top" role="navigation" style="min-height:30px;">
  <div class="container">
    <div class="navbar-header">

      <ul class="nav navbar-nav">
        <li style="display:inline; float:left;">
          <a href="index.php" style="padding:5px; margin-left:25px; color:#fff;"><i class="fa fa-home fa-2x white"></i></a>
        </li>
        <li class='dropdown' style="display:inline; float:right;">
          <a class='dropdown-toggle' style='color:#FFF; background-color:transparent; display:inline;' data-toggle='dropdown' href='#'>
            <button type="button" class="navbar-toggle" style="float:right; padding:0px; margin-right:20px;">
              <span class="icon-bar"> </span>
              <span class="icon-bar"> </span>
              <span class="icon-bar"> </span>
            </button>
            <span style="line-height:2.5">
            <?php echo getCookie('saleName'); ?>
            </span>

          </a>
          <ul class='dropdown-menu dropdown-user'>
            <li class="text-right" style="padding-right:10px;">
              <a href='index.php?logout'><i class='fa fa-sign-out fa-fw'></i> Logout</a>
            </li>
          </ul>
        <!-- /.dropdown-user -->
        </li>
              <!-- /.dropdown -->
      </ul>
    </div>
  </div>
</div>
