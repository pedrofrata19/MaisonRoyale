<div align="center">

# MAISON <span style="color:#D4AF37">ROYALE</span>

### Sistema de Reservas de Quartos de Hotel

**Organização • Conforto • Excelência**

</div>

---

## Sobre o Projeto

O **Maison Royale** é um sistema web desenvolvido com o objetivo de facilitar e otimizar o processo de reservas de quartos de hotel.

Atualmente, muitos estabelecimentos enfrentam dificuldades na organização de reservas, controle de disponibilidade dos quartos e gerenciamento das informações dos hóspedes.

Processos realizados manualmente ou por diferentes canais podem ocasionar erros de agendamento, reservas duplicadas e demora no atendimento.

O Maison Royale busca solucionar esses problemas por meio de uma plataforma centralizada, prática e organizada.

---

## Objetivos

- Centralizar o gerenciamento de reservas.
- Facilitar a consulta de quartos disponíveis.
- Permitir reservas de maneira prática e organizada.
- Melhorar o controle das informações dos hóspedes.
- Otimizar a administração das operações do hotel.

---

## Funcionalidades

### Clientes
- Consulta de acomodações disponíveis.
- Visualização de informações dos quartos.
- Realização de reservas.

### Administradores e Funcionários
- Gerenciamento de quartos.
- Acompanhamento das reservas.
- Organização das informações dos hóspedes.
- Controle da disponibilidade das acomodações.

---

## Tecnologias Utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript

---

## Arquitetura

O projeto utiliza o padrão **MVC (Model-View-Controller)**, visando organizar o código, separar responsabilidades e facilitar a manutenção e evolução do sistema.

```
index.php            → ponto de entrada único (roteador ?acao=)
config/database.php  → conexão PDO
app/models/          → Usuario, Quarto, Reserva (SQL com prepared statements)
app/controllers/     → Auth, Quarto, Reserva, Usuario (regras de negócio)
app/views/           → telas de autenticação, quartos, reservas e administração
assets/              → CSS responsivo e JavaScript
banco.sql            → criação do banco (3 tabelas)
dados_exemplo.sql    → dados para teste
```

---

## Como executar

1. Copie a pasta para `C:\xampp\htdocs\Maison_Royale`.
2. Inicie Apache e MySQL no XAMPP.
3. No phpMyAdmin (http://localhost/phpmyadmin), aba SQL, execute `banco.sql`.
4. Opcional: execute `dados_exemplo.sql` para popular quartos e usuários de teste.
5. Acesse http://localhost/Maison_Royale/

> Ao cadastrar ou editar um quarto, a imagem é enviada por upload e salva em `assets/img/quartos/` (a pasta já existe e precisa ter permissão de escrita).

**Contas de exemplo:**

| Perfil | E-mail | Senha |
|---|---|---|
| Administrador | admin@maisonroyale.com | admin123 |
| Cliente | cliente@email.com | cliente123 |

Novos cadastros pela tela criam usuários com perfil `cliente`.

---

## Equipe de Desenvolvimento

| Integrante | |
|---|---|
| Pedro Frata Bélgamo Santos | Desenvolvedor |
| Rayck da Silva Baracho | Desenvolvedor |
| Talita Queiroz Duran | Desenvolvedora |
| Anny Gabrielly Lima | Desenvolvedora |

---

<div align="center">

**MAISON ROYALE**

*Sistema de Reservas de Quartos de Hotel*

</div>
