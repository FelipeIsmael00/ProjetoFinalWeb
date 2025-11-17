# Projeto Final - Desenvolvimento Back-End com PHP e Laravel

## 📋 Descrição

Este projeto é a culminação do aprendizado na disciplina de Desenvolvimento Back-End com PHP e Laravel. A aplicação demonstra a implementação de padrões de projeto, princípios SOLID, CQRS e boas práticas de arquitetura de software.

## 🎯 Objetivos da Entrega Parcial 1

- ✅ Implementação do **Padrão Factory Method**
- ✅ Implementação do **Padrão Strategy**
- ✅ Implementação inicial do **Padrão CQRS** (Command Query Responsibility Segregation)
- ✅ Aplicação consistente dos **Princípios SOLID**
- ✅ Uso de **Injeção de Dependência**

## 🏗️ Arquitetura e Padrões Implementados

### 1. Factory Method Pattern

**Localização:** `app/Notifications/Factory/NotificationFactory.php`

**Descrição:**
O padrão Factory Method foi implementado para criar diferentes tipos de notificações (Email, SMS, Push) de forma desacoplada. A factory encapsula a lógica de criação, permitindo adicionar novos tipos de notificação sem modificar o código existente.

**Justificativa:**
- **Open/Closed Principle**: O sistema está aberto para extensão (novos tipos de notificação) e fechado para modificação
- **Single Responsibility**: A factory tem responsabilidade única de criar instâncias de notificações
- **Dependency Inversion**: O código depende da abstração `NotificationInterface`, não de implementações concretas

**Estrutura:**
```
app/Notifications/
├── Contracts/
│   └── NotificationInterface.php (Abstração)
├── EmailNotification.php (Implementação concreta)
├── SmsNotification.php (Implementação concreta)
├── PushNotification.php (Implementação concreta)
└── Factory/
    └── NotificationFactory.php (Factory Method)
```

**Exemplo de uso:**
```php
$notification = NotificationFactory::create('email');
$notification->send('user@example.com', 'Mensagem');
```

### 2. Strategy Pattern

**Localização:** `app/Payment/`

**Descrição:**
O padrão Strategy foi implementado para processar diferentes métodos de pagamento (Cartão de Crédito, PIX, Boleto). Cada método de pagamento é uma estratégia independente, permitindo trocar algoritmos em tempo de execução.

**Justificativa:**
- **Open/Closed Principle**: Novos métodos de pagamento podem ser adicionados sem modificar o código existente
- **Single Responsibility**: Cada estratégia tem responsabilidade única de processar um tipo específico de pagamento
- **Dependency Inversion**: O `PaymentProcessor` depende da abstração `PaymentStrategyInterface`
- **Flexibilidade**: Permite trocar estratégias dinamicamente em tempo de execução

**Estrutura:**
```
app/Payment/
├── Contracts/
│   └── PaymentStrategyInterface.php (Abstração)
├── Strategies/
│   ├── CreditCardPayment.php
│   ├── PixPayment.php
│   └── BoletoPayment.php
├── PaymentProcessor.php (Contexto)
└── PaymentStrategyFactory.php (Factory auxiliar)
```

**Exemplo de uso:**
```php
$strategy = PaymentStrategyFactory::create('pix');
$processor = new PaymentProcessor($strategy);
$result = $processor->process(100.00, ['pix_key' => 'chave@exemplo.com']);
```

### 3. CQRS (Command Query Responsibility Segregation)

**Localização:** `app/CQRS/`

**Descrição:**
O padrão CQRS foi implementado para separar operações de escrita (Commands) e leitura (Queries) de produtos. Isso permite otimizar cada lado independentemente e escalar conforme necessário.

**Justificativa:**
- **Separation of Concerns**: Separação clara entre operações de escrita e leitura
- **Single Responsibility**: Commands e Queries têm responsabilidades distintas
- **Escalabilidade**: Permite otimizar leitura e escrita independentemente
- **Manutenibilidade**: Facilita a manutenção e evolução do código

**Estrutura:**
```
app/CQRS/
├── Commands/
│   ├── CreateProductCommand.php (Command)
│   └── Handlers/
│       └── CreateProductCommandHandler.php (Handler)
└── Queries/
    ├── GetProductsQuery.php (Query)
    └── Handlers/
        └── GetProductsQueryHandler.php (Handler)
```

**Command (Escrita):**
```php
$command = new CreateProductCommand(
    name: 'Produto',
    description: 'Descrição',
    price: 99.90,
    stock: 10,
    category: 'Categoria'
);
$handler = new CreateProductCommandHandler();
$product = $handler->handle($command);
```

**Query (Leitura):**
```php
$query = new GetProductsQuery(
    category: 'Eletrônicos',
    minPrice: 50.00,
    maxPrice: 500.00
);
$handler = new GetProductsQueryHandler();
$products = $handler->handle($query);
```

## 🔧 Princípios SOLID Aplicados

### Single Responsibility Principle (SRP)
- Cada classe tem uma única responsabilidade:
  - `NotificationFactory`: Criar notificações
  - `PaymentProcessor`: Processar pagamentos
  - `CreateProductCommandHandler`: Criar produtos
  - `GetProductsQueryHandler`: Consultar produtos

### Open/Closed Principle (OCP)
- Sistema aberto para extensão, fechado para modificação:
  - Novos tipos de notificação podem ser adicionados sem modificar a factory
  - Novos métodos de pagamento podem ser adicionados sem modificar o processador
  - Novas queries podem ser adicionadas sem modificar comandos existentes

