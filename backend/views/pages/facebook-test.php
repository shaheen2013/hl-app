<!DOCTYPE html>
<?php include_once  LIB . 'facebook_iframe_dialog_NEW.php'; ?>
<html>
<head>
  <title>Example</title>
</head>
<body>
<?php echo (isset($loginUrl) ? '<a href="' . htmlspecialchars($loginUrl) . '" target="_self">Log in with Facebook!</a>': '') ; ?>
</body>
</html>