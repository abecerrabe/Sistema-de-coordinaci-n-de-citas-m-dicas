-- DROP DATABASE hospital;

-- creacion de la base de datos
CREATE DATABASE hospital;

USE hospital;


--creacion usuario
CREATE TABLE usuario(
    id_usuario int auto_increment primary key,
    nombre_completo varchar(100) not null,
    correo_electronico varchar(100) not null unique,
    password_usuario varchar(100) not null,
    numero_cedula bigint not null unique, --falta quisiera cambiarlo por un varchar
    tipo_permiso ENUM('administrador', 'paciente', 'medico') not null,
    estado ENUM('activo','inactivo') not null default 'activo'
)

--creacion paciente
CREATE TABLE paciente(
    id_paciente int auto_increment primary key,
    id_usuario int not null,
    tipo_sangre varchar(4) not null,
    --falta discapacidad y alergia
)
