CREATE pizzaria_laravel;
USE pizzaria_laravel;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  senha VARCHAR(255) NOT NULL
);

CREATE TABLE pizzas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  ingredientes TEXT NOT NULL,
  preco DECIMAL(8,2) NOT NULL,
  tamanho ENUM('Pequena', 'Media', 'Grande') NOT NULL,
  categoria VARCHAR(50) NOT NULL,
  estoque_minimo INT DEFAULT 5,
  ativo BOOLEAN DEFAULT TRUE
);

CREATE TABLE movimentacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pizza_id INT NOT NULL,
  usuario_id INT NOT NULL,
  data_hora DATETIME NOT NULL,
  tipo ENUM('entrada','saida') NOT NULL,
  quantidade INT NOT NULL,
  observacoes TEXT,
  FOREIGN KEY (pizza_id) REFERENCES pizzas(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

INSERT INTO usuarios (nome, senha) VALUES
('Admin', 'admin123'),
('Gerente', 'gerente123'),
('Atendente', 'atendente123');

INSERT INTO pizzas (nome, ingredientes, preco, tamanho, categoria) VALUES
('Margherita', 'Molho de tomate, mussarela, manjericão', 25.90, 'Media', 'Tradicional'),
('Calabresa', 'Molho de tomate, mussarela, calabresa, cebola', 28.90, 'Media', 'Tradicional'),
('Portuguesa', 'Molho de tomate, mussarela, presunto, ovos, ervilha, cebola', 32.90, 'Media', 'Especial'),
('Frango com Catupiry', 'Molho de tomate, mussarela, frango desfiado, catupiry', 35.90, 'Media', 'Especial'),
('Quatro Queijos', 'Molho de tomate, mussarela, gorgonzola, parmesão, provolone', 38.90, 'Media', 'Especial');

INSERT INTO movimentacoes (pizza_id, usuario_id, data_hora, tipo, quantidade, observacoes) VALUES
(1, 1, '2025-01-15 10:30:00', 'entrada', 5, 'Estoque inicial'),
(2, 1, '2025-01-15 10:35:00', 'entrada', 3, 'Estoque inicial'),
(3, 2, '2025-01-15 11:00:00', 'saida', 2, 'Venda para cliente'),
(4, 2, '2025-01-15 11:15:00', 'entrada', 4, 'Reposição de estoque');
