<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Módulo Solicitantes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/ModSolicitantes.css">
</head>

<body>


    <main>
    <?php 
    session_start();
    include("nav.php")
     ?>

        <section class="perfilSolicitante">
            <h2>Creación de Perfil Solicitante</h2>
            <div class="dropdown">
                <button class="dropbtn">Editar Perfil</button>
                <div class="dropdown-content">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" placeholder="Ingresa tu nombre completo">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" placeholder="Ingresa tu correo electrónico">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" placeholder="Ingresa tu teléfono">
                    <label for="cv">Adjuntar CV (PDF, DOCX)</label>
                    <input type="file" id="cv" accept=".pdf,.docx">
                    <button type="submit">Actualizar Perfil</button>
                </div>
            </div>
        </section>

        <section class="postulaciones">
            <h2>Postulaciones</h2>
            <div class="dropdown">
                <button class="dropbtn">Ver Postulaciones</button>
                <div class="dropdown-content">
                    <p>En este apartado podrás ver los puestos a los que has aplicado y su estado.</p>
                    <ul>
                        <li>Puesto: Desarrollador Web - Estado: En revisión</li>
                        <li>Puesto: Diseñador Gráfico - Estado: Postulación rechazada</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="estadisticas">
            <h2>Seguimiento de Estadísticas</h2>
            <div class="boxEstadisticas">
                <p>Aquí se mostrarán las estadísticas de las postulaciones y empleos aplicados.</p>
            </div>
        </section>

        <section class="busquedaEmpleo">
            <h2>Búsqueda de Empleo</h2>
            <div class="boxBusqueda">
                <p>Aquí podrás buscar empleos disponibles.</p>
            </div>
        </section>
    </main>
    <footer>
        Derechos reservados Grupo#1
    </footer>
</body>

</html>