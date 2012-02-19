<!DOCTYPE html>
<html lang='ja'>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
  require_once 'php/init.php';
  require_once 'php/passport.php';
?>
  <title>Login | <?php echo SITE_NAME; ?></title>
	<link href="styles/bootstrap.min.css" rel="stylesheet" />
	<link href="styles/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="styles/user.css" rel="stylesheet" />
</head>
<body>
  <div class="container">

    <header id="page-header">
      <h1><?php echo SITE_NAME; ?></h1>
    </header>

    <section class="row" id="login-form">
      <div class="span12">
        <h1>Login</h1>
        <form method="POST" action="">
          <input type="text" name="uname" id="uname" placeholder="Username" />
          <input type="password" name="password" id="password" placeholder="Password" />
          <input type="submit" name="login" value="Login" class="btn-info" />
        </form>
      </div>
    </section>
    
    <?php if (ALLOW_NEW_USER) { ?>
      <section class="row" id="signup-form">
        <div class="span12">
          <h1>
            New to <?php echo SITE_NAME; ?>?
            <small>Sign up</small>
          </h1>
          <form method="POST" action="">
            <input type="text" name="new-uname" id="new-uname" placeholder="Username" />
            <input type="password" name="new-password" id="new-password" placeholder="Password" />
            <input type="submit" name="signup" value="Sign up for <?php echo SITE_NAME; ?>" class="btn-warning" />
          </form>
        </div>
      </section>
    <?php } ?>

    <footer id="page-footer">
      <?php echo '&copy; '.date('Y').' '.SITE_NAME; ?>
    </footer>

  </div>
	
	<!-- JavaScript Area -->
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!-- JavaScript Area -->
  <!-- Do not write anything below this. -->
</body>
</html>
