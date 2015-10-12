<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $this->getVar('title'); ?></title>
		<?php foreach ($this->getCss() as $css){ ?>
		<link rel="stylesheet" type="text/css" href="<?php FormHelper::encodeAttribute($css['href']) ?>" media="<?php FormHelper::encodeAttribute($css['media']) ?>">
		<?php } ?>
		<?php foreach ($this->getJs() as $js){ ?>
		<script type="text/javascript" src="<?php FormHelper::encodeAttribute($js) ?>"></script>
		<?php } ?>
		<script type="text/javascript"><?php echo $this->getReadyJs(); ?></script>
	</head>
	<body>
		<?php $this->getChild('body')->render(); ?>
	</body>
</html>