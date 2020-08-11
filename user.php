<?php
include_once 'presentation.class.php';
include_once 'business.class.php';

if(array_key_exists('logout', $_REQUEST)){
	User::logout();
	header('Location: index.php');
	exit();
}

$user = User::getLoggedUser();

View::start("{$user['cuenta']} - GCActiva");
View::topBar(3);

if($user['tipo'] == 2){
	$id = $user['id'];
	$empresas = DB::execute_sql("SELECT * FROM EMPRESAS WHERE IDEMPRESA = '$id'");
	$empresa = $empresas->fetch();
	$imagen = View::imgtobase64($empresa['logo']);
	echo "<img class=\"logo\" src=\"$imagen\">";
} else {
	echo "<img class=\"logo\" src=\"Imágenes/perfil.png\">";
}

echo "<h2 class=\"saludo\">Hola, {$user['nombre']}</h2>
<h4>Tu información:</h4>
<p class=\"base\">Correo:</p>
<p class=\"info\">{$user['email']}</p>
<p class=\"base\">Lugar de residencia:</p>
<p class=\"info\">{$user['poblacion']}</p>
<p class=\"base\">Dirección:</p>
<p class=\"info\">{$user['direccion']}</p>
<p class=\"base\">Teléfono de contacto:</p>
<p class=\"info\">{$user['telefono']}";

if($user['tipo'] == 2){
	echo "<p class=\"base\">Descripción de la empresa:</p>
	<p class=\"info\">{$empresa['descripcion']}</p>
	<p class=\"base\">Contacto de la empresa:</p>
	<p class=\"info\">{$empresa['contacto']}</p>";
	$actividades = DB::execute_sql("SELECT * FROM actividades WHERE idempresa = '$id'");
	echo "<h3>Tus actividades</h3>
	<table class=\"userActivities\">
	<tr>
	<th></th>
	<th>Nombre</th>
	<th>Tipo</th>
	<th>Precio</th>
	<th>Fecha</th>
	<th></th>
	</tr>";
	foreach($actividades as $actividad){
		$fecha = date("d-m-Y H:i:s", "{$actividad['inicio']}");
		$imagen = View::imgtobase64($actividad['imagen']);
		echo "<tr onclick=\"window.location.href = 'activity.php?id={$actividad['id']}';\" id=\"row{$actividad['id']}\">
		<td><img src='$imagen'></td>
		<td>{$actividad['nombre']}</td>
		<td>{$actividad['tipo']}</td>
		<td>{$actividad['precio']}</td>
		<td>$fecha</td>
		<td><button onclick=\"deleteActivity({$actividad['id']}, '{$actividad['nombre']}')\">Borrar</button></td>
		</tr>";
	}
	echo "</table>
	<form class=\"nueva\" action=\"modify.php\" method=\"post\">
	<input type=\"submit\" value=\"Nueva actividad\">
	</form>";
}

echo "<form class=\"salir\" action=\"user.php\">
<input type=\"submit\" name=\"logout\" value=\"Salir\">
</form>";

View::end();