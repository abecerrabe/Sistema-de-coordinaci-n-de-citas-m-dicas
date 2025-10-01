-- DROP DATABASE hospital;

-- creacion de la base de datos
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
);

--creacion paciente
CREATE TABLE paciente(
    id_paciente int auto_increment primary key,
    id_usuario int not null,
    tipo_sangre ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') not null,
    foreign key (id_usuario) references usuario(id_usuario)
);

CREATE TABLE discapacidad (
    id_discapacidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE alergia (
    id_alergia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE paciente_discapacidad (
    id_paciente INT NOT NULL,
    id_discapacidad INT NOT NULL,
    PRIMARY KEY (id_paciente, id_discapacidad),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente) on delete cascade,
    FOREIGN KEY (id_discapacidad) REFERENCES discapacidad(id_discapacidad)
);

CREATE TABLE paciente_alergia (
    id_paciente INT NOT NULL,
    id_alergia INT NOT NULL,
    PRIMARY KEY (id_paciente, id_alergia),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente) on delete cascade,
    FOREIGN KEY (id_alergia) REFERENCES alergia(id_alergia)
);


--creacion cargo
CREATE TABLE cargo(
    id_cargo int auto_increment primary key,
    nombre_cargo varchar(100),
    descripcion_cargo varchar(100)
);


--creacion medico
CREATE TABLE medico(
    id_medico int auto_increment primary key,
    id_usuario int not null,
    id_cargo int not null,
    horario_atencion ENUM('mañana','tarde') not null,
    foreign key (id_usuario) references usuario(id_usuario),
    foreign key (id_cargo) references cargo(id_cargo)
);

--creacion disponibilidad_horaria
CREATE TABLE disponibilidad_horaria(
    id_disponibilidad_horaria int auto_increment primary key,
    id_medico int not null,
    hora_llegada time not null,
    hora_finalizacion time not null,
    foreign key (id_medico) references medico(id_medico)
);




--creacion cita
CREATE TABLE cita(
    id_cita int auto_increment primary key,
    id_paciente int not null,
    id_disponibilidad_horaria int not null,
    fecha_cita date not null,
    tipo_cita varchar(100) not null,
    prioridad ENUM('baja','moderada', 'alta') not null,
    foreign key (id_paciente) references paciente(id_paciente),
    foreign key (id_disponibilidad_horaria) references disponibilidad_horaria(id_disponibilidad_horaria)
);


--creacion diagnostico
CREATE TABLE diagnostico(
    id_diagnostico int auto_increment primary key,
    id_cita int not null,
    --los siguiente campos no son obligatorios
    remision TEXT,
    descripcion_remision TEXT,
    formula_asignada TEXT,
    descripcion_general_cita TEXT NOT NULL,
    tipo_diagnostico TEXT NOT NULL,
    foreign key (id_cita) references cita(id_cita)
);

--creacion historial_medico
CREATE TABLE historial_medico(
    id_historial_medico int auto_increment primary key,
    id_paciente int not null,
    foreign key (id_paciente) references paciente(id_paciente)
);



--creacion detalle_historial
CREATE TABLE detalle_historial(
    id_detalle_historial int auto_increment primary key,
    id_historial_medico int not null,
    id_diagnostico int not null,
    fecha_registro date not null,
    hora_registro time not null,
    estado ENUM('iniciado','en proceso','cerrado') not null,
    foreign key (id_historial_medico) references historial_medico(id_historial_medico),
    foreign key (id_diagnostico) references diagnostico(id_diagnostico)
);
