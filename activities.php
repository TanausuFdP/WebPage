<?php
include_once 'presentation.class.php';

View::start('Actividades - GCActiva');
View::topBar(2);

echo "<div class=\"buscar\">
<h2>Buscar actividades</h2>
<form action=\"activities.php\" method=\"get\">
<input class=\"inputText\" type=\"text\" name=\"activitySearch\">
<select name=\"selection\">
<option value=\"name\">Nombre</option>
<option value=\"type\">Tipo</option>
<option value=\"date\">Fecha</option>
<br>
<input class=\"inputSubmit\" type=\"submit\" value=\"Buscar\">
</form>
</div>";

$activities = DB::execute_sql('SELECT * FROM ACTIVIDADES ORDER BY nombre');
$rows = DB::execute_sql('SELECT COUNT(*) FROM ACTIVIDADES');

if(array_key_exists("activitySearch", $_REQUEST)){
	$value = $_REQUEST['activitySearch'];
	if($_REQUEST['selection'] == 'name'){
		$activities = DB::execute_sql("SELECT * FROM ACTIVIDADES WHERE nombre LIKE '%$value%' ORDER BY nombre");
		$rows = DB::execute_sql("SELECT COUNT(*) FROM ACTIVIDADES WHERE nombre LIKE '%$value%'");
	} else if($_REQUEST['selection'] == 'type'){
		$activities = DB::execute_sql("SELECT * FROM ACTIVIDADES WHERE tipo LIKE '%$value%' ORDER BY tipo");
		$rows = DB::execute_sql("SELECT COUNT(*) FROM ACTIVIDADES WHERE tipo LIKE '%$value%'");
	} else {
		$dateArray = preg_split('/[-\/]/', $value);
		if(count($dateArray) == 3){
			$date = $dateArray[0] . '-' . $dateArray[1] . '-' . $dateArray[2];
			$ini = strtotime($date);
			$next = $dateArray[0] + 1;
			$date = $next . '-' . $dateArray[1] . '-' . $dateArray[2];
			$fin = strtotime($date);
			$activities = DB::execute_sql("SELECT * FROM ACTIVIDADES WHERE inicio > '$ini' AND inicio < '$fin' ORDER BY inicio");
			$rows = DB::execute_sql("SELECT COUNT(*) FROM ACTIVIDADES WHERE inicio > '$ini' AND inicio < '$fin'");
		} else {
			echo "<p>La fecha introducida es incorrecta</p>";
		}
	}
}

if($rows->fetchColumn() < 1){
	echo "<p>No se han encontrado actividades con los parámetros buscados.</p>";
	$activities = DB::execute_sql('SELECT * FROM ACTIVIDADES ORDER BY nombre');
}

echo "<table class=\"activities\">
<tr>
<th></th>
<th>Nombre</th>
<th>Tipo</th>
<th>Precio</th>
<th>Fecha</th>
</tr>";

foreach($activities as $activity){
	$fecha = date("d-m-Y H:i:s", "{$activity['inicio']}");
	$imagen = View::imgtobase64($activity['imagen']);
	echo "<tr onclick=\"window.location.href = 'activity.php?id={$activity['id']}';\">
	<td><img src='$imagen'></td>
	<td>{$activity['nombre']}</td>
	<td>{$activity['tipo']}</td>
	<td>{$activity['precio']}</td>
	<td>$fecha</td>
	</tr>";
}

echo "</table>";

View::end();