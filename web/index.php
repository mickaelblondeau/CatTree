<?php include '../src/core.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Cats!</title>
	<link rel="stylesheet" href="css/main.css" />
</head>
<body>
<svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="<?= $view['w'] ?>" height="<?= $view['h'] ?>" viewBox="0 0 <?= $view['w'] ?> <?= $view['h'] ?>">
	<?= $view['dates'] ?>
	<?= $view['tree'] ?>
</svg>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>