//ver lista de alumnos de la materia
function verlista(sfkeyy) {
    //obtenemos el sfkey
  sK= sfkeyy;

  $.ajax({
    type:'POST',
    url: 'lista.php',
    data:{sfkey: sK}
  }).done(function(msg){
    $("#destino").html(msg);
  }).fail(function(jqXHR, textStatus, errorThrown){
  $("#destino").html("Error al mostrar los grupos"+ textStatus +" "+ errorThrown)  
  })
  }


  $(document).ready(function(){
	var i=1;
	$('#add').click(function(){
		i++;
		$('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="rubro[]" placeholder="Descripcion" class="form-control name_list" /><input type="text" name="Porcentaje[]" placeholder="%" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
		verlista($_SESSION['sfkeyy']);  
	});
	
	$(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id"); 
		$('#row'+button_id+'').remove();
	});
	
	$('#submit').click(function(){		
		$.ajax({
			url:"nombre.php",
			method:"POST",
			data:$('#add_name').serialize(),
			success:function(data)
			{
				alert(data);
				$('#add_name')[0].reset();
			}
		});
	});
	
});