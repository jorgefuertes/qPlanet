<?php
# Stripping out the 'www.' if present:
$domain = preg_replace("/^www\./", "", $_SERVER['HTTP_HOST']);
# Nos aseguramos de que esté en minúsculas:
$domain = strtolower($domain);
?>
<html>
<head>
	<title><?php echo $domain; ?></title>
</head>
<body style='color: #ffffff; background-color: #000000'>
	<div style='margin: auto; color: #efefef; background-color: #222222; -moz-border-radius: 12px;
			text-align: center; padding: 15px; width: 450px; margin-top: 10%;'>
		<h1><?php echo $domain; ?></h1>
		<img src='/mantenimiento/mantenimiento.jpg' alt='Mantenimiento' />
		<h3>Estamos trabajando en ello.</h3>
		<h3><a href='/mantenimiento/aznar_tejano.wav'><img style='border: 0px;' src='/mantenimiento/play.png' /></a></h3>
	</div>
</body>
</html>
