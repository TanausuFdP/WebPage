<?php
include_once 'presentation.class.php';
include_once 'business.class.php';

View::start('Acceder - GCActiva');
View::topBar(3);

$class='login';
if(array_key_exists('userName', $_REQUEST)){
	if(User::login($_REQUEST['userName'], $_REQUEST['userPassword'])){
		header("Location: user.php");
		exit();
	} else {
		echo "<p class=\"error\"> Error: Nombre de usuario y/o contraseña incorrectos. </p>";
		$class='login2';
	}
}

echo "<div class=\"$class\">
<h2>Iniciar sesión</h2>
<form action=\"login.php\" method=\"post\">
<input type=\"text\" name=\"userName\">
<br>
<input type=\"password\" name=\"userPassword\">
<br>
<input type=\"submit\" value=\"Acceder\">
</form>
</div>";

View::end();