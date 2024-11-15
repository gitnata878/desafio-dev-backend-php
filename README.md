# Desafio Desenvolvedor PHP

## Índice

1. [Introdução](#introdução)

2. [Instalação e Configuração](#instalação-e-configuração)

3. [Testes](#testes)

4. [Documentação da API](#documentação-da-api)

5. [Estrutura de Pastas e Trabalhos Realizados](#estrutura-de-pastas-e-trabalhos-realizados)

6. [Autor](#autor)

---
## Introdução

Descrição :

> Este projeto é uma API Laravel que gerencia formulários dinâmicos e seus preenchimentos. O sistema armazena os dados dos formulários em arquivos JSON e permite que os usuários preencham e consultem esses formulários via uma interface RESTful.

## Tecnologias

Este projeto usa as seguintes tecnologias:

- **PHP 8.1**: Backend principal utilizando o Laravel.
- **Laravel**: Framework PHP utilizado para o backend.
- **Docker**: Para ambiente de desenvolvimento isolado.
- **PHPUnit**: Framework de testes.
- **Swagger**: Para documentação da API.

## Pré-requisitos

Antes de rodar o projeto, você precisa ter as seguintes ferramentas instaladas:

- **Docker** (e Docker Compose)
- **Composer** (caso queira instalar dependências manualmente)
- **PHP 8.1** (caso não queira usar Docker)

## Instalação e Configuração

Para instalar e configurar o projeto localmente com Docker, siga os passos abaixo:

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/gitnata878/desafio-dev-backend-php.git

   cd desafio-dev-backend-php

2. **Rodando o Projeto.**

   ```bash
   docker compose up -d --build

## Executando os Testes
Para rodar os testes unitários com PHPUnit, use o comando:
    
    docker compose run test --verbose

## Cenarios de teste
### Testes de Preenchimento de Formulário

Este conjunto de testes tem como objetivo validar diferentes cenários ao preencher um formulário. Ele cobre casos de sucesso e erro para garantir que o sistema de preenchimento de formulário esteja funcionando corretamente.

### Testes

#### 1. Teste de Preenchimento de Formulário com Sucesso
**Objetivo**: Verificar se o preenchimento de um formulário com os campos corretamente preenchidos retorna uma resposta de sucesso (código 201).

**Passos**:
- Enviar uma solicitação POST com os dados válidos para o endpoint de preenchimento de formulário.
- Verificar se o status da resposta é 201 (Criado).
- Verificar se a estrutura da resposta JSON contém os campos `message` e `data`.

#### 2. Teste de Formulário Inexistente
**Objetivo**: Verificar o comportamento quando um formulário inexistente é preenchido.

**Passos**:
- Enviar uma solicitação POST com dados para um formulário que não existe (indicando um endpoint inexistente).
- Verificar se o status da resposta é 404 (Não encontrado).

#### 3. Teste de Campo Obrigatório Não Preenchido
**Objetivo**: Verificar se um erro de validação ocorre quando um campo obrigatório não é preenchido.

**Passos**:
- Enviar uma solicitação POST sem preencher o campo obrigatório `field-2-1`.
- Verificar se o status da resposta é 422 (Erro de validação).
- Verificar se o erro de validação está relacionado ao campo `field-2-1`.

#### 4. Teste de Campo Select com Valor Inválido
**Objetivo**: Verificar se um erro de validação ocorre quando um valor inválido é fornecido para um campo do tipo `select` (campo de escolha).

**Passos**:
- Enviar uma solicitação POST com um valor inválido (`'Carros'` para um campo que deve ter valores como `'Eletrônicos'`, por exemplo).
- Verificar se o status da resposta é 422 (Erro de validação).
- Verificar se o erro de validação está relacionado ao campo `field-2-3`.

#### 5. Teste de Campo Text Deve Ser Uma String (Erro de Validação)
**Objetivo**: Verificar se ocorre um erro de validação quando um valor inválido (não-string) é fornecido para um campo de texto.

**Passos**:
- Enviar uma solicitação POST com o campo `field-2-1` contendo um valor numérico (`12345`) em vez de uma string.
- Verificar se o status da resposta é 422 (Erro de validação).
- Verificar se o erro de validação está relacionado ao campo `field-2-1`.

#### 6. Teste de Campo Number Deve Ser Um Número (Erro de Validação)
**Objetivo**: Verificar se ocorre um erro de validação quando um valor inválido (não-numérico) é fornecido para um campo que espera um número.

**Passos**:
- Enviar uma solicitação POST com o campo `field-2-2` contendo um valor inválido (a string `'dois mil'` em vez de um número).
- Verificar se o status da resposta é 422 (Erro de validação).
- Verificar se o erro de validação está relacionado ao campo `field-2-2`.

    

## Documentação da API
A documentação da API pode ser acessada diretamente através do Swagger. apos rodar o projeto pode acessá-la na seguinte URL:

    http://localhost:8080

## Estrutura de Pastas e Trabalhos Realizados

### 1. Pasta `app/Http/Controllers`
Nesta pasta, trabalhei na criação e implementação do `FormularioController`, que gerencia as operações relacionadas aos formulários dinâmicos, como preenchimento e consulta dos formulários.

- **Criação do `FormularioController`:**
  - Endpoint para preencher formulários.
  - Endpoint para consultar os preenchimentos de um formulário.

### 2. Pasta `app/Services`
Criei uma nova pasta `Services` para organizar melhor o código. Dentro dela, criei a classe `FormularioService`, responsável pela validação dos campos dinâmicos de cada formulário. Essa classe realiza as seguintes tarefas:

- **Validação dos campos dinâmicos:**
  - Verificação de campos obrigatórios.
  - Validação de tipos de dados de acordo com as regras definidas para cada campo (ex.: texto, número, select).

A criação da pasta `Services` visa melhorar a organização do código e separar responsabilidades, tornando o código mais modular e fácil de testar.

#### Injeção de Dependência
A classe `FormularioService` foi injetada no `FormularioController` usando a funcionalidade de **injeção de dependência** do Laravel. Isso facilita a modularização e o teste da aplicação, permitindo que o serviço seja facilmente substituído por uma versão mockada durante os testes.

- **Injeção no Controller:**
  O Laravel automaticamente injeta o `FormularioService` no `FormularioController` quando a rota é chamada, garantindo que a lógica de preenchimento e consulta de formulários seja desacoplada da implementação de validação.

  ```php
  public function __construct(FormularioService $formularioService)
  {
      $this->formularioService = $formularioService;
  }

### 3. Pasta `routes`
Na pasta `routes`, especificamente no arquivo `api.php`, foram incluídas duas novas rotas para gerenciar as operações de formulários dinâmicos:

- **Rota `POST /api/formularios/{id_formulario}/preenchimentos`:**
  - Utilizada para preencher um formulário com os dados enviados via `POST`.

- **Rota `GET /api/formularios/{id_formulario}/preenchimentos`:**
  - Utilizada para obter os preenchimentos de um formulário.

Essas rotas são responsáveis por fazer a comunicação entre o frontend e o backend para armazenar e recuperar os dados dos formulários.

### 4. Pasta `storage`
Na pasta `storage`, subpastas foram criadas para gerenciar os arquivos necessários ao funcionamento da aplicação:

- **Arquivo `forms_definition.json`:**
  - Dentro de `storage/`, coloquei o arquivo `forms_definition.json`, que contém a definição da estrutura dos formulários. Esse arquivo é utilizado para definir os campos e a estrutura dos formulários dinâmicos, garantindo que os formulários sejam gerados corretamente conforme a definição.

- **Subpasta `storage/preenchimentos`:**
  - A subpasta `preenchimentos` foi criada dentro de `storage` para armazenar os arquivos gerados quando um formulário é preenchido. Cada formulário preenchido é salvo como um arquivo dentro dessa pasta, facilitando o acesso e organização dos dados preenchidos. Esses arquivos são salvos com os dados submetidos pelo usuário.

### 5. Pasta `tests/Feature`
Dentro da pasta `tests/Feature`, foi criada a classe `FormularioTest` para implementar os testes unitários, garantindo que todos os cenários de validação e fluxo de dados fossem corretamente tratados

Esses testes visam garantir a qualidade do código e a conformidade com os requisitos do sistema.


## Autor
    Natã dos Santos Bandeira
    emails: natanbandeira18@gmial.com
            gitnata878@gmail.com

