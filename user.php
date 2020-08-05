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

echo "<h2>Hola, {$user['nombre']}</h2>
<h4>Tu información:</h4>
<p>Correo: {$user['email']}</p>
<p>Lugar de residencia: {$user['poblacion']}</p>
<p>Dirección: {$user['direccion']}</p>
<p>Teléfono de contacto: {$user['telefono']}";

if($user['tipo'] == 2){
	$id = $user['id'];
	$empresas = DB::execute_sql("SELECT * FROM EMPRESAS WHERE IDEMPRESA = '$id'");
	foreach($empresas as $empresa){
		echo "<p>Descripción de la empresa: {$empresa['descripcion']}</p>
		<p>Contacto de la empresa: {$empresa['contacto']}</p>";
	}
	$actividades = DB::execute_sql("SELECT * FROM actividades WHERE idempresa = '$id'");
	echo "<h3>Sus actividades</h3>
	<table>
	<tr>
	<th></th>
	<th>Nombre</th>
	<th>Tipo</th>
	<th>Precio</th>
	<th>Fecha</th>
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
	<form action=\"modify.php\" method=\"post\">
	<input type=\"submit\" value=\"Nueva actividad\">
	</form>";
}

echo "<form action=\"user.php\">
<input type=\"submit\" name=\"logout\" value=\"Salir\">
</form>";

View::end();