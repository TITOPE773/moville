<?php
session_start();

// Protege el panel: si no hay sesión, manda a login (ajusta la ruta si tu login está en otro lugar)
if (!isset($_SESSION['usuario'])) {
    header('Location: ../frontend/login.php'); // <-- si tu login está en otra ruta, cámbiala
    exit();
}

// opcional: usar la conexión para estadísticas rápidas (si ../backend/conexion.php existe)
$stats = [
    'imagenes' => 0,
    'usuarios' => 0,
    'comentarios' => 0
];

if (file_exists(__DIR__ . '/../backend/conexion.php')) {
    include __DIR__ . '/../backend/conexion.php';
    // $conexion (o $conn) depende de tu archivo conexion.php; intento detectarlo
    if (isset($conexion)) $db = $conexion;
    elseif (isset($conn)) $db = $conn;
    else $db = null;

    if ($db) {
        try {
            $r = $db->query("SELECT COUNT(*) as c FROM carrusel_inicio");
            $stats['imagenes'] = $r ? ((int)$r->fetch_assoc()['c']) : 0;
        } catch (Exception $e){}
        try {
            $r = $db->query("SELECT COUNT(*) as c FROM usuarios");
            $stats['usuarios'] = $r ? ((int)$r->fetch_assoc()['c']) : 0;
        } catch (Exception $e){}
        try {
            $r = $db->query("SELECT COUNT(*) as c FROM opiniones");
            $stats['comentarios'] = $r ? ((int)$r->fetch_assoc()['c']) : 0;
        } catch (Exception $e){}
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin • Movilian</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      --sidebar-bg:#0f172a;
      --accent: #0ea5e9;
    }
    body { min-height:100vh; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:#f8fafc; }
    .wrap { display:flex; gap:20px; }
    /* Sidebar */
    .sidebar {
      width:260px;
      background: linear-gradient(180deg,var(--sidebar-bg), #071028);
      color: #e6eef8;
      min-height:100vh;
      padding:22px;
      position:sticky;
      top:0;
    }
    .brand { font-weight:800; font-size:1.4rem; letter-spacing:-1px; }
    .brand span { color:var(--accent); }
    .nav-item a { color: #cfe8fb; text-decoration:none; display:flex; align-items:center; gap:10px; padding:10px; border-radius:8px; }
    .nav-item a:hover, .nav-item a.active { background: rgba(255,255,255,0.04); color: #fff; }
    .sidebar .small { color:#94a3b8; font-size:0.85rem; margin-top:8px; }

    /* Content */
    .main {
      flex:1;
      padding:28px;
    }

    /* Dashboard cards */
    .card-stat { border-left: 4px solid var(--accent); }

    /* Imagenes table */
    .thumb { width:96px; height:64px; object-fit:cover; border-radius:8px; border:1px solid #e6eef8; }

    /* Modal custom */
    .modal-card { border-radius:12px; }

    /* Responsive */
    @media (max-width:900px){
      .wrap { flex-direction:column; }
      .sidebar { width:100%; display:flex; gap:12px; overflow:auto; }
    }

  </style>
</head>
<body>
<div class="wrap">
  <aside class="sidebar">
    <div class="brand">MOVI<span>LIAN</span></div>
    <div class="small mb-3">Panel de administración</div>

    <nav class="nav flex-column mb-3">
      <div class="nav-item"><a href="#" id="menu-dashboard" class="active"><i class="fa-solid fa-chart-simple"></i> Dashboard</a></div>
      <div class="nav-item"><a href="#" id="menu-imagenes"><i class="fa-solid fa-image"></i> Imágenes</a></div>
      <div class="nav-item"><a href="#" id="menu-usuarios"><i class="fa-solid fa-users"></i> Usuarios</a></div>
      <div class="nav-item"><a href="#" id="menu-comentarios"><i class="fa-solid fa-comment-dots"></i> Opiniones</a></div>
      <div class="nav-item mt-3"><a href="../backend/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></div>
    </nav>

    <hr style="border-color:rgba(255,255,255,0.03)">

    <div class="small">Usuario</div>
    <div style="font-weight:700; margin-bottom:8px;"><?= htmlspecialchars($_SESSION['usuario']); ?></div>
    <div class="small">Atajos</div>
    <div class="mt-2">
      <button id="btn-subir-quick" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-upload"></i> Subir imagen</button>
    </div>
  </aside>

  <main class="main">
    <!-- Dashboard -->
    <section id="panel-dashboard">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard</h3>
        <small class="text-muted">Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']); ?></strong></small>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card card-stat shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="text-muted">Imágenes en carrusel</h6>
                  <h3><?= $stats['imagenes']; ?></h3>
                </div>
                <div class="text-end">
                  <i class="fa-solid fa-image fa-2x text-muted"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-stat shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="text-muted">Usuarios</h6>
                  <h3><?= $stats['usuarios']; ?></h3>
                </div>
                <div class="text-end">
                  <i class="fa-solid fa-users fa-2x text-muted"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-stat shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h6 class="text-muted">Opiniones</h6>
                  <h3><?= $stats['comentarios']; ?></h3>
                </div>
                <div class="text-end">
                  <i class="fa-solid fa-comment fa-2x text-muted"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- row -->

      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title">Actividad reciente</h5>
          <p class="text-muted">Aquí puedes revisar acciones recientes — por ejemplo imágenes subidas o comentarios nuevos. (Puedes integrar logs en el backend y mostrarlos aquí.)</p>
        </div>
      </div>
    </section>

    <!-- Imágenes -->
    <section id="panel-imagenes" style="display:none;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Gestionar imágenes</h3>
        <div>
          <button id="open-upload" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Subir Imagen</button>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="tabla-imagenes">
              <thead>
                <tr>
                  <th>Vista</th>
                  <th>Título</th>
                  <th>Subtítulo</th>
                  <th>ID</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- filas se inyectan por JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- Usuarios (placeholder) -->
    <section id="panel-usuarios" style="display:none;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Usuarios</h3>
        <small class="text-muted">Funcionalidad para ver/eliminar/rol aún por implementar en backend.</small>
      </div>

      <div class="card">
        <div class="card-body">
          <p class="text-muted">Puedes crear `admin/usuarios.php` y endpoints en `backend/` para listar/editar usuarios.</p>
        </div>
      </div>
      <h2>Usuarios</h2>
<!-- Formulario para crear usuario -->
<div class="card mb-3">
  <div class="card-body">
    <h5>Crear nuevo usuario</h5>
    <form id="formCrearUsuario">
      <div class="mb-2">
        <label class="form-label">Usuario</label>
        <input name="usuario" required class="form-control">
      </div>
      <div class="mb-2">
        <label class="form-label">Email</label>
        <input name="email" required type="email" class="form-control">
      </div>
      <div class="mb-2">
        <label class="form-label">Contraseña</label>
        <input name="password" required type="password" class="form-control">
      </div>
      <button class="btn btn-primary" type="submit">Crear usuario</button>
    </form>
  </div>
</div>

<!-- Tabla de usuarios (ya la tienes) -->
<table id="tablaUsuarios" class="table table-sm">
  <thead><tr><th>ID</th><th>Usuario</th><th>Email</th><th>Acción</th></tr></thead>
  <tbody></tbody>
</table>

<script>
async function cargarUsuarios(){
  try {
    const res = await fetch('/movile/admin/ver_usuarios.php');
    const data = await res.json();
    const tbody = document.querySelector('#tablaUsuarios tbody');
    tbody.innerHTML = '';
    data.forEach(u => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${u.id}</td>
        <td>${escapeHtml(u.usuario)}</td>
        <td>${escapeHtml(u.email)}</td>
        <td>
          <button class="btn btn-sm btn-danger btn-delete-user" data-id="${u.id}">Eliminar</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch (e) {
    console.error(e);
  }
}

// manejo del submit para crear usuario
document.getElementById('formCrearUsuario').addEventListener('submit', async (e) => {
  e.preventDefault();
  const form = e.target;
  const fd = new FormData(form);
  try {
    const res = await fetch('/movile/admin/agregar_usuario.php', {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
    });
    const json = await res.json();
    if (json.status === 'ok') {
      Swal.fire('Creado','Usuario creado correctamente','success');
      form.reset();
      cargarUsuarios();
    } else {
      Swal.fire('Error', json.message || 'No fue posible crear usuario','error');
    }
  } catch (err) {
    console.error(err);
    Swal.fire('Error','Error de conexión','error');
  }
});

// Delegación para eliminar usuario (usa POST para mayor seguridad)
document.addEventListener('click', async (e) => {
  if (!e.target.classList.contains('btn-delete-user')) return;
  const id = e.target.dataset.id;
  const confirmed = await Swal.fire({
    title: 'Eliminar usuario?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar'
  });
  if (!confirmed.isConfirmed) return;

  try {
    const fd = new FormData();
    fd.append('id', id);
    const res = await fetch('/movile/admin/eliminar_usuario.php', {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
    });
    const json = await res.json();
    if (json.status === 'ok') {
      Swal.fire('Eliminado','Usuario eliminado','success');
      cargarUsuarios();
    } else {
      Swal.fire('Error', json.message || 'No se pudo eliminar','error');
    }
  } catch (err) {
    console.error(err);
    Swal.fire('Error','Error de conexión','error');
  }
});

// inicializar
cargarUsuarios();
</script>

    <!-- Comentarios (placeholder) -->
    <section id="panel-comentarios" style="display:none;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Opiniones</h3>
        <small class="text-muted">Listar y moderar opiniones.</small>
      </div>

      <div class="card">
        <div class="card-body">
          <p class="text-muted">Aquí podrías consumir `../backend/comentarios.php` o crear un endpoint que devuelva JSON con comentarios.</p>
        </div>
      </div>
      <h3>Opiniones de usuarios</h3>

<table class="table" id="tablaComentarios">
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Comentario</th>
<th>Acciones</th>
</tr>
</thead>

<tbody></tbody>
</table>
    </section>
  </main>
</div>

<!-- MODAL: subir imagen -->
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-card">
      <div class="modal-header">
        <h5 class="modal-title">Subir nueva imagen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="uploadForm" enctype="multipart/form-data" method="POST" action="../backend/upload.php">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Título</label>
            <input name="titulo" class="form-control" required placeholder="Ej: Reparación de Laptop">
          </div>
          <div class="mb-3">
            <label class="form-label">Subtítulo (opcional)</label>
            <input name="subtitulo" class="form-control" placeholder="Ej: Servicio técnico especializado">
          </div>
          <div class="mb-3">
            <label class="form-label">Seleccionar imagen</label>
            <input name="imagen" type="file" accept="image/*" class="form-control" required>
          </div>
          <div id="upload-feedback" class="small text-muted"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar en base de datos</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Navegación interna
  const menu = {
    dashboard: document.getElementById('menu-dashboard'),
    imagenes: document.getElementById('menu-imagenes'),
    usuarios: document.getElementById('menu-usuarios'),
    comentarios: document.getElementById('menu-comentarios')
  };
  const panels = {
    dashboard: document.getElementById('panel-dashboard'),
    imagenes: document.getElementById('panel-imagenes'),
    usuarios: document.getElementById('panel-usuarios'),
    comentarios: document.getElementById('panel-comentarios')
  };

  function showPanel(name){
    // active menu
    Object.values(menu).forEach(n => n.classList.remove('active'));
    Object.keys(menu).forEach(k => {
      if (k===name) menu[k].classList.add('active');
    });
    // panels
    Object.values(panels).forEach(p => p.style.display='none');
    panels[name].style.display = 'block';
    if (name === 'imagenes') loadImages();
    if (name === 'comentarios') {
    cargarComentarios();
  }


  }

  menu.dashboard.addEventListener('click', (e)=>{ e.preventDefault(); showPanel('dashboard'); });
  menu.imagenes.addEventListener('click', (e)=>{ e.preventDefault(); showPanel('imagenes'); });
  menu.usuarios.addEventListener('click', (e)=>{ e.preventDefault(); showPanel('usuarios'); });
  menu.comentarios.addEventListener('click', (e)=>{ e.preventDefault(); showPanel('comentarios'); });

  // abrir modal
  const modalUploadEl = document.getElementById('modalUpload');
  const bsModal = new bootstrap.Modal(modalUploadEl);
  document.getElementById('open-upload').addEventListener('click', ()=> bsModal.show());
  document.getElementById('btn-subir-quick').addEventListener('click', ()=> bsModal.show());

  // cargar imagenes desde backend/ver_carrusel.php
  async function loadImages(){
    const tbody = document.querySelector('#tabla-imagenes tbody');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>';
    try {
      const res = await fetch('../backend/ver_carrusel.php?nocache=' + Date.now());
      // intenta parsear JSON (tu ver_carrusel.php ya devuelve JSON)
      const data = await res.json();
      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay imágenes.</td></tr>';
        return;
      }
      tbody.innerHTML = '';
      data.forEach(slide => {
        const tr = document.createElement('tr');
        const imgSrc = slide.imagen || ''; // debe venir como data:... o url
        tr.innerHTML = `
          <td><img src="${imgSrc}" class="thumb" alt=""></td>
          <td>${escapeHtml(slide.titulo || '')}</td>
          <td>${escapeHtml(slide.subtitulo || '')}</td>
          <td>${escapeHtml(slide.id ?? '')}</td>
          <td>
            <button class="btn btn-sm btn-danger btn-delete" data-id="${slide.id}"><i class="fa-solid fa-trash"></i> Eliminar</button>
          </td>
        `;
        tbody.appendChild(tr);
      });

      // attach delete handlers
      document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const id = e.currentTarget.dataset.id;
          confirmDelete(id);
        });
      });

    } catch (err) {
      console.error('Error cargando imágenes:', err);
      tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error cargando imágenes</td></tr>';
    }
  }

  // Confirmar y eliminar (ruta absoluta /movile/admin/eliminar_imagen.php)
  function confirmDelete(id){
    if (!id) {
      Swal.fire('Error','ID inválido','error');
      return;
    }

    Swal.fire({
      title: '¿Eliminar esta imagen?',
      text: 'Esta acción es irreversible.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const resp = await fetch('/movile/admin/eliminar_imagen.php?id=' + encodeURIComponent(id), {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
              'Accept': 'application/json'
            }
          });

          // Intentamos parsear JSON, si falla y resp.ok==true asumimos éxito (fallback)
          let json;
          try {
            json = await resp.json();
          } catch (e) {
            json = resp.ok ? { status: 'ok' } : { status: 'error', message: 'Respuesta inválida del servidor' };
          }

          if (json && json.status === 'ok') {
            Swal.fire({ icon:'success', title:'Imagen eliminada', timer:1200, showConfirmButton:false });
            setTimeout(loadImages, 400);
          } else {
            Swal.fire({ icon:'error', title:'Error', text: json.message || 'No fue posible eliminar la imagen.' });
          }

        } catch (err) {
          console.error(err);
          Swal.fire({ icon:'error', title:'Error del servidor' });
        }
      }
    });
  }

  // Interceptar uploadForm para enviar por AJAX (fallback a envío normal)
  const uploadForm = document.getElementById('uploadForm');
  uploadForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(uploadForm);
    const feedback = document.getElementById('upload-feedback');
    feedback.textContent = 'Subiendo...';
    try {
      const res = await fetch(uploadForm.action, { method:'POST', body: fd, credentials: 'same-origin' });
      // intenta JSON
      let json = null;
      try { json = await res.json(); } catch {}
      if (json && json.status === 'ok') {
        feedback.textContent = 'Subida correcta';
        Swal.fire('Guardada','Imagen subida correctamente.','success');
        bsModal.hide();
        loadImages();
      } else {
        // si no devolvió JSON, puede que upload.php haga redirect. Recarcaremos la lista igualmente.
        if (res.ok) {
          feedback.textContent = 'Subida completada (sin respuesta JSON).';
          Swal.fire('Guardada','Imagen subida (respuesta no JSON).','success');
          bsModal.hide();
          setTimeout(loadImages, 800);
        } else {
          feedback.textContent = 'Error al subir';
          Swal.fire('Error','No se pudo subir la imagen. Revisa el servidor.','error');
        }
      }
    } catch (err) {
      console.error(err);
      feedback.textContent = 'Error de conexión';
      // fallback: submit normal (quita comentario si prefieres)
      // uploadForm.submit();
      Swal.fire('Error','Problema en la conexión. Puedes intentar con el envío normal de formulario.', 'error');
    }
  });
  //usuarios
  fetch("/movile/admin/ver_usuarios.php")
