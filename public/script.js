(function () {
	const siteKey = document.currentScript.getAttribute('data-sitekey');
	const contenedor = document.getElementById('captcha-creativos');

	if (!siteKey || !contenedor) return;

	// ✅ Duración del CAPTCHA en minutos (0.5 es medio minuto)
	const captchaDuracionMinutos = 0.5; // Cambia este valor como prefieras
	const captchaDuracionMilisegundos = captchaDuracionMinutos * 60 * 1000;	

	let mouseMovido = false;
	let tiempoPasado = false;
	let tokenGenerado = false;
	let imagenMostrada = false;

	contenedor.innerHTML = `
		<div id="captcha-box" style="
			display: flex;
			align-items: center;
			justify-content: space-between;
			background: #f9f9f9;
			border: 1px solid #ccc;
			border-radius: 6px;
			padding: 10px 15px;
			box-shadow: 0 2px 6px rgba(0,0,0,0.1);
			cursor: not-allowed;
			user-select: none;
			width:90%;
			max-width: 340px;
			font-family: Arial, sans-serif;
			position: relative;
		">
			<div id="captcha-content" style="display: flex; align-items: center; flex-wrap: nowrap; width: 100%;">
				<div id="captcha-icon" style="width:24px;height:24px;margin-right:10px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
						<rect x="2" y="2" width="20" height="20" fill="white" stroke="#000" stroke-width="2"/>
					</svg>
				</div>
				<div id="captcha-text" style="color: #555;">No soy un robot</div>
			</div>
			<div id="captcha-logo" style="margin-left: 10px;">
				<img src="https://captcha.creativos75.es/graf/logo/logo_recorte_75_v2.png" alt="Captcha75" style="width:130px; opacity:0.9;">
			</div>
		</div>
	`;

	// Aplicar estilos responsivos con CSS
	const style = document.createElement('style');
	style.innerHTML = `
		#captcha-box {
			flex-direction: row;
		}
		@media (max-width: 390px) {
			#captcha-box {
				flex-direction: column;
				align-items: center;
				text-align: center;
			}
			#captcha-content {
				justify-content: center;
				margin-bottom: 10px;
			}
			#captcha-logo img {
				width: 98%;
				max-width: none;
			}
		}
	`;
	document.head.appendChild(style);

	const box = document.getElementById('captcha-box');
	const text = document.getElementById('captcha-text');
	const icon = document.getElementById('captcha-icon');

	window.addEventListener('mousemove', () => mouseMovido = true);
	window.addEventListener('touchstart', () => mouseMovido = true); 
	window.addEventListener('click', () => mouseMovido = true); 

	setTimeout(() => {
		tiempoPasado = true;
		if (mouseMovido) activarCaja();
	}, 2000);

	function activarCaja() {
		box.style.cursor = 'pointer';

		box.addEventListener('click', function (e) {
			if (!imagenMostrada) {
				const num = Math.floor(Math.random() * 12) + 1;
				const imagenUrl = `https://captcha.creativos75.es/assets/img${String(num).padStart(2, '0')}.jpg`;
				mostrarImagenAlLado(imagenUrl, e);
				e.stopPropagation();
				return;
			}

			// Solo permite verificar si el desafío fue completado correctamente
			if (tokenGenerado && imagenMostrada) {
				text.textContent = 'Verificando...';
				enviarPeticionToken();
			}
		});
	}

	function reiniciarCaptcha() {
		tokenGenerado = false;
		imagenMostrada = false;
		text.textContent = 'No soy un robot';
		icon.innerHTML = `
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
				<rect x="2" y="2" width="20" height="20" fill="white" stroke="#000" stroke-width="2"/>
			</svg>
		`;

		// ✅ Asegurar que el cursor sea "pointer" inicialmente
		box.style.cursor = 'pointer'; 

		const form = contenedor.closest('form');
		if (form) {
			const input = form.querySelector('input[name="captcha_token"]');
			if (input) input.remove();
		}

		mouseMovido = false;
		tiempoPasado = false;

		// ✅ Llamar a la función de expiración si está definida
		if (typeof window.onExpired === 'function') {
			window.onExpired();
		}

		// ✅ Evento para reactivar la caja si el usuario mueve el ratón
		window.addEventListener('mousemove', activarDeNuevo);
		window.addEventListener('touchstart', activarDeNuevo);

		// ✅ Activar automáticamente tras el tiempo definido
		setTimeout(() => {
			tiempoPasado = true;
			if (mouseMovido) activarCaja();
		}, captchaDuracionMilisegundos);
	}

	// ✅ Nueva función para reactivar la caja del CAPTCHA
	function activarDeNuevo() {
		mouseMovido = true;
		if (tiempoPasado) {
			activarCaja();
			window.removeEventListener('mousemove', activarDeNuevo);
			window.removeEventListener('touchstart', activarDeNuevo);
		}
	}

	function mostrarImagenAlLado(url, e) {
		if (document.getElementById('imagen-desafio')) return;

		imagenMostrada = true;

		const colors = ['red', 'blue', 'green', 'orange', 'purple'];
		const colorsEs = ['rojo', 'azul', 'verde', 'naranja', 'morado'];
		const radius = 10;
		const padding = 20;
		const ancho = 300;
		const alto = 300;

		const indiceColor = Math.floor(Math.random() * colors.length);
		const colorObjetivo = colors[indiceColor];
		const colorObjetivoEs = colorsEs[indiceColor];

		const imagen = document.createElement('img');
		imagen.src = url;
		imagen.id = 'imagen-desafio';
		imagen.alt = 'Desafío adicional';
		imagen.style.position = 'absolute';
		imagen.style.top = (e.clientY - 15) + 'px';
		imagen.style.left = (e.clientX - 25) + 'px';
		imagen.style.width = ancho + 'px';
		imagen.style.height = alto + 'px';
		imagen.style.border = '2px solid #ccc';
		imagen.style.borderRadius = '6px';
		imagen.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
		imagen.style.cursor = 'default';
		imagen.style.zIndex = '10000';

		document.body.appendChild(imagen);

		const mensaje = document.createElement('div');
		mensaje.textContent = `Haz clic en el círculo ${colorObjetivoEs}`;
		mensaje.style.position = 'absolute';
		mensaje.style.top = (e.clientY + alto + 0) + 'px'; // 10px debajo de la imagen
		mensaje.style.left = (e.clientX - 25) + 'px';        // misma posición izquierda que la imagen
		mensaje.style.width = ancho + 'px';                 // igual al ancho de la imagen
		mensaje.style.textAlign = 'center';
		mensaje.style.fontSize = '18px';
		mensaje.style.background = '#ccc';
		mensaje.style.border = '1px solid #ccc';
		mensaje.style.border = '1px solid #ccc';
		mensaje.style.boxShadow = '0 2px 6px rgba(0,0,0,0.8)';
		mensaje.style.fontWeight = 'bold';
		

		mensaje.style.padding = '0';
		mensaje.style.zIndex = '10000';

		document.body.appendChild(mensaje);

		const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		svg.setAttribute("width", ancho);
		svg.setAttribute("height", alto);
		svg.style.position = "absolute";
		svg.style.top = (e.clientY - 15) + "px";
		svg.style.left = (e.clientX - 25) + "px";
		svg.style.zIndex = "10001";

		for (let i = 0; i < 5; i++) {
			const cx = Math.floor(Math.random() * (ancho - 2 * (padding + radius))) + padding + radius;
			const cy = Math.floor(Math.random() * (alto - 2 * (padding + radius))) + padding + radius;

			const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
			circle.setAttribute("cx", cx);
			circle.setAttribute("cy", cy);
			circle.setAttribute("r", radius);
			//circle.setAttribute("stroke", colors[i]);
			circle.setAttribute("stroke", "black");
			circle.setAttribute("stroke-width", "3");
			//circle.setAttribute("fill", "white");
			circle.setAttribute("fill", colors[i]);
			circle.setAttribute("data-color", colors[i]);
			circle.style.cursor = 'pointer';

			circle.addEventListener('click', function (ev) {
				ev.stopPropagation();
				const colorClicado = this.getAttribute('data-color');
				if (colorClicado === colorObjetivo) {
					const xhr = new XMLHttpRequest();
					xhr.open('POST', 'https://captcha.creativos75.es/captcha/api/generar_token.php', true);
					xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					xhr.onreadystatechange = function () {
						if (xhr.readyState === 4) {
							try {
								const res = JSON.parse(xhr.responseText);
								if (res.success && res.token) {
									text.textContent = 'Verificado';
									icon.innerHTML = `
										<svg xmlns="http://www.w3.org/2000/svg" fill="green" viewBox="0 0 24 24">
											<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
										</svg>
									`;
									tokenGenerado = true;


									const form = contenedor.closest('form');
									if (form) {
										const input = document.createElement('input');
										input.type = 'hidden';
										input.name = 'captcha_token';
										input.id = 'captcha_token';
										input.value = res.token;
										form.appendChild(input);
									} else {
										// Si no existe formulario, guardar el token globalmente
										window.captcha_creativos_token = res.token;
									}

									if (typeof window.onComplete === 'function') {
										window.onComplete();
									}

									setTimeout(() => {
										reiniciarCaptcha();
									}, 30000);
								} else {
									text.textContent = 'Error generando token';
								}
							} catch (e) {
								text.textContent = 'Error de conexión';
							}
						}
					};

					const dominio = window.location.hostname;
					xhr.send('site_key=' + encodeURIComponent(siteKey) + '&dominio=' + encodeURIComponent(dominio));
				} else {
					reiniciarCaptcha();
				}

				imagen.remove();
				svg.remove();
				mensaje.remove();
			});

			svg.appendChild(circle);
		}

		document.body.appendChild(svg);
		imagen.addEventListener('click', ev => ev.stopPropagation());
		svg.addEventListener('click', ev => ev.stopPropagation());
		mensaje.addEventListener('click', ev => ev.stopPropagation());


		function activarCierreFuera() {
			document.addEventListener('click', function cerrarTodo(ev) {
				// Solo se cierra si la imagen está abierta y el CAPTCHA no está verificado
				if (imagenMostrada && !tokenGenerado) {
					const clickDentro = (
						imagen.contains(ev.target) ||
						svg.contains(ev.target) ||
						mensaje.contains(ev.target) ||
						box.contains(ev.target) ||
						ev.target.closest('form')
					);

					// Si el clic está fuera, cerrar
					if (!clickDentro) {
						imagen.remove();
						svg.remove();
						mensaje.remove();
						reiniciarCaptcha();
						document.removeEventListener('click', cerrarTodo);
					}
				}
			});
		}
	}

	function enviarPeticionToken() {
		const xhr = new XMLHttpRequest();
		xhr.open('POST', 'https://captcha.creativos75.es/captcha/api/generar_token.php', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4) {
				try {
					const res = JSON.parse(xhr.responseText);
					if (res.success && res.token) {
						text.textContent = 'Verificado';
						tokenGenerado = true;

						icon.innerHTML = `
							<svg xmlns="http://www.w3.org/2000/svg" fill="green" viewBox="0 0 24 24">
								<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
							</svg>
						`;

						window.captcha_creativos_token = res.token;

						const formularios = document.querySelectorAll('form');
						formularios.forEach(form => {
							const input = document.createElement('input');
							input.type = 'hidden';
							input.name = 'captcha_token';
							input.value = res.token;
							form.appendChild(input);
						});

						if (typeof window.onComplete === 'function') {
							window.onComplete();
						}

						setTimeout(() => {
							reiniciarCaptcha();
						}, 30000);
					} else {
						text.textContent = 'Error de validación';
						icon.innerHTML = '';
						box.style.cursor = 'not-allowed';
					}
				} catch (e) {
					text.textContent = 'Error de conexión';
				}
			}
		};
		xhr.send('site_key=' + encodeURIComponent(siteKey));
	}
})();



