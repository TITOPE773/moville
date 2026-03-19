<?php
session_start();

var_dump($_SESSION);

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movilian | Centro de Diagnóstico Avanzado</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/eb43394144.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS local (está dentro de frontend/) -->
    <link rel="stylesheet" href="movile.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="logo">MOVI<span>LIAN</span></div>
            <ul class="nav-links">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#servicios">Diagnóstico</a></li>
                <li><a href="#opiniones">Opiniones</a></li>
                <li><a href="#trabajos">Trabajos</a></li>
                <li>
                    <div class="lang-switcher">
                        <button class="lang-btn active" onclick="changeLanguage('es')">ES</button>
                        <button class="lang-btn" onclick="changeLanguage('en')">EN</button>
                        <!-- idiomas.js está en frontend/ -->
                        <script src="idiomas.js"></script>
                    </div>
                </li>
                <li>
<?php if(isset($_SESSION['usuario'])): ?>

<a href="../backend/logout.php">Cerrar sesión</a>

<?php else: ?>

<a href="login.php">Iniciar sesión</a>

<?php endif; ?>
                </li>
            </ul>
        </nav>
    </header>

    <main>
      <section id="inicio" class="swiper mySwiper">
        <div class="swiper-wrapper" id="carrusel-dinamico">
        </div>

        <!-- Burbujitas del carrusel -->
        <div class="swiper-pagination"></div>
      </section>

        <section id="servicios" class="section-container">
            <div class="about-grid">
                <div>
                    <h2 class="section-title text-left">Laboratorio Técnico Especializado</h2>
                    <p class="about-description">En Movilian no adivinamos. Utilizamos herramientas de diagnóstico de grado industrial para las marcas más reconocidas del mercado. Restauramos el rendimiento original de tu equipo.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-microscope"></i> Análisis microscópico de hardware.</li>
                        <li><i class="fas fa-battery-full"></i> Testeo de ciclos y rendimiento de batería.</li>
                        <li><i class="fas fa-mobile-alt"></i> Reemplazo de pantallas OEM garantizadas.</li>
                        <li><i class="fas fa-water"></i> Mantenimiento preventivo por humedad.</li>
                    </ul>
                </div>
                <!-- img/ está dentro de frontend/ -->
                <img src="img/iphone 17.jpg" class="about-img animate__animated animate__fadeInRight" alt="Iphone abierto para reparación">
            </div>
        </section>

        <section id="opiniones" class="section-container">
            <div class="comments-section">
                <div class="comments-header">
                    <h2>Opiniones de Clientes</h2>
                    <button class="admin-btn" onclick="activarAdmin()" title="Modo Administrador"><i class="fas fa-lock"></i></button>
                </div>

                <div id="lista-comentarios">
                    <div class="comment-box">
                        <div>
                            <div class="comment-user"><i class="fas fa-user-circle"></i> Carlos Martínez <span class="comment-date">- Hace 2 días</span></div>
                            <p>Excelente servicio, mi Galaxy S24 Ultra no cargaba y lo solucionaron el mismo día. Muy transparentes con el diagnóstico.</p>
                        </div>
                        <button class="btn-delete" onclick="borrarComentario(1)" title="Borrar comentario"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="comment-form">
                    <h3>Deja tu opinión</h3>
                    <form id="formComentario" onsubmit="enviarComentario(event)">
                        <input type="text" id="comNombre" placeholder="Tu Nombre Completo" required>
                        <textarea id="comTexto" rows="3" placeholder="¿Cómo fue tu experiencia en Movilian?" required></textarea>
                        <button type="submit" class="btn-primary w-auto">Publicar Opinión</button>
                    </form>
                </div>
            </div>
        </section>

    <?php if(isset($_SESSION['usuario'])): ?>

<button class="fab-upload" onclick="document.getElementById('modalSubida').style.display='flex'">
<i class="fas fa-plus"></i> Subir Imagen
</button>

<?php endif; ?>

