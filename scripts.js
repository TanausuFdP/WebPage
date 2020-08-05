$(document).ready(function(){
	$("button").click(function(event) {
    	event.stopPropagation();
	});
});

function updateSearch(){
    $.ajax({
        url: "search.php",
        type: "POST",
        data: JSON.stringify({  "texto_buscar":$('.texto_buscar').val(), 
                                "seleccion":$('#seleccion').val(), 
                                "fecha_inicio":$('#fecha_inicio').val(), 
                                "fecha_fin":$('#fecha_fin').val()}),
        contentType: "application/json;charset=utf-8",
        dataType: "json",
        success: function(res){
            $("#result").html(res);
        }
    });
}

function deleteActivity(id, name){
	if(!confirm("¿Desea borrar la actividad '" + name + "'?")){
		return;
	}

	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange=function(){
		if(this.readyState == 4 && this.status ==200){
			var res = JSON.parse(this.responseText);
			if(res.deleted == true){
				var row = document.querySelector('#row' + id);
				row.parentNode.removeChild(row);
			}
		}
	};
	ajax.open("post", "delete_activity_json.php", true);
	ajax.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	ajax.send(JSON.stringify({"id":id}));
}