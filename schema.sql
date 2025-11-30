CREATE TABLE IF NOT EXISTS enlaces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    plataforma VARCHAR(50) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO enlaces (titulo, url, plataforma, descripcion) VALUES 
('Presentación Q1', 'https://drive.google.com', 'Google Drive', 'Balance del primer trimestre'),
('Video Marketing', 'https://youtube.com', 'YouTube', 'Campaña de verano');