<!DOCTYPE html>

<html lang="en" ng-app="myapp">
  <head>

    <meta charset="utf-8">
     <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mojo Scribe - Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('assets/admin/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- <link href="<?php echo base_url(); ?>css/bootswatch.min.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="<?php echo base_url('assets/admin/font-awesome/css/font-awesome.min.css');?>">    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.dataTables.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ng-table/ng-table.min.css">

    
    <?php if(isset($links)) {
    foreach($links as $link) { ?>
      <link href="<?php echo base_url('assets/css/' . $link); ?>" rel="stylesheet"></link>
<?php }
    }
?>
  <style>
    .error-class {

    border: 1px solid;
    border-color: #a94442;
    }
  </style>

  </head>

  <body>
  <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="<?php echo base_url('admin/home'); ?>" class="navbar-brand">MojoScribe</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <?php  if(!isset($login)){ ?>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li>
              <a href="<?php echo base_url('admin/users'); ?>">Users</a>
            </li>
            <li>
              <a href="<?php echo base_url('admin/featured'); ?>">Featured</a>
            </li>
            <li>
            	<a href="<?php echo base_url('admin/poll'); ?>"> Poll </a>
            </li>
            <li>
            	<a href="<?php echo base_url('admin/feedback'); ?>"> Feedback </a>
            </li>
            <li>
              <a href="<?php echo base_url('admin/categories'); ?>"> Categories </a>
            </li>
            <li>
              <a href="<?php echo base_url('admin/impacts'); ?>"> Impacts </a>
            </li>
            <li>
              <a href="<?php echo base_url('admin/anonymous'); ?>"> Anonymous Posts </a>
            </li>

            <li>
              <a href="<?php echo base_url('admin/flagged'); ?>"> Flagged Posts </a>
            </li>

            <li>
              <a href="<?php echo base_url('admin/allPosts'); ?>"> All Posts </a>
            </li>

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download"><?php echo $_SESSION['adminUserName']; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="download">
                <li><a href="<?php echo base_url('admin/logout'); ?>">Logout </a></li>
              </ul>
            </li>
          </ul>

        </div>
        <?php } ?>
      </div>
    </div>
      <!-- Sidebar -->
