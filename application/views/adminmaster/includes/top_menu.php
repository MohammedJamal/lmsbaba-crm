<?php  $nav_controller_name=$this->router->fetch_class(); ?>
<a href="<?php echo adminportal_url(); ?>logout" class="active">Log Out</a>
<div id="myLinks" style="display: block;">
    <!-- <a href="<?php echo adminportal_url(); ?>dashboard">Dashboard</a>    -->

    <?php if(count($topmenu_list)){
        foreach($topmenu_list AS $menu){ ?> 
    <a href="<?php echo adminportal_url().$menu['controller_name']; ?>" <?php if($nav_controller_name==$menu['controller_name']) echo'class="topnav_active"'; ?>><?php echo $menu['menu_name']; ?></a>
    <?php   }
    } ?>

    <!-- <a href="<?php echo adminportal_url(); ?>client">Client List</a>
    <a href="<?php echo adminportal_url(); ?>inactive_clients">Inactive Clients</a>
    <a href="<?php echo adminportal_url(); ?>renewal">Renewal</a>
    <a href="<?php echo adminportal_url(); ?>users">Users</a>
    <a href="<?php echo adminportal_url(); ?>downloads">Downloads</a> -->
</div>
<!-- <a href="javascript:void(0);" class="icon" onclick="menu_toggle()"><i class="fa fa-bars"></i></a> -->

<style>
    .topnav_active {
        color: #000 !important;
        background-color: #F0F0F0;
    }
</style>