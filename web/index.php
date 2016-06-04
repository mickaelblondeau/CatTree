<?php include '../lib/core.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<title>Cats!</title>
	<link rel="stylesheet" href="css/main.css" />
</head>
<body>
	<svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<?php echo CatManager::getInstance()->renderDates() ?>
		<?php echo CatManager::getInstance()->renderTree() ?>
	</svg>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>