### Liskov Substitution Principle (LSP)
- Implementações podem ser substituídas sem quebrar o código:
  - Qualquer implementação de `NotificationInterface` pode substituir outra
  - Qualquer implementação de `PaymentStrategyInterface` pode substituir outra

### Interface Segregation Principle (ISP)
- Interfaces específicas e coesas:
  - `NotificationInterface`: Apenas métodos necessários para notificações
  - `PaymentStrategyInterface`: Apenas métodos necessários para pagamentos

### Dependency Inversion Principle (DIP)
- Dependências de abstrações, não de implementações concretas:
  - Services dependem de interfaces, não de classes concretas
  - Injeção de dependência via construtor em todos os services e controllers
  - Service Provider registra dependências no container

## 📦 Injeção de Dependência

A injeção de dependência é aplicada em:

1. **Controllers**: Recebem services via construtor
   ```php
   public function __construct(private ProductService $productService) {}
   ```

2. **Services**: Recebem handlers e outras dependências via construtor
   ```php
   public function __construct(
       private CreateProductCommandHandler $createProductHandler,
       private GetProductsQueryHandler $getProductsHandler
   ) {}
   ```

3. **Service Provider**: Registra dependências no container
   ```php
   $this->app->singleton(ProductService::class, function ($app) {
       return new ProductService(
           $app->make(CreateProductCommandHandler::class),
           $app->make(GetProductsQueryHandler::class)
       );
   });
   ```

## 🚀 Instalação e Configuração

### Pré-requisitos
- PHP >= 8.1
- Composer
- MySQL

### Passos para instalação

1. **Clone o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd "Projeto Final - web"
   ```

2. **Instale as dependências**
   ```bash
   composer install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o banco de dados no arquivo `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nome_do_banco
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

5. **Execute as migrations**
   ```bash
   php artisan migrate
   ```

6. **Inicie o servidor**
   ```bash
   php artisan serve
   ```

## 📡 Endpoints da API

### Produtos (CQRS)

**GET** `/api/products`
- Lista produtos (Query)
- Query params: `category`, `min_price`, `max_price`, `min_stock`, `limit`, `offset`

**POST** `/api/products`
- Cria um produto (Command)
- Body:
  ```json
  {
    "name": "Produto",
    "description": "Descrição",
    "price": 99.90,
    "stock": 10,
    "category": "Categoria"
  }
  ```

### Pagamentos (Strategy)

**POST** `/api/payments/process`
- Processa um pagamento
- Body:
  ```json
  {
    "amount": 100.00,
    "payment_method": "pix",
    "payment_data": {
      "pix_key": "chave@exemplo.com"
    }
  }
  ```
- Métodos suportados: `credit_card`, `pix`, `boleto`

### Notificações (Factory Method)

**POST** `/api/notifications/send`
- Envia uma notificação
- Body:
  ```json
  {
    "type": "email",
    "recipient": "user@example.com",
    "message": "Mensagem"
  }
  ```
- Tipos suportados: `email`, `sms`, `push`

## 📁 Estrutura de Diretórios

```
app/
├── CQRS/
│   ├── Commands/
│   │   ├── CreateProductCommand.php
│   │   └── Handlers/
│   │       └── CreateProductCommandHandler.php
│   └── Queries/
│       ├── GetProductsQuery.php
│       └── Handlers/
│           └── GetProductsQueryHandler.php
├── Http/
│   └── Controllers/
│       ├── ProductController.php
│       ├── PaymentController.php
│       └── NotificationController.php
├── Models/
│   ├── Product.php
│   ├── Order.php
│   └── User.php
├── Notifications/
│   ├── Contracts/
│   │   └── NotificationInterface.php
│   ├── EmailNotification.php
│   ├── SmsNotification.php
│   ├── PushNotification.php
│   └── Factory/
│       └── NotificationFactory.php
├── Payment/
│   ├── Contracts/
│   │   └── PaymentStrategyInterface.php
│   ├── Strategies/
│   │   ├── CreditCardPayment.php
│   │   ├── PixPayment.php
│   │   └── BoletoPayment.php
│   ├── PaymentProcessor.php
│   └── PaymentStrategyFactory.php
├── Providers/
│   └── AppServiceProvider.php
└── Services/
    ├── ProductService.php
    └── NotificationService.php
```

## 🧪 Testes

Para executar os testes (quando implementados):
```bash
php artisan test
```

## 📝 Notas de Implementação

### Decisões Técnicas

1. **CQRS Inicial**: Implementado apenas para o domínio de Produtos como demonstração inicial. O padrão pode ser expandido para outros domínios conforme necessário.

2. **Factory Method**: Utilizado para criação de notificações, mas pode ser estendido para outros contextos onde a criação de objetos precisa ser encapsulada.

3. **Strategy Pattern**: Implementado para métodos de pagamento, demonstrando como diferentes algoritmos podem ser intercambiados dinamicamente.

4. **Service Layer**: Criada uma camada de serviços para orquestrar operações complexas e manter os controllers enxutos.

## 🔒 Segurança

- Validação de dados em todos os endpoints
- Uso de transações em operações de escrita
- Preparação para autenticação (Sanctum configurado)

## 👥 Autores

Felipe Ismael, Priscila Camargo

## 📄 Licença

Este projeto é desenvolvido para fins educacionais.

