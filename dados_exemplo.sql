USE bd_maison_royale;

INSERT INTO usuarios (nome, email, cpf, senha, perfil) VALUES
('Administrador', 'admin@maisonroyale.com', '00000000000', '$2y$10$A3Ge1xuNvVGr2wbB9G816uxrx.3Sgp5QSzIzGEJatSx/vzY4GXEBa', 'admin'),
('Cliente Exemplo', 'cliente@email.com', '11111111111', '$2y$10$5LgX2SYwVII2ZIomZhY8..0vwR/kB7Rj7POtJ6EnAuOFSK5B9AQpq', 'cliente');

INSERT INTO quartos (nome, categoria, descricao, capacidade, preco, imagem) VALUES
('Suíte Presidencial', 'Suíte', 'Ambiente amplo com sala de estar, banheira e vista panorâmica.', 4, 850.00, 'assets/img/quartos/foto-presidencial.png'),
('Quarto Deluxe', 'Deluxe', 'Quarto confortável com cama king e café da manhã incluso.', 2, 420.00, 'assets/img/quartos/foto-deluxe.png'),
('Quarto Standard', 'Standard', 'Acomodação prática para hospedes em viagem de negócios.', 2, 280.00, 'assets/img/quartos/foto-standard.png');
