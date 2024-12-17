<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Módulo Solicitantes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/ModSolicitante.css">
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
                    <label for="nombre">Nombre Completo</label><br>
                    <input type="text" id="nombre" placeholder="Ingresa tu nombre completo"><br>
                    <label for="correo">Correo Electrónico</label><br>
                    <input type="email" id="correo" placeholder="Ingresa tu correo electrónico"><br>
                    <label for="telefono">Teléfono</label><br><br>
                    <input type="tel" id="telefono" placeholder="Ingresa tu teléfono"><br>
                    <label for="nombre">Educación</label><br>
                    <input type="text" id="Educacion" placeholder="Ingresa tu nivel de educación"><br>
                    <label for="nombre">Habilidades</label><br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50" placeholder="Ingresa tus habilidades" required></textarea><br>
                    <label for="cv">Adjuntar CV (PDF, DOCX)</label><br>
                    <input type="file" id="cv" accept=".pdf,.docx"><br>
                    <button type="submit">Actualizar Perfil</button>
                    <button type="submit">Borrar Perfil</button><br>
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