<div id="modalSubida" class="modal-upload-container">
    <div class="modal-card">
        <button class="btn-close" onclick="document.getElementById('modalSubida').style.display='none'">&times;</button>
        <h2 class="modal-title">Subir Nueva Imagen</h2>
        <p class="modal-subtitle">Esta imagen se guardará en la base de datos.</p>
        
        <!-- upload.php está en backend/ -->
        <form action="../backend/upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Título de la imagen</label>
                <input type="text" name="titulo" required placeholder="Ej: Reparación de Laptop">
            </div>
            <div class="form-group">
                <label>Subtítulo (Opcional)</label>
                <input type="text" name="subtitulo" placeholder="Ej: Servicio técnico especializado">
            </div>
            <div class="form-group">
                <label>Seleccionar Imagen</label>
                <input type="file" name="imagen" accept="image/*" required>
            </div>
            <button type="submit" class="btn-primary">Guardar en Base de Datos</button>
            <hr>
        </form>

        <h3>Imágenes subidas</h3>

        <div class="admin-imagenes">
            <!-- admin_imagenes.php está en admin/ -->
            <?php include("../admin/admin_imagenes.php"); ?>
        </div>
    </div>
</div>

        <section id="trabajos" class="section-container seccion-trabajos-cards">
            <h2 class="section-title text-white">Nuestros Trabajos Realizados</h2>
            <div class="trabajos-cards-container">
                <div class="trabajo-card">
                    <div class="card-image-wrapper">
                        <img src="img/pantalla oled.jpg" alt="Reemplazo de Pantalla iPhone 15 Pro" class="card-image">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Reemplazo de Pantalla iPhone 15 Pro</h3>
                        <p class="card-description">Nuestro servicio de reemplazo de pantalla garantiza el uso de repuestos originales y un ajuste perfecto.</p>
                        <a href="detalles1.html" class="btn-card-details">Ver detalles</a>
                    </div>
                </div>

                <div class="trabajo-card">
                    <div class="card-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?q=80&w=600" alt="Reparación de Placa Base de MacBook" class="card-image">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Reparación de Placa Base de MacBook</h3>
                        <p class="card-description">Soluciones complejas de nivel 3 y micro-soldadura para problemas de encendido y video.</p>
                        <a href="detalles1.html" class="btn-card-details">Ver detalles</a>
                    </div>
                </div>

                <div class="trabajo-card">
                    <div class="card-image-wrapper">
                        <img src="img/limpieza de telefono.jpeg" alt="Recuperación de Datos y Componentes" class="card-image">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Recuperación de Datos y Componentes</h3>
                        <p class="card-description">Recuperación de datos de dispositivos dañados y reemplazo de puertos de carga.</p>
                        <a href="detalles1.html" class="btn-card-details">Ver detalles</a>
                    </div>
                </div>

                <div class="trabajo-card">
                    <div class="card-image-wrapper">
                        <img src="img/bateria.jpg" alt="Reemplazo de Batería" class="card-image">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Reemplazo de Batería</h3>
                        <p class="card-description">Mejora el rendimiento de tu dispositivo con baterías certificadas.</p>
                        <a href="detalles1.html" class="btn-card-details">Ver detalles</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="modalContacto">
        <div class="modal-bg-animation"></div>
        <div class="modal-card animate__animated animate__zoomIn">
            <button class="btn-close" onclick="toggleModal(false)" title="Cerrar">&times;</button>
            <h2 class="modal-title">Iniciar Diagnóstico</h2>
            <p class="modal-subtitle">Selecciona la gama de tu equipo para acelerar el proceso.</p>
            
            <!-- contacto.php está en backend/ -->
            <form action="../backend/contacto.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <select id="marca" name="marca" onchange="actualizarModelos()" required>
                            <option value="">Seleccionar...</option>
                            <option value="samsung">Samsung</option>
                            <option value="motorola">Motorola</option>
                            <option value="xiaomi">Xiaomi</option>
                            <option value="iphone">iPhone (Apple)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo (Gamas Actuales)</label>
                        <select id="modelo" name="modelo" required disabled>
                            <option value="">Elige la marca primero</option>
                        </select>
                    </div>
                </div>

                
                <div class="form-group mb-20">
                    <label for="mensaje">Falla que presenta</label>
                    <textarea id="mensaje" name="mensaje" rows="3" placeholder="Ej: La pantalla táctil no responde en la parte superior..." required></textarea>
                </div>

                <button type="submit" class="btn-primary"><i class="fas fa-paper-plane"></i> Enviar a Laboratorio</button>
            </form>
        </div>
    </div>

    <button class="fab animate__animated animate__bounceIn" onclick="toggleModal(true)" aria-label="Solicitar Reparación">
        <i class="fas fa-tools"></i> Solicitar Reparación
    </button>
    
    <a href="https://wa.me/527271163526?text=Hola%20Movilian,%20necesito%20presupuesto%20para%20reparar%20mi%20teléfono" class="btn-whatsapp" target="_blank" id="whatsapp-link" aria-label="Chat de WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>MOVILIAN</h3>
                <p>Expertos en devolverle la vida a tu tecnología con diagnósticos de alta precisión.</p>
            </div>
            <div class="footer-section">
                <h4>Marcas Populares</h4>
                <p>iPhone 15/16 Pro Max</p>
                <p>Samsung S24 Ultra</p>
                <p>Xiaomi 14 Series</p>
            </div>
            <div class="footer-section">
                <h4>Contacto Directo</h4>
                <p><i class="fas fa-envelope"></i> luisalbertolanda04@gmail.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Laboratorio Central - CDMX</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 Movilian Tech Solutions. Todos los derechos reservados. | Diseñado para Semestre 6.
        </div>
    </footer>
    

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>

        // 2. MODAL DE CONTACTO (Con mejora para cerrar haciendo clic afuera)
        const modalContacto = document.getElementById('modalContacto');
        
        function toggleModal(show) { 
            modalContacto.style.display = show ? 'flex' : 'none'; 
        }

        // Cierra el modal si se hace clic en el fondo animado
        window.addEventListener('click', (e) => {
            if (e.target === modalContacto) {
                toggleModal(false);
            }
        });

        // 3. BASE DE DATOS DE MODELOS
        const modelosDB = {
            samsung: ["Galaxy S26 Ultra", "Galaxy S26+", "Galaxy S25 FE", "Galaxy Z Fold 7", "Galaxy Z Flip 7", "Galaxy A56", "Galaxy A36"],
            motorola: ["Edge 60 Ultra", "Edge 60 Pro", "Razr 60 Ultra", "Moto G86", "Moto G64", "ThinkPhone 2"],
            xiaomi: ["Xiaomi 16 Pro", "Xiaomi 16", "Redmi Note 15 Pro+", "Redmi Note 15", "Poco F7 Pro", "Poco X7"],
            iphone: ["iPhone 17 Pro Max", "iPhone 17 Pro", "iPhone 17", "iPhone 16 Pro", "iPhone 16", "iPhone SE (4ta Gen)"]
        };

        function actualizarModelos() {
            const marca = document.getElementById("marca").value;
            const selectModelo = document.getElementById("modelo");
            
            selectModelo.innerHTML = '<option value="">Seleccionar Modelo...</option>';
            
            if (marca !== "") {
                selectModelo.disabled = false;
                modelosDB[marca].forEach(mod => {
                    let opt = document.createElement("option");
                    opt.value = mod; opt.text = mod;
                    selectModelo.add(opt);
                });
            } else {
                selectModelo.disabled = true;
            }
        }

        // 4. LÓGICA DEL MODO ADMINISTRADOR (Contraseña: 1234)
        let isAdmin = false;
        function activarAdmin() {
            if(!isAdmin) {
                Swal.fire({
                    title: 'Acceso Administrativo',
                    input: 'password',
                    inputPlaceholder: 'Ingresa la contraseña',
                    showCancelButton: true,
                    confirmButtonText: 'Acceder',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#0f172a'
                }).then((result) => {
                    if (result.value === "1234") { 
                        isAdmin = true;
                        Swal.fire('¡Modo Admin Activado!', 'Ahora puedes borrar comentarios.', 'success');
                        document.querySelectorAll('.btn-delete').forEach(btn => btn.style.display = 'block');
                        document.querySelector('.admin-btn').style.color = '#ef4444';
                    } else if (result.value) {
                        Swal.fire('Error', 'Contraseña incorrecta', 'error');
                    }
                });
            } else {
                isAdmin = false;
                Swal.fire('Desconectado', 'Modo Admin desactivado.', 'info');
                document.querySelectorAll('.btn-delete').forEach(btn => btn.style.display = 'none');
                document.querySelector('.admin-btn').style.color = '#94a3b8';
            }
        }

        // 5. SISTEMA DE COMENTARIOS REAL (CONECTADO A PHP)
        document.addEventListener('DOMContentLoaded', cargarComentarios);

        function cargarComentarios() {
            // comentarios.php está en backend/
            fetch('../backend/comentarios.php')
                .then(respuesta => respuesta.json())
                .then(datos => {
                    const lista = document.getElementById('lista-comentarios');
                    lista.innerHTML = ''; 
                    
                    if(datos.length === 0) {
                        lista.innerHTML = '<p style="text-align:center; color:#64748b;">Sé el primero en opinar.</p>';
                    }

                    datos.forEach(com => {
                        lista.innerHTML += `
                        <div class="comment-box animate__animated animate__fadeIn" id="com-${com.id}">
                            <div>
                                <div class="comment-user"><i class="fas fa-user-circle"></i> ${com.nombre} <span class="comment-date">- ${com.fecha}</span></div>
                                <p>${com.texto}</p>
                            </div>
                            <button class="btn-delete" style="${isAdmin ? 'display:block;' : 'display:none;'}" onclick="borrarComentario(${com.id})" title="Borrar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                    });
                })
                .catch(error => console.log("Nota: El fetch a comentarios.php fallará si no estás en un servidor local."));
        }

        function enviarComentario(e) {
            e.preventDefault();
            const nombre = document.getElementById('comNombre').value;
            const texto = document.getElementById('comTexto').value;
            
            const formData = new FormData();
            formData.append('accion', 'guardar');
            formData.append('nombre', nombre);
            formData.append('texto', texto);

            // backend
            fetch('../backend/comentarios.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'ok') {
                        document.getElementById('formComentario').reset();
                        cargarComentarios(); 
                        Swal.fire('¡Publicado!', 'Tu opinión ha sido guardada.', 'success');
                    }
                })
                .catch(error => Swal.fire('Error', 'Necesitas un servidor PHP para enviar datos.', 'warning'));
        }

        function borrarComentario(id) {
            Swal.fire({
                title: '¿Eliminar comentario?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, borrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('accion', 'borrar');
                    formData.append('id', id);

                    // backend
                    fetch('../backend/comentarios.php', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'ok') {
                                cargarComentarios(); 
                                Swal.fire('Borrado', 'El comentario fue eliminado.', 'success');
                            }
                        });
                }
            });
        }

       // CARGAR IMÁGENES DEL CARRUSEL DESDE MYSQL (ver_carrusel.php está en backend/)
document.addEventListener("DOMContentLoaded", () => {

    fetch("../backend/ver_carrusel.php")
    .then(res => res.json())
    .then(data => {

        const wrapper = document.getElementById("carrusel-dinamico");

        data.forEach(slide => {

            const div = document.createElement("div");
            div.classList.add("swiper-slide");

            div.innerHTML = `
                <img src="${slide.imagen}">
                <div class="slide-content">
                    <h2>${slide.titulo}</h2>
                    <p>${slide.subtitulo}</p>
                </div>
            `;

            wrapper.appendChild(div);

        });

        // INICIAR SWIPER DESPUÉS DE CARGAR LAS IMÁGENES
        new Swiper(".mySwiper", {
            loop: true,
            effect: "fade",
            autoplay: {
                delay: 4000,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            }
        });

    })
    .catch(error => console.log("Error cargando carrusel:", error));

});
    </script>
</body>
</html>