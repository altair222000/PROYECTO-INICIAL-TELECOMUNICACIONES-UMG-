CREATE DATABASE esp32;

USE esp32;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO usuarios (usuario, password) VALUES 
('admin', SHA2('1234', 256));  -- cambia la clave

CREATE TABLE temperaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor FLOAT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
