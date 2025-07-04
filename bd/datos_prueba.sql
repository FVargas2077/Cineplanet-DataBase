-- Insertar sedes
INSERT INTO Sede (ID_sede, nombre, direccion, ciudad, departamento, telefono) VALUES
(1, 'Cineplanet Tacna', 'Av. Circunvalación 123', 'Tacna', 'Tacna', '052123456'),
(2, 'Cineplanet Lima', 'Av. Javier Prado 2000', 'Lima', 'Lima', '014567890');

-- Insertar empleados
INSERT INTO Empleado (ID_empleado, nombre, apellidos, cargo, turno, ID_sede, fecha_contrato, salario) VALUES
(1, 'Juan', 'Pérez López', 'Gerente', 'Tarde', 1, '2020-01-15', 3500.00),
(2, 'María', 'Gómez Sánchez', 'Taquillera', 'Noche', 1, '2021-05-20', 1200.00);

-- Insertar salas
INSERT INTO Sala (ID_sala, numero_sala, tipo_sala, capacidad, ID_sede, ID_empleado) VALUES
(1, 1, '3D', 120, 1, 1),
(2, 2, '2D', 100, 1, 1),
(3, 1, 'IMAX', 150, 2, NULL);

-- Insertar asientos para sala 1 (3D)
INSERT INTO Asiento (fila, numero, tipo_asiento, ID_sala) VALUES
('A', 1, 'Normal', 1),
('A', 2, 'Normal', 1),
('A', 3, 'Normal', 1),
('B', 1, 'VIP', 1),
('B', 2, 'VIP', 1),
('C', 1, 'Discapacitado', 1);

-- Insertar clientes
INSERT INTO Cliente (DNI, nombre, apellidos, email, telefono, fecha_nac, genero) VALUES
('12345678', 'Carlos', 'Martínez Rojas', 'carlos@example.com', '987654321', '1990-05-15', 'M'),
('87654321', 'Ana', 'Díaz Fuentes', 'ana@example.com', '987123456', '1995-08-22', 'F');

-- Insertar socio
INSERT INTO Socio (DNI, numero_socio, tipo_socio, puntos_acumulados, fecha_vencimiento) VALUES
('12345678', 'SOC001', 'Premium', 1500, '2025-12-31');

-- Insertar películas
INSERT INTO Pelicula (ID_pelicula, titulo, director, genero, duracion_minutos, clasificacion, sinopsis, fecha_estreno) VALUES
(1, 'Cómo entrenar tu dragón', 'Dean DeBlois', 'Animación', 98, 'ATP', 'Un joven vikingo se hace amigo de un dragón', '2023-03-15'),
(2, 'Duna', 'Denis Villeneuve', 'Ciencia Ficción', 155, '+13', 'Adaptación de la famosa novela de ciencia ficción', '2023-04-20');

-- Insertar funciones
INSERT INTO Funcion (ID_funcion, fecha, hora, precio_base, subtitulos, doblaje, ID_sala, ID_pelicula) VALUES
(1, '2023-05-20', '20:40:00', 25.00, FALSE, TRUE, 1, 1),
(2, '2023-05-20', '22:00:00', 30.00, TRUE, FALSE, 1, 2);

-- Insertar promociones
INSERT INTO Promocion (ID_promocion, nombre, descripcion, tipo_descuento, valor_descuento, fecha_inicio, fecha_fin, aplica_socios, tipo_socio_aplicable) VALUES
(1, 'Descuento Socio', '10% de descuento para socios', 'Porcentaje', 10.00, '2023-01-01', '2023-12-31', TRUE, 'Todos');

-- Insertar productos de dulcería
INSERT INTO Dulceria (ID_producto, nombre, categoria, precio_unitario, stock, ID_sede) VALUES
(1, 'Combo Familiar', 'Combos', 35.00, 50, 1),
(2, 'Gaseosa Grande', 'Bebidas', 8.00, 100, 1);