//Logo inferior derexha
(function () {
	const contenedor = document.createElement('div');
	contenedor.id = 'captcha-creativos';
	contenedor.style.position = 'fixed';
	contenedor.style.bottom = '10px';
	contenedor.style.right = '-190px'; // Oculto inicialmente, desplazado a la derecha
	contenedor.style.width = '250px'; // Ancho completo del div
	contenedor.style.height = '60px';
	contenedor.style.cursor = 'pointer';
	contenedor.style.transition = 'right 0.4s ease';
	contenedor.style.zIndex = '9999';
	contenedor.style.display = 'flex';
	contenedor.style.alignItems = 'center';
	contenedor.style.justifyContent = 'start';
	contenedor.style.background = '#ffffff';
	contenedor.style.border = '1px solid #ddd';
	contenedor.style.borderRadius = '8px';
	contenedor.style.boxShadow = '0 2px 6px rgba(0,0,0,0.15)';
	contenedor.style.padding = '5px';
	contenedor.style.overflow = 'hidden';
	contenedor.style.whiteSpace = 'nowrap';

	const captchaLogo = document.createElement('img');
	captchaLogo.src = 'https://captcha.creativos75.es/graf/logo/logo_integrado.png';
	captchaLogo.style.width = '50px';
	captchaLogo.style.height = 'auto';
	captchaLogo.style.transition = 'all 0.4s ease';
	captchaLogo.alt = 'Protección Captcha';
	contenedor.appendChild(captchaLogo);

	const captchaText = document.createElement('span');
	
	captchaText.innerHTML = `
		<p style="margin:0;padding:0;">Protegido por Captcha'75</p>
		<p style="margin:6px 0 0 0;padding:0;font-size:12px;text-align:center;">
			<a href="https://captcha.creativos75.es/privacidad.php" target="_blank" style="text-decoration:none;color:#03107e">Privacidad</a> 
			<a href="https://captcha.creativos75.es/terminos.php" target="_blank" style="text-decoration:none;color:#03107e;margin-left:5px;">Condicciones</a>
		</p>
	`;
	captchaText.style.marginLeft = '10px';
	captchaText.style.color = '#4a4a4a';
	captchaText.style.fontSize = '14px';
	captchaText.style.opacity = '1';
	captchaText.style.transition = 'opacity 0.4s ease';
	contenedor.appendChild(captchaText);

	// Mostrar el contenedor completo al pasar el ratón
	contenedor.addEventListener('mouseover', function() {
		contenedor.style.right = '10px'; // Se muestra suavemente
	});

	// Ocultar el contenedor al quitar el ratón
	contenedor.addEventListener('mouseleave', function() {
		contenedor.style.right = '-190px'; // Se oculta suavemente
	});

	document.body.appendChild(contenedor);
})();
