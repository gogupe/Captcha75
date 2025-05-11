<?php
if (!$link)	{exit;}
switch ($active)
	{
		case "clientes":
			$menu_cliente="active";
			break;


		case "nuevo_cliente":
			$menu_nuevo_cliente="active";
			break;


	}

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<p class="m-0 p-0 text-center">
		<a href="/admin/admin.php" class="brand-link bg-light">
			<img src="/graf/logo/logo_recorte.png" alt="Logo"  style="opacity: .8;width:90%;max-width:150px;">
		</a>
	</p>
	<div class="sidebar bg-navy">

	<nav class="mt-2">
	<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

		<li class="nav-item">
			<a href="/admin/cliente.php" class="nav-link <?php echo $menu_cliente;?>">
				<i class="nav-icon fa-solid fa-globe"></i>
				<p>Clientes</p>
			</a><i class=""></i>
		</li>

		<li class="nav-item">
			<a href="/admin/nuevo_cliente.php" class="nav-link <?php echo $menu_nuevo_cliente;?>">
				<i class="nav-icon fa-solid fa-plus"></i>
				<p>Nuevo cliente</p>
			</a>
		</li>


		<li class="nav-item">
			<a href="javascript:void(0)" class="nav-link" data-toggle="modal" data-target="#modal-desconectar">
				<i class="nav-icon fas fa-sign-out-alt"></i>
				<p>Desconectar</p>
			</a>
		</li>
	</ul>
	</nav>
</div>

</aside>
