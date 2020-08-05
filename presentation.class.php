<?php
include_once 'business.class.php';

class View{
	public static function start($title){
		$html="<!DOCTYPE html>
		<html>
		<head>
		<meta charset=\"utf-8\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"estilos.css\">
        <script src=\"//code.jquery.com/jquery-1.11.2.js\"></script>
        <script src=\"//code.jquery.com/ui/1.11.4/jquery-ui.js\"></script>
        <script src=\"scripts.js\"></script>
		<title>$title</title>
		</head>
		<body>
		";
		User::session_start();
		echo $html;
	}

	public static function imgtobase64($img){
        $b64 = base64_encode($img);
        $signature = substr($b64, 0, 3);
        if ( $signature == '/9j') {
            $mime = 'data:image/jpeg;base64,';
        } else if ( $signature == 'iVB') {
            $mime = 'data:image/png;base64,';
        }
        return $mime . $b64;
    }

    public static function topBar($num){
        $user = User::getLoggedUser();
        $link = 'login.php';
        $word = 'Acceder';
        if($user){
            $link = 'user.php';
            $word = $user['nombre'];
        }
    	echo "<nav id=\"topBar\">
    	<img src=\"Imágenes/logo2.png\">
    	<p id=\"logo\">GCACTIVA</p>";
    	if($num == 3){
    		echo "<p id=\"selected\">$word</p>";
    	}else{
    		echo "<a href=\"$link\">$word</a>";
    	}
    	if($num == 2){
    		echo "<p id=\"selected\">Actividades</p>";
    	} else {
    		echo "<a href=\"activities.php\">Actividades</a>";
    	}
    	if($num == 1){
    		echo "<p id=\"selected\">Página principal</p>";
    	} else {
    		echo "<a href=\"index.php\">Página principal</a>";
    	}
    	echo "</nav>";
    }

    public static function end(){
    	echo "<hr>
    	<p class=\"ending\">Copyright © 2020 TanausúFdP Inc. Todos los derechos reservados.</p>
    	</body>
    	</html>";
    }
}
