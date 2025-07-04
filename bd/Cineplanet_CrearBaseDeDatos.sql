-- CREACIÓN DE BASE DE DATOS CINEPLANET PERÚ
CREATE DATABASE IF NOT EXISTS CineDB;
USE CineDB;

-- TABLA SEDE (Nueva - Para múltiples ubicaciones en Perú)
CREATE TABLE Sede (
    ID_sede INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200),
    ciudad VARCHAR(50),
    departamento VARCHAR(50),
    telefono VARCHAR(15),
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

-- TABLA EMPLEADO (Mejorada)
CREATE TABLE Empleado (
    ID_empleado INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    cargo VARCHAR(50),
    turno ENUM('Mañana', 'Tarde', 'Noche'),
    ID_sede INT,
    fecha_contrato DATE,
    salario DECIMAL(8,2),
    FOREIGN KEY (ID_sede) REFERENCES Sede(ID_sede)
);

-- TABLA SALA (Mejorada - Asociada a sede)
CREATE TABLE Sala (
    ID_sala INT PRIMARY KEY,
    numero_sala INT NOT NULL,
    tipo_sala ENUM('2D', '3D', 'IMAX', 'VIP', '4DX') DEFAULT '2D',
    capacidad INT NOT NULL,
    estado ENUM('Disponible', 'Mantenimiento', 'Fuera_Servicio') DEFAULT 'Disponible',
    ID_sede INT NOT NULL,
    ID_empleado INT,
    FOREIGN KEY (ID_sede) REFERENCES Sede(ID_sede),
    FOREIGN KEY (ID_empleado) REFERENCES Empleado(ID_empleado)
);

-- TABLA ASIENTO (Mejorada)
CREATE TABLE Asiento (
    ID_asiento INT AUTO_INCREMENT PRIMARY KEY,
    fila CHAR(1) NOT NULL,
    numero INT NOT NULL,
    tipo_asiento ENUM('Normal', 'VIP', 'Discapacitado') DEFAULT 'Normal',
    estado ENUM('Disponible', 'Ocupado', 'Mantenimiento') DEFAULT 'Disponible',
    ID_sala INT NOT NULL,
    FOREIGN KEY (ID_sala) REFERENCES Sala(ID_sala),
    UNIQUE KEY unique_asiento_sala (fila, numero, ID_sala)
);

