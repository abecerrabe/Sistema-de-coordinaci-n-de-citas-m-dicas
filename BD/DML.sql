--usuario 6
/* dos de cada uno */
/* contrasena:
tiene que tener la primera letra del correo en mayus
al finalizar un *
solo 8 caracter
correo:
un numero debe tener
*/
use hospital;
--crear usuario
INSERT INTO usuario (
    nombre_completo,
    correo_electronico,
    telefono,
    password_usuario,
    numero_cedula,
    tipo_permiso,
    estado
) VALUES 
('Juan Pablo Cuellar Vanegas','Jpcuellarva1@gmail.com', '312111331','$2y$10$9lfRoou0v4SC9j5LNvZ9a.HBNZVG23g5DkF4myXjjLIGS16kO9Xu2', 1211223456, 'paciente','activo'),
('Kevin Steven Parra Norena','ksparran1@gmail.com','312222333','$2y$10$VWjU.XvRMh7FQ5NdCYvku.m6skpoCm4kmpOsHxhmuAzLsOHjumwk6', 1294919423,'paciente','activo'),
('Maria Alejandra Nunez Chaucanes','manunezc1@gmail.com','312333444','$2y$10$dnb.QuSZEjtiGRSMKoBWC.DpvmnZC1tNShziW/fc.EauIA9MTuSJO*', 1112445690, 'medico','activo'),
('Carlos Caicedo Perez', 'carlitoscaicedo4@gmail.com','312444555','$2y$10$YvIAmVB5gAQOiIT31n3TVuEnYRwhz/cMzSQ801voiC7LXpzTuCwEO', 1233399255, 'medico','activo'),
('Alejandra Becerra Bellaiza','abecerrabe1@gmail.com','312345678','$2y$10$tE/z8DikKTi0.4DIahGefenfbwMnZaj9Waz6Tm9sY5JZoKa5Ovgh.', 1112940289, 'administrador','activo'),
('Javier Arias','jarias3@gmail.com', '319383929', '$2y$10$BUDV1.JXABJhFx.milFtD.Byz25.O0K8LASnIdxtGI1a6lyq0VKL.', 1010239312,'administrador','activo');


--crear pacientes
INSERT INTO paciente(id_usuario, tipo_sangre, alergia, discapacidad) VALUES 
(1,'O+','','visual'),(2,'O-','dipierona','');

--crear cargos
INSERT INTO cargo (nombre_cargo, descripcion_cargo, estados) VALUES
('Coordinador Médico', 'Responsable de supervisar el equipo médico', 'activo'),
('medico general', 'Encargado de atención al paciente para sintomas comunes y sin especialidad', 'activo');

--crear medicos
INSERT INTO medico (id_usuario, id_cargo, horario_atencion) VALUES
(3, 1, 'dia'),
(4, 2, 'tarde');

--crear disponibilidad_horaria
INSERT INTO disponibilidad_horaria (id_medico, hora_llegada, hora_finalizacion) VALUES
(1, '08:00:00', '12:00:00'),
(2, '14:00:00', '18:00:00');

--crear citas
INSERT INTO cita (id_paciente,id_disponibilidad_horaria,fecha_cita,prioridad,estado) VALUES
(1, 1, '2025-10-03', 'moderada', 'pendiente'),
(2, 2, '2025-10-04', 'alta', 'pendiente');

--crear diagnosticos
INSERT INTO diagnostico (
    id_cita,
    remision,
    descripcion_remision,
    formula_asignada,
    descripcion_general_cita,
    tipo_diagnostico
) VALUES
(1, 'Remisión a nutricionista', 'Paciente con sobrepeso y antecedentes familiares de diabetes tipo 2',
'Metformina 500mg cada 8 horas por 15 días', 'Control de presión cardiaca con signos de resistencia a tratamiento previo', 'nutricion'),

(2, 'Remisión a cardiología', 'Paciente con antecedentes de hipertensión arterial, 
requiere evaluación especializada', 'Losartán 50mg cada 12 horas por 30 días', 
'Presión elevada durante consulta, sin signos de complicación aguda', 'cardiología');

/* tener cuidado con el tipo de diagnostico */


--crear diagnosticos
INSERT INTO historial_medico (
    id_paciente,
    id_diagnostico,
    fecha_registro,
    hora_registro,
    estado
) VALUES
(1, 1, '2025-10-03', '09:15:00', 'iniciado'),
(2, 2, '2025-10-04', '15:30:00', 'en proceso');

