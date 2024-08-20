# Projeto Laravel 11

Este é um projeto Laravel 11 que utiliza PHPUnit para testes, Tenancy for Laravel para multi-tenancy, Actions Pattern para organização de código, Sanctum para autenticação de API e MailHog para captura de e-mails durante o desenvolvimento.

## Requisitos

- PHP >= 8.1
- Composer
- MySQL ou MariaDB
- Node.js e npm (para front-end)
- MailHog (para captura de e-mails)

## Code Coverage Report

**Date:** 2024-08-20 15:16:49

### Summary

| Metric   | Coverage       | Covered / Total |
|----------|----------------|-----------------|
| **Classes** | ![69.05%](https://img.shields.io/badge/69.05%25-yellow) | 29 / 42 |
| **Methods** | ![79.21%](https://img.shields.io/badge/79.21%25-yellowgreen) | 80 / 101 |
| **Lines**   | ![87.34%](https://img.shields.io/badge/87.34%25-green) | 345 / 395 |

### Detailed Coverage

- **Classes:** 69.05% (29/42)
- **Methods:** 79.21% (80/101)
- **Lines:** 87.34% (345/395)

To check the detailed coverage report, run:

```sh
composer coverage:status

## Instalação

### Passo 1: Clonar o repositório

```sh
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### Passo 2: Instalar dependências

```sh
composer install
npm install
```

### Passo 3: Configurar o ambiente

Copie o arquivo [`.env.example`](command:_github.copilot.openRelativePath?%5B%7B%22scheme%22%3A%22file%22%2C%22authority%22%3A%22%22%2C%22path%22%3A%22%2Fhome%2Fjones%2FDeskBee%2Fsys%2Fdeskbee-server%2F.env.example%22%2C%22query%22%3A%22%22%2C%22fragment%22%3A%22%22%7D%5D "/home/jones/DeskBee/sys/deskbee-server/.env.example") para [`.env`](command:_github.copilot.openRelativePath?%5B%7B%22scheme%22%3A%22file%22%2C%22authority%22%3A%22%22%2C%22path%22%3A%22%2Fhome%2Fjones%2FDeskBee%2Fsys%2Fdeskbee-server%2F.env%22%2C%22query%22%3A%22%22%2C%22fragment%22%3A%22%22%7D%5D "/home/jones/DeskBee/sys/deskbee-server/.env") e configure as variáveis de ambiente conforme necessário.

```sh
cp .env.example .env
```

### Passo 4: Gerar a chave da aplicação

```sh
php artisan key:generate
```

### Passo 5: Configurar o banco de dados

Configure as variáveis de ambiente do banco de dados no arquivo [`.env`](command:_github.copilot.openRelativePath?%5B%7B%22scheme%22%3A%22file%22%2C%22authority%22%3A%22%22%2C%22path%22%3A%22%2Fhome%2Fjones%2FDeskBee%2Fsys%2Fdeskbee-server%2F.env%22%2C%22query%22%3A%22%22%2C%22fragment%22%3A%22%22%7D%5D "/home/jones/DeskBee/sys/deskbee-server/.env").

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### Passo 6: Executar migrações e seeders

```sh
php artisan migrate
php artisan db:seed
```

### Passo 7: Configurar o Tenancy for Laravel

Execute as migrações e seeders para o Tenancy.

```sh
php artisan tenants:migrate
php artisan tenants:seed
```

### Passo 8: Iniciar o servidor de desenvolvimento

```sh
php artisan serve
```

### Passo 9: Iniciar o MailHog

```sh
mailhog
```

Acesse o MailHog em [http://localhost:8025](http://localhost:8025).

## Testes

### Executar testes unitários e de feature

```sh
php artisan test
```

### Executar testes em paralelo

```sh
php artisan test --parallel
```

### Executar testes com saída compacta

```sh
php artisan test --compact
```

## Autenticação com Sanctum

Sanctum é usado para autenticação de API. Para configurar, siga a [documentação oficial do Sanctum](https://laravel.com/docs/11.x/sanctum).

## Padrão de Ações (Actions Pattern)

O padrão de ações é usado para organizar a lógica de negócios em classes separadas. Cada ação representa uma única responsabilidade ou tarefa.

### Exemplo de uma ação

```php
namespace App\Actions;

use App\Models\User;

class CreateUserAction
{
    public function execute(array $data): User
    {
        return User::create($data);
    }
}
```

### Usando a ação em um controlador

```php
namespace App\Http\Controllers;

use App\Actions\CreateUserAction;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request, CreateUserAction $createUserAction)
    {
        $user = $createUserAction->execute($request->all());

        return response()->json($user, 201);
    }
}
```

## Configuração de Testes com Tenancy

### Exemplo de configuração de testes com Tenancy

No arquivo [`TestTenancyCase.php`](command:_github.copilot.openRelativePath?%5B%7B%22scheme%22%3A%22file%22%2C%22authority%22%3A%22%22%2C%22path%22%3A%22%2Fhome%2Fjones%2FWebDev%2Fgateway%2Ftests%2FTestTenancyCase.php%22%2C%22query%22%3A%22%22%2C%22fragment%22%3A%22%22%7D%5D "/home/jones/WebDev/gateway/tests/TestTenancyCase.php"), você pode configurar a inicialização e limpeza do banco de dados para cada tenant:

```php
<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class TestTenancyCase extends TestCase
{
    protected $tenant;
    protected $tenantDomain;

    protected function setUp(): void
    {
        parent::setUp();

        // Configuração do tenant
        $this->tenant = Tenant::factory()->create();
        $this->tenantDomain = 'tenant' . $this->tenant->id . '.example.com';

        Domain::create([
            'domain' => $this->tenantDomain,
            'tenant_id' => $this->tenant->id,
        ]);

        // Executar migrações e seeders para o tenant
        $this->artisan('tenants:migrate', [
            '--tenants' => [$this->tenant->id],
        ]);

        $this->artisan('tenants:seed', [
            '--class' => 'Database\Seeders\Development\RolePermissionSeeder',
            '--tenants' => [$this->tenant->id],
        ]);

        tenancy()->initialize($this->tenant);

        $this->artisan('tenants:seed', [
            '--class' => 'Database\Seeders\Development\RolePermissionSeeder',
            '--tenants' => [$this->tenant->id],
        ]);
    }

    protected static function truncateTables()
    {
        DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = DB::connection('tenant')->select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            if (DB::connection('tenant')->table($tableName)->count()){
                DB::connection('tenant')->table($tableName)->truncate();
            }
        }
        DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
```

## Contribuição

1. Faça um fork do projeto.
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`).
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`).
4. Faça o push para a branch (`git push origin feature/nova-feature`).
5. Crie um novo Pull Request.

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.
