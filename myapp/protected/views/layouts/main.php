<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">
	<div class="header"><a href="<?php echo Yii::app()->request->baseUrl; ?>">Dennis Site</a></div>
	<div style="padding: 10px 20px;">
		<?php echo $content; ?>
	</div>
</div>

</body>
</html>