-- TABLA CLIENTE (Mejorada)
CREATE TABLE Cliente (
    DNI CHAR(8) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    telefono VARCHAR(15),
    fecha_nac DATE,
    genero ENUM('M', 'F', 'Otro'),
    nacionalidad VARCHAR(50) DEFAULT 'Peruana',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLA SOCIO (1 a 1 opcional con Cliente - Mejorada)
CREATE TABLE Socio (
    DNI CHAR(8) PRIMARY KEY,
    numero_socio VARCHAR(20) UNIQUE NOT NULL,
    tipo_socio ENUM('Classic', 'Premium', 'Black') DEFAULT 'Classic',
    puntos_acumulados INT DEFAULT 0,
    fecha_vencimiento DATE,
    estado ENUM('Activo', 'Vencido', 'Suspendido') DEFAULT 'Activo',
    FOREIGN KEY (DNI) REFERENCES Cliente(DNI)
);

-- TABLA PELICULA (Simplificada - Género incluido)
CREATE TABLE Pelicula (
    ID_pelicula INT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    director VARCHAR(100),
    genero VARCHAR(50) NOT NULL,
    duracion_minutos INT,
    clasificacion ENUM('ATP', '+13', '+16', '+18') DEFAULT 'ATP',
    sinopsis TEXT,
    fecha_estreno DATE,
    estado ENUM('En_Cartelera', 'Proximamente', 'Retirada') DEFAULT 'En_Cartelera',
);

-- TABLA FUNCION (Mejorada)
CREATE TABLE Funcion (
    ID_funcion INT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    precio_base DECIMAL(6,2) NOT NULL,
    subtitulos BOOLEAN DEFAULT FALSE,
    doblaje BOOLEAN DEFAULT TRUE,
    estado ENUM('Programada', 'En_Curso', 'Finalizada', 'Cancelada') DEFAULT 'Programada',
    ID_sala INT NOT NULL,
    ID_pelicula INT NOT NULL,
    FOREIGN KEY (ID_sala) REFERENCES Sala(ID_sala),
    FOREIGN KEY (ID_pelicula) REFERENCES Pelicula(ID_pelicula)
);

-- TABLA PROMOCION (Mejorada)
CREATE TABLE Promocion (
    ID_promocion INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo_descuento ENUM('Porcentaje', 'Monto_Fijo') DEFAULT 'Porcentaje',
    valor_descuento DECIMAL(5,2),
    fecha_inicio DATE,
    fecha_fin DATE,
    estado ENUM('Activa', 'Inactiva', 'Vencida') DEFAULT 'Activa',
    aplica_socios BOOLEAN DEFAULT TRUE,
    tipo_socio_aplicable ENUM('Classic', 'Premium', 'Black', 'Todos') DEFAULT 'Todos'
);

-- TABLA COMPRA (Reemplaza Pedido - Más específico para cine)
CREATE TABLE Compra (
    ID_compra INT PRIMARY KEY AUTO_INCREMENT,
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(8,2) NOT NULL,
    descuento DECIMAL(8,2) DEFAULT 0.00,
    total DECIMAL(8,2) NOT NULL,
    metodo_pago ENUM('Efectivo', 'Tarjeta', 'Yape', 'Plin') DEFAULT 'Tarjeta',
    estado ENUM('Pendiente', 'Pagada', 'Cancelada', 'Reembolsada') DEFAULT 'Pendiente',
    DNI CHAR(8) NOT NULL,
    ID_promocion INT NULL,
    FOREIGN KEY (DNI) REFERENCES Cliente(DNI),
    FOREIGN KEY (ID_promocion) REFERENCES Promocion(ID_promocion)
);

-- TABLA BOLETO (Mejorada - Incluye información de asiento)
CREATE TABLE Boleto (
    ID_boleto INT PRIMARY KEY AUTO_INCREMENT,
    numero_serie VARCHAR(50) UNIQUE NOT NULL,
    precio DECIMAL(6,2) NOT NULL,
    fecha_emision TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    codigo_qr VARCHAR(200),
    estado ENUM('Activo', 'Usado', 'Cancelado') DEFAULT 'Activo',
    ID_compra INT NOT NULL,
    ID_funcion INT NOT NULL,
    ID_asiento INT NOT NULL,
    FOREIGN KEY (ID_compra) REFERENCES Compra(ID_compra),
    FOREIGN KEY (ID_funcion) REFERENCES Funcion(ID_funcion),
    FOREIGN KEY (ID_asiento) REFERENCES Asiento(ID_asiento)
);

-- TABLA DULCERIA (Mejorada - Productos de concesión)
CREATE TABLE Dulceria (
    ID_producto INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria ENUM('Dulces', 'Bebidas', 'Salado', 'Combos') DEFAULT 'Dulces',
    precio_unitario DECIMAL(6,2) NOT NULL,
    stock INT DEFAULT 0,
    descripcion TEXT,
    estado ENUM('Disponible', 'Agotado', 'Descontinuado') DEFAULT 'Disponible',
    ID_sede INT NOT NULL,
    FOREIGN KEY (ID_sede) REFERENCES Sede(ID_sede)
);

-- TABLA DETALLE_COMPRA_DULCERIA (Normalizada - Productos comprados)
CREATE TABLE Detalle_Compra_Dulceria (
    ID_detalle INT PRIMARY KEY AUTO_INCREMENT,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(6,2) NOT NULL,
    subtotal DECIMAL(8,2) NOT NULL,
    ID_compra INT NOT NULL,
    ID_producto INT NOT NULL,
    FOREIGN KEY (ID_compra) REFERENCES Compra(ID_compra),
    FOREIGN KEY (ID_producto) REFERENCES Dulceria(ID_producto)
);