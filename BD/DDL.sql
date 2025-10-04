-- DROP DATABASE hospital;

-- creacion de la base de datos
USE hospital;


--creacion usuario
CREATE TABLE usuario(
    id int auto_increment primary key,
    nombre_completo varchar(100) not null,
    correo_electronico varchar(100) not null unique,
    telefono VARCHAR(20) not null unique,
    password_usuario varchar(100) not null,
    numero_cedula int not null unique, 
    tipo_permiso ENUM('administrador', 'paciente', 'medico') not null,
    estado ENUM('activo','inactivo') not null default 'activo'
);

--creacion paciente
CREATE TABLE paciente(
    id int auto_increment primary key,
    id_usuario int not null,
    tipo_sangre ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') not null,
    alergia text,
    discapacidad text,
    foreign key (id_usuario) references usuario(id)
);

--creacion cargo
CREATE TABLE cargo(
    id int auto_increment primary key,
    nombre_cargo varchar(100),
    descripcion_cargo varchar(100),
    estados varchar(10)
);

--creacion medico
CREATE TABLE medico(
    id int auto_increment primary key,
    id_usuario int not null,
    id_cargo int not null,
    horario_atencion ENUM('dia','tarde') not null,
    foreign key (id_usuario) references usuario(id),
    foreign key (id_cargo) references cargo(id)
);

--creacion disponibilidad_horaria
CREATE TABLE disponibilidad_horaria(
    id int auto_increment primary key,
    id_medico int not null,
    hora_llegada time not null,
    hora_finalizacion time not null,
    foreign key (id_medico) references medico(id)
);

--creacion cita
CREATE TABLE cita(
    id int auto_increment primary key,
    numero_tramite VARCHAR(25),
    id_paciente int not null,
    id_disponibilidad_horaria int not null,
    fecha_cita date not null,
    tipo_cita varchar(100) not null,
    prioridad ENUM('baja','moderada', 'alta') not null,
    estado ENUM('pendiente','cancelado','inasistencia','completado') not null default 'pendiente',
    foreign key (id_paciente) references paciente(id),
    foreign key (id_disponibilidad_horaria) references disponibilidad_horaria(id)
);


--creacion diagnostico
CREATE TABLE diagnostico(
    id int auto_increment primary key,
    id_cita int not null,
    remision TEXT,
    descripcion_remision TEXT,
    formula_asignada TEXT,
    descripcion_general_cita TEXT NOT NULL,
    tipo_diagnostico TEXT NOT NULL,
    foreign key (id_cita) references cita(id)
);

--creacion historial_medico
CREATE TABLE historial_medico(
    id int auto_increment primary key,
    id_paciente int not null,
    id_diagnostico int not null,
    fecha_registro date not null,
    hora_registro time not null,
    estado ENUM('iniciado', 'en proceso','cerrado') not null,
    Foreign Key (id_paciente) REFERENCES paciente(id),
    foreign key (id_diagnostico) references diagnostico(id)
);
