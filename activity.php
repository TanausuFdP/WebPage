<?php
include_once 'presentation.class.php';
include_once 'business.class.php';

$activities = DB::execute_sql("SELECT * FROM actividades WHERE id={$_REQUEST['id']};");
$activity = $activities->fetch();
$user = User::getLoggedUser();

if(array_key_exists("cantidad", $_REQUEST) && $_REQUEST['cantidad'] > 0){
	$sql = "INSERT INTO [tickets] ([idcliente], [idactividad], [precio], [unidades])
    VALUES ({$user['id']}, {$activity['id']}, {$activity['precio']}, {$_REQUEST['cantidad']});";
    DB::execute_sql($sql);
}

View::start("GCActiva - {$activity['nombre']}");
View::topBar(0);

$inicio = date("d-m-Y H:i:s", "{$activity['inicio']}");
$duracion = $activity['inicio'] + $activity['duracion'];
$final = date("d-m-Y H:i:s", "$duracion");
$imagen = View::imgtobase64($activity['imagen']);

echo "<h2 class=\"activityName\">{$activity['nombre']}</h2>
<img class=\"activityImage\" src='$imagen'>
<p class=\"base\">Tipo:</p>
<p class=\"info\">{$activity['tipo']}</p>
<p class=\"base\">Descripción:</p>
<p class=\"info\">{$activity['descripcion']}</p>
<p class=\"base\">Precio:</p>
<p class=\"info\">{$activity['precio']}€/ticket</p>
<p class=\"base\">Aforo:</p>
<p class=\"info\">{$activity['aforo']}</p>
<p class=\"base\">Fecha:</p>
<p class=\"info\">De $inicio a $final</p>";

if($user['tipo'] == 2 && $activity['idempresa'] == $user['id']){
	echo "<form action=\"modify.php\" method=\"post\">
	<input type=\"hidden\" name=\"id\" value=\"{$activity['id']}\">
	<input type=\"submit\" value=\"Modificar\">
	</form>"; 
}

if($user['tipo'] == 3){
	$SQLtickets = DB::execute_sql("SELECT * FROM tickets WHERE idactividad={$activity['id']};");
	$tickets = $SQLtickets->fetchAll();
	$sold = 0;
	foreach($tickets as $ticket){
		$sold += $ticket['unidades'];
	}
	$available = $activity['aforo'] - $sold;

	if($available > 0){
		echo "<p>Quedan $available tickets disponibles. </p>
		<h3>Comprar tickets</h3>
		<form action=\"activity.php\" method=\"post\">
		<input type=\"hidden\" name=\"id\" value=\"{$activity['id']}\">
		<input type=\"number\" name=\"cantidad\" value=\"0\" min=\"0\" max=\"$available\">
		<input type=\"submit\" value=\"Comprar\">
		</form>";
	}
}

View::end();