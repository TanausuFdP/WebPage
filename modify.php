<?php
include_once 'presentation.class.php';
include_once 'business.class.php';

if(array_key_exists("action", $_REQUEST)){
	$duration = $_REQUEST['duracion']*60;
	$array_date=preg_split("/[-^\/]/",$_REQUEST['inicio'],-1, PREG_SPLIT_NO_EMPTY);
    $newDate=$array_date[2]."-".$array_date[1]."-".$array_date[0]." ".$_REQUEST['hora'].":".$_REQUEST['minuto'].":00";
    $ini=strtotime("$newDate");
	if($_REQUEST['action']=='Modificar'){
		if(isset($_FILES['photo']) && ! empty($_FILES['photo']['tmp_name'])){
            $mTmpFile = $_FILES["photo"]["tmp_name"];
            $mTipo = exif_imagetype($mTmpFile);
            if (($mTipo == IMAGETYPE_JPEG) or ($mTipo == IMAGETYPE_PNG)){
                $img=file_get_contents( $_FILES['photo']['tmp_name'] );
                $sql = "UPDATE actividades SET nombre=?, tipo=?, descripcion=?, 
                precio=?, aforo=?, inicio=?, duracion=?, imagen=? WHERE id=?;";
                DB::execute_sql($sql, array($_REQUEST['nombre'],$_REQUEST['tipo'],$_REQUEST['descripcion'],$_REQUEST['precio'],
                $_REQUEST['aforo'],$ini,$duration,$img,$_REQUEST['id']));
            }
        } else {
            $sql = "UPDATE actividades SET nombre=?, tipo=?, descripcion=?, 
            precio=?, aforo=?, inicio=?, duracion=? WHERE id=?;";
            View::start("GCActiva - Nueva actividad");
            DB::execute_sql($sql, array($_REQUEST['nombre'],$_REQUEST['tipo'],$_REQUEST['descripcion'],$_REQUEST['precio'],
            $_REQUEST['aforo'],$ini,$duration,$_REQUEST['id']));
        }
		header("Location: user.php");
		exit(); 
	} else if($_REQUEST['action']=='Añadir'){
		$mTmpFile = $_FILES["photo"]["tmp_name"];
        $mTipo = exif_imagetype($mTmpFile);
        if (($mTipo == IMAGETYPE_JPEG) or ($mTipo == IMAGETYPE_PNG)){
        	$user = User::getLoggedUser();
            $img=file_get_contents( $_FILES['photo']['tmp_name'] );
            $sql = "INSERT INTO [actividades] ([idempresa], [nombre], [tipo], [descripcion],
            [precio], [aforo], [inicio], [duracion], [imagen]) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);";
            
            DB::execute_sql($sql, array($user['id'],$_REQUEST['nombre'],$_REQUEST['tipo'],
            $_REQUEST['descripcion'],$_REQUEST['precio'],$_REQUEST['aforo'],$ini,$duration,$img));
        }

		header("Location: user.php");
		exit();
	} else {
		header("Location: user.php");
		exit();
	}
}

if(array_key_exists("id", $_REQUEST)){
	$activities = DB::execute_sql("SELECT * FROM actividades WHERE id={$_REQUEST['id']}");
	$activity = $activities->fetch();

	View::start("GCActiva - {$activity['nombre']}");
	View::topBar(0);

	$date=date("d/m/Y", $activity['inicio']);
    $array_date=preg_split("/[-^\/]/",$date,-1, PREG_SPLIT_NO_EMPTY);
    $date=$array_date[2]."-".$array_date[1]."-".$array_date[0];
    $hour=date("H", $activity['inicio']);
    $minute=date("i", $activity['inicio']);
    $duracion=$activity['duracion']/60;

	echo "<div class=\"modify\">
	<h2>Modificar la actividad \"{$activity['nombre']}\"</h2>
	<form enctype=\"multipart/form-data\" action=\"modify.php\" method=\"post\">
	<input type=\"hidden\" name=\"id\" value=\"{$activity['id']}\">
	<input class=\"leftInput\" type=\"text\" name=\"nombre\" value=\"{$activity['nombre']}\" placeholder=\"Nombre\"><br>
	<input class=\"leftInput\" type=\"text\" name=\"tipo\" value=\"{$activity['tipo']}\" placeholder=\"Tipo\"><br>
	<textarea name=\"descripcion\" placeholder=\"Descripción\">{$activity['descripcion']}</textarea><br>
	<input class=\"leftInput2\" type=\"number\" name=\"precio\" value=\"{$activity['precio']}\" min=\"0\" step=\"any\"><label> €/ticket</label><br>
	<input class=\"leftInput2\" type=\"number\" name=\"aforo\" value=\"{$activity['aforo']}\" min=\"1\" step=\"1\"><label> personas</label><br>
	<label class=\"leftInput3\">La actividad comienza el </label><input type=\"date\" name=\"inicio\" value=\"$date\" placeholder=\"aaaa-MM-dd\">
	<label> a las</label> <input type=\"number\" name=\"hora\" min=\"00\" max=\"23\" value=\"$hour\"/>
    <label> y </label><input type=\"number\" name=\"minuto\" min=\"00\" max=\"59\" value=\"$minute\"/><br>
	<label class=\"leftInput3\">La actividad dura </label><input type=\"number\" name=\"duracion\" value=\"$duracion\"><label> minutos</label><br>
	<input class=\"leftInput\" type=\"file\" name=\"photo\"/><br>
	<input class=\"submit\" type=\"submit\" name=\"action\" value=\"Modificar\">
	<input class=\"submit\" type=\"submit\" name=\"action\" value=\"Cancelar\">
	</form>
	</div>";
} else {
	View::start("GCActiva - Nueva actividad");
	View::topBar(0);

	echo "<div class=\"modify\">
	<h2>Nueva actividad</h2>
	<form enctype=\"multipart/form-data\" action=\"modify.php\" method=\"post\">
	<input class=\"leftInput\" type=\"text\" name=\"nombre\" placeholder=\"Nombre\"><br>
	<input class=\"leftInput\" type=\"text\" name=\"tipo\" placeholder=\"Tipo\"><br>
	<textarea name=\"descripcion\" placeholder=\"Descripción\"></textarea><br>
	<input class=\"leftInput2\" type=\"number\" name=\"precio\" min=\"0\" step=\"any\"><label> €/ticket</label><br>
	<input class=\"leftInput2\" type=\"number\" name=\"aforo\" min=\"1\" step=\"1\"><label> personas</label><br>
	<label class=\"leftInput3\">La actividad comienza el </label><input type=\"date\" name=\"inicio\" placeholder=\"aaaa-MM-dd\">
	<label> a las</label> <input type=\"number\" name=\"hora\" min=\"00\" max=\"23\"/>
    <label> y </label><input type=\"number\" name=\"minuto\" min=\"00\" max=\"59\" /><br>
	<label class=\"leftInput3\">La actividad dura </label><input type=\"number\" name=\"duracion\" ><label> minutos</label><br>
	<input class=\"leftInput\" type=\"file\" name=\"photo\"/><br>
	<input class=\"submit\" class=\"submit\" type=\"submit\" name=\"action\" value=\"Añadir\">
	<input class=\"submit\" type=\"submit\" name=\"action\" value=\"Cancelar\">
	</form>
	</div>";
}

View::end();