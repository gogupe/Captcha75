<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/conexion/conexion.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/function/control_login.php";

$active="clientes";

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
<link rel="stylesheet" href="/vendor/almasaeed2010/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="/vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.css">



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

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Clientes</h3>

                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Identificador</th>
                                            <th>Dominios</th>
                                            <th>Site key</th>
                                            <th>Secret Key</th>
                                            <th class="text-right">Ok</th>
                                            <th class="text-right">Nok</th>
                                            <th colspan="5"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql_list="SELECT * FROM clientes ORDER BY nombre";
                                    $result_list=mysqli_query($link,$sql_list);
                                    $cont=1;
                                    while ($row_list=mysqli_fetch_assoc($result_list))
                                        {
                                            $check_id="check_".$row_list['id'];

                                            if ($row_list['activo']=="1")
                                                {$check_activo="checked";}
                                            else{$check_activo="";}
                                    ?>
                                            <tr>
                                                <td><?php echo $cont++;?></td>
                                                <td><?php echo $row_list['nombre'];?></td>
                                                <td><?Php echo $row_list['dominios_permitidos'];?></td>
                                                <td><?Php echo $row_list['site_key'];?></td>
                                                <td><?Php echo $row_list['secret_key'];?></td>
                                                <td class="text-right"><?php echo $row_list['aciertos'];?></td>
                                                <td class="text-right"><?php echo $row_list['fallos'];?></td>

                                                <td>
                                                    <a href="javascript:void(0)" class="delete-client" data-id="<?php echo $row_list['id']; ?>">
                                                        <i class="fa-solid fa-trash-can" style="color:#000;"></i>
                                                    </a>
                                                </td>

                                                <td><a href="/admin/modificar_cliente.php?id=<?php echo $row_list['id'];?>"><i class="fa-solid fa-pen-to-square" style="color:#000;"></i></a></td>
                                                <td>
                                                    <a href="javascript:void(0)" class="show-html-code" data-sitekey="<?php echo $row_list['site_key'];?>" title="Código HTML">
                                                        <i class="fa-solid fa-code" style="color:#000;"></i>
                                                    </a>
                                                </td>
                                                <?php
                                                /*
                                                <td>
                                                    <a href="javascript:void(0)" class="show-wp-code" data-sitekey="<?php echo $row_list['site_key'];?>" title="Código WordPress">
                                                        <i class="fa-brands fa-wordpress" style="color:#000;"></i>
                                                    </a>
                                                </td>
                                                */
                                                ?>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input toggle-active" data-id="<?php echo $row_list['id']; ?>" id="<?php echo $check_id; ?>" <?php echo $check_activo; ?>>
                                                        <label class="custom-control-label" for="<?php echo $check_id;?>"></label>
                                                        </div>
                                                    </div>                                            
                                                </td>                                                
                                            </tr>
                                    <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
		</div>
	</div>

    <div class="modal fade" id="codeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="codeTitle">Código de Inserción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea id="codeContent" class="form-control" rows="6" readonly></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="copyToClipboard()">Copiar al Portapapeles</button>
                </div>
            </div>
        </div>
    </div>

    <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">
        <div id="toastContainer" style="position: absolute; top: 20px; right: 20px; z-index: 9999;"></div>
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
<script src="/vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/vendor/almasaeed2010/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script>


document.addEventListener('DOMContentLoaded', function() {
	// Detectar clic en el icono de HTML
	document.querySelectorAll('.show-html-code').forEach(button => {
		button.addEventListener('click', function() {
			const siteKey = this.getAttribute('data-sitekey');
			showCodeModal('HTML/PHP', generateHtmlCode(siteKey));
		});
	});

	// Detectar clic en el icono de WordPress
	document.querySelectorAll('.show-wp-code').forEach(button => {
		button.addEventListener('click', function() {
			const siteKey = this.getAttribute('data-sitekey');
			showCodeModal('WordPress', generateWpCode(siteKey));
		});
	});
});

// Mostrar el modal con el código correspondiente
function showCodeModal(title, code) {
	document.getElementById('codeTitle').textContent = 'Código de Inserción (' + title + ')';
	document.getElementById('codeContent').value = code;
	$('#codeModal').modal('show');
}

// Generar el código de HTML/PHP
function generateHtmlCode(siteKey) {
	return '<div id="captcha-creativos"></div>\n' + 
	       '<script src="https://captcha.creativos75.es/public/script.js" data-sitekey="' + siteKey + '"><\/script>';
}

// Generar el código para WordPress (Shortcode)
function generateWpCode(siteKey) {
	return '[captcha_creativos sitekey="' + siteKey + '"]';
}

// Copiar al portapapeles
function copyToClipboard() {
	const content = document.getElementById('codeContent');
	content.select();
	document.execCommand('copy');
	showToast('Código copiado al portapapeles.');
}

function showToast(message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: `<div class="swal-toast-custom-text">${message}</div>`,
        showConfirmButton: false,
        timer: 2000, // 2 segundos
        timerProgressBar: true,
        customClass: {
            popup: 'swal-toast-custom'
        }
    });
}

// CSS para centrar verticalmente y horizontalmente el texto del toast
const style = document.createElement('style');
style.innerHTML = `
    .swal-toast-custom .swal-toast-custom-text {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
        font-size: 16px;
        font-weight: 500;
        text-align: center;
    }
`;
document.head.appendChild(style);


$(document).ready(function() {
    $('.toggle-active').on('change', function() {
        var clientId = $(this).data('id');
        var isActive = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '/admin/ajax/ajax_activo_cliente.php',
            type: 'POST',
            data: {
                id: clientId,
                activo: isActive
            },
            success: function(response) {
                if (response == 'ok') {

                }
            }

        });
    });
});



$(document).ready(function() {
    // Manejar el evento de clic en el icono de la papelera
    $(document).on('click', '.delete-client', function() {
        var clientId = $(this).data('id');

        // Mostrar SweetAlert de confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, se envía la petición AJAX
                $.ajax({
                    url: '/admin/ajax/ajax_eliminar_cliente.php',
                    type: 'POST',
                    data: {
                        id: clientId
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        });
    });
});

</script>


</body>
</html>
