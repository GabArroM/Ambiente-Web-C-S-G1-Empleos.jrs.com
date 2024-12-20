
drop database ProyectoWebQ3;

CREATE DATABASE ProyectoWebQ3;

USE ProyectoWebQ3;

CREATE TABLE Usuarios (
    ID_Usuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Contraseña VARCHAR(100) NOT NULL,
    TipoUsuario ENUM('Junior', 'Empleador') NOT NULL,
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Tabla Perfil_Junior
CREATE TABLE Perfil_Junior (
    ID_PerfilJunior INT AUTO_INCREMENT PRIMARY KEY,
    ID_Usuario INT NOT NULL,
    Educacion VARCHAR(200),
    Habilidades TEXT,
    CV_URL VARCHAR(255),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario)
);

CREATE TABLE Empleadores (
    ID_Empleador INT AUTO_INCREMENT PRIMARY KEY,
    ID_Usuario INT NOT NULL,
    Empresa VARCHAR(100),
    Ubicacion VARCHAR(100),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario)
);

CREATE TABLE Ofertas_Empleo (
    ID_Oferta INT AUTO_INCREMENT PRIMARY KEY,
    ID_Empleador INT NOT NULL,
    Titulo VARCHAR(100),
    Descripcion TEXT,
    Categoria VARCHAR(100),
    TipoContrato VARCHAR(50),
    RangoSalarial VARCHAR(50),
    FechaPublicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Empleador) REFERENCES Empleadores(ID_Empleador)
);

CREATE TABLE Aplicaciones (
    ID_Aplicacion INT AUTO_INCREMENT PRIMARY KEY,
    ID_Oferta INT NOT NULL,
    ID_PerfilJunior INT NOT NULL,
    EstadoAplicacion ENUM('Aplicado', 'En revisión', 'Rechazado') NOT NULL,
    FechaAplicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Oferta) REFERENCES Ofertas_Empleo(ID_Oferta),
    FOREIGN KEY (ID_PerfilJunior) REFERENCES Perfil_Junior(ID_PerfilJunior)
);


CREATE TABLE Evaluaciones (
    ID_Evaluacion INT AUTO_INCREMENT PRIMARY KEY,
    ID_Oferta INT NOT NULL,
    ID_UsuarioEvaluador INT NOT NULL,
    ID_UsuarioEvaluado INT NOT NULL,
    Calificacion INT NOT NULL,
    Comentarios TEXT,
    FechaEvaluacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Oferta) REFERENCES Ofertas_Empleo(ID_Oferta),
    FOREIGN KEY (ID_UsuarioEvaluador) REFERENCES Usuarios(ID_Usuario),
    FOREIGN KEY (ID_UsuarioEvaluado) REFERENCES Usuarios(ID_Usuario)
);

alter table perfil_junior modify column CV_URL LongBlob;
ALTER TABLE Perfil_Junior
ADD COLUMN Telefono VARCHAR(15) NULL;

alter table Aplicaciones
modify column EstadoAplicacion enum ('Aplicado', 'En revisión', 'Rechazado','Aprobado') not null;


drop table Evaluaciones;
 
CREATE TABLE Comentarios (
    ID_Comentario INT AUTO_INCREMENT PRIMARY KEY,
    ID_Usuario INT NOT NULL,
    ID_Empleador INT NOT NULL,
    Comentario TEXT NOT NULL,
    FechaComentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario),
    FOREIGN KEY (ID_Empleador) REFERENCES Empleadores(ID_Empleador)
);
 
ALTER TABLE Comentarios ADD COLUMN ID_PerfilJunior INT;
 
 
ALTER TABLE Comentarios
ADD CONSTRAINT fk_perfilJunior FOREIGN KEY (ID_PerfilJunior) REFERENCES Perfil_Junior(ID_PerfilJunior);


ALTER TABLE comentarios
DROP FOREIGN KEY fk_perfilJunior;

ALTER TABLE comentarios
ADD CONSTRAINT fk_perfilJunior FOREIGN KEY (ID_PerfilJunior) REFERENCES perfil_junior(ID_PerfilJunior)
ON DELETE CASCADE; 