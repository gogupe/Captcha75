<?php
session_name();
session_start();
include $_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/control_login.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/genera_token.php";

$active="nuevo_cliente";

$sql_comp="SELECT * FROM clientes WHERE site_key='".escapa($_GET['token'])."'";
$result_comp=mysqli_query($link,$sql_comp);
$cuanto_comp=mysqli_num_rows($result_comp);

if ($cuanto_comp==0)
	{
		mysqli_free_result($result_comp);
		header ("location:/admin/nuevo_cliente.php");
		exit;
	}
$row_comp=mysqli_fetch_assoc($result_comp);
mysqli_free_result($result_comp);



if (isset($_POST['sb_ins_ur']))
	{
		
		$dominios = escapa($_POST['url']);
		$cadena=$row_comp['dominios_permitidos'].",".$dominios;
		$cadena = ltrim($cadena, ',');
		


		$sql_ins="UPDATE clientes SET dominios_permitidos='$cadena' WHERE site_key='".escapa($_GET['token'])."'";
		mysqli_query($link,$sql_ins);

		header ("location:/admin/url_permitidas.php?token=$_GET[token]");
		exit;
}

if (isset($_POST['sb_activar']))
	{
		$sql_mod="UPDATE clientes SET activo='1' WHERE id='$row_comp[id]'";
		mysqli_query($link,$sql_mod);

		header ("location:/admin/cliente.php");
		exit;
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Captcha Creativos 75</title>
<link rel="icon" href="/favicon.png" type="image/png" sizes="64x64">
<link rel="apple-touch-icon" href="/favicon.png" sizes="64x64">


<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="/vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">

<link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.css">


</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<ul class="navbar-nav">
			<li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i class="fas fa-bars"></i></a></li>
		</ul>
		<ul class="navbar-nav ml-auto"></ul>
	</nav>

	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/php/barra_izda.php"; ?>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-12"><h1 class="m-0"></h1></div>
				</div>
			</div>
		</div>

		<div class="content">
			<div class="container-fluid">
				<form method="post">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="nombre">Nombre identificativo</label>
							<input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $row_comp['nombre'];?>" disabled>
						</div>						
					</div>

					<div class="col-md-7">
						<div class="form-group">
							<label for="url">URL Permitidas </label> (Sin https://)
							<input type="text" name="url" class="form-control" id="url" placeholder="URL Permitidas" autofocus reqruired>
						</div>						
					</div>

					<div class="col-sm-2">
						<label>&nbsp;</label>
						<input type="submit" name="sb_ins_ur" class="btn btn-success btn-block" value="Insertar" />
					</div>				
				</div>
				</form>

				<div class="row mt-2">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title font-weight-bold">URL con permisos</h3>							
							</div>
							<?php
							$cont=1;
							?>

							<div class="card-body table-responsive p-0">
								<table class="table">
									<thead>
									<tr>
										<th style="width: 10px">#</th>
										<th>URL</th>
										<th colspan="2"></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$elementos = explode(',', $row_comp['dominios_permitidos']);
									foreach ($elementos as $index => $nombre) {
									?>
										<tr>
											<td><?php echo $cont++; ?></td>
											<td><?php echo trim($nombre); ?></td>
											
											<td class="text-center" style="width:50px;">
												<a href="javascript:void(0)" class="edit-domain" 
													data-index="<?php echo $index; ?>" 
													data-domain="<?php echo trim($nombre); ?>" 
												data-id="1">
												<i class="fa-solid fa-pen-to-square" style="color:#000";></i>
											</a>
											</td>

											<td class="text-center" style="width:50px;">
												<a href="javascript:void(0)" class="delete-domain" 
													data-index="<?php echo $index; ?>" 
													data-domain="<?php echo trim($nombre); ?>" 
													data-id="<?php echo $row_comp['id']; ?>"> <!-- Enviar el ID del cliente -->
													<i class="fa-solid fa-trash-can" style="color:#000";></i>
												</a>
											</td>
										</tr>
									<?php
									}
									?>

								</tbody>
								</table>
							</div>

							<form method="post">
							<div class="card-footer text-right pt-4 pb-4">
								<button type="submit" name="sb_activar" class="btn btn-success">Grabar</button>
							</div>				
							</form>					
						</div>

				
					</div>
				
				</div>
			</div>
		</div>
	</div>

<!-- Modal para editar dominio -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Dominio</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="text" class="form-control" id="editDomainInput">
				<input type="hidden" id="editDomainIndex">
				<input type="hidden" id="editDomainId">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" id="saveDomain">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal de confirmación para eliminar dominio -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Eliminar Dominio</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>¿Estás seguro de que deseas eliminar este dominio?</p>
				<p><strong id="deleteDomainText"></strong></p>
				<input type="hidden" id="deleteDomainIndex">
				<input type="hidden" id="deleteDomainId">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
			</div>
		</div>
	</div>
</div>


	<?php
	include $_SERVER['DOCUMENT_ROOT']."/admin/php/form_desconectar.php";
	?>
	<aside class="control-sidebar control-sidebar-dark"></aside>

	<?php include $_SERVER['DOCUMENT_ROOT']."/admin/php/copy.php"; ?>
</div>

<script src="/vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/dist/js/adminlte.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
	// Detectar clic en el botón de edición
	document.querySelectorAll('.edit-domain').forEach(function(button) {
		button.addEventListener('click', function() {
			const index = this.getAttribute('data-index');
			const domain = this.getAttribute('data-domain');
			const id = this.getAttribute('data-id');

			// Verificar que el modal exista antes de usarlo
			const editDomainInput = document.getElementById('editDomainInput');
			const editDomainIndex = document.getElementById('editDomainIndex');
			const editDomainId = document.getElementById('editDomainId');

			if (editDomainInput && editDomainIndex && editDomainId) {
				editDomainInput.value = domain;
				editDomainIndex.value = index;
				editDomainId.value = id;
				$('#editModal').modal('show');
			}
		});
	});

	// Detectar el evento "Enter" en el input de edición
	document.getElementById('editDomainInput').addEventListener('keydown', function(event) {
		if (event.key === 'Enter') {
			event.preventDefault();
			document.getElementById('saveDomain').click();
		}
	});

	// Guardar cambios del dominio
	document.getElementById('saveDomain').addEventListener('click', function() {
		const index = document.getElementById('editDomainIndex').value;
		const nuevoDominio = document.getElementById('editDomainInput').value.trim();
		const id_cliente = document.getElementById('editDomainId').value;

		// Verificar que no esté vacío
		if (nuevoDominio === '') {
			alert('❌ El dominio no puede estar vacío.');
			return;
		}

		// Enviar por AJAX
		fetch('/admin/ajax/ajax_editar_dominio.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'index=' + index + '&nuevoDominio=' + encodeURIComponent(nuevoDominio) + '&id_cliente=' + id_cliente
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				location.reload();
			}
		});
	});
});



document.addEventListener('DOMContentLoaded', function() {
	// Detectar clic en el botón de eliminar
	document.querySelectorAll('.delete-domain').forEach(function(button) {
		button.addEventListener('click', function() {
			const index = this.getAttribute('data-index');
			const domain = this.getAttribute('data-domain');
			const id = this.getAttribute('data-id');

			// Configurar el modal de eliminación
			document.getElementById('deleteDomainText').textContent = domain;
			document.getElementById('deleteDomainIndex').value = index;
			document.getElementById('deleteDomainId').value = id;

			// Mostrar el modal de eliminación
			$('#deleteModal').modal('show');
		});
	});

	// Confirmar eliminación
	document.getElementById('confirmDelete').addEventListener('click', function() {
		const index = document.getElementById('deleteDomainIndex').value;
		const id_cliente = document.getElementById('deleteDomainId').value;

		// Enviar por AJAX para eliminar
		fetch('/admin/ajax/ajax_eliminar_dominio.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'index=' + index + '&id_cliente=' + id_cliente
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				location.reload();
			} else {
				alert('❌ Error al eliminar el dominio.');
			}
		});
	});
});

</script>



</body>
</html>

<?php
mysqli_close($link);