<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=$title?> - Test Mode: <?=$module?></title>
  <?= isset($css) ? $css : null ?>
</head>
<body>
  <?= $content?>
  <?= isset($js) ? $js : null ?>
  <?= isset($js_scripts) ? "<script type='text/javascript'>".$js_scripts."</script>" : null ?>
</body>
</html>