.then(res => res.json())
.then(data => {

const tabla = document.querySelector("#tablaUsuarios tbody");

data.forEach(user => {

tabla.innerHTML += `
<tr>
<td>${user.id}</td>
<td>${user.usuario}</td>
<td>${user.email}</td>
<td>
<button class="deleteUser" data-id="${user.id}">
Eliminar
</button>
</td>
</tr>
`;

});
    //eleminar usuarios 
    document.addEventListener("click", function(e){

if(e.target.classList.contains("deleteUser")){

const id = e.target.dataset.id;

fetch("/movile/admin/eliminar_usuario.php?id="+id)
.then(res => res.json())
.then(data => {

if(data.status === "ok"){
alert("Usuario eliminado");
location.reload();
}

});

}

});

});

  // small helper
  function escapeHtml(s){ if(!s && s !== 0) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  // inicial
  showPanel('dashboard');

  function cargarComentarios(){

fetch("/movile/admin/ver_comentarios.php")
.then(res => res.json())
.then(data => {

const tabla = document.querySelector("#tablaComentarios tbody");
tabla.innerHTML = "";

data.forEach(c => {

tabla.innerHTML += `
<tr>
<td>${c.id}</td>
<td>${c.nombre}</td>
<td>
<input value="${c.comentario}" id="coment${c.id}" class="form-control">
</td>
<td>

<button onclick="editarComentario(${c.id})" class="btn btn-warning btn-sm">
Editar
</button>

<button onclick="eliminarComentario(${c.id})" class="btn btn-danger btn-sm">
Eliminar
</button>

</td>
</tr>
`;

});

});

}

function editarComentario(id){

let comentario = document.getElementById("coment"+id).value;

let fd = new FormData();
fd.append("id",id);
fd.append("comentario",comentario);

fetch("/movile/admin/editar_comentario.php",{
method:"POST",
body:fd
})
.then(res=>res.json())
.then(data=>{

if(data.status=="ok"){
Swal.fire("Actualizado","Comentario editado","success");
}

});

}

function eliminarComentario(id){

Swal.fire({
title:"Eliminar comentario?",
icon:"warning",
showCancelButton:true
}).then((result)=>{

if(result.isConfirmed){

let fd = new FormData();
fd.append("id",id);

fetch("/movile/admin/eliminar_comentario.php",{
method:"POST",
body:fd
})
.then(res=>res.json())
.then(data=>{

if(data.status=="ok"){
Swal.fire("Eliminado","","success");
cargarComentarios();
}

});

}

});

}



</script>
</body>
</html>