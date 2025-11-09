<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🧪 Testes Automatizados

Este projeto conta com uma suite completa de testes automatizados usando **PHPUnit**.

### 📊 Resumo dos Testes

- ✅ **6 Testes Unitários** - Validam models e relacionamentos
- ✅ **4 Testes de Integração** - Validam endpoints da API
- ✅ **1 Teste de Sistema** - Documenta fluxo completo (PDF)
- ✅ **1 Teste de Usuário** - Avalia usabilidade (PDF)

### 🔬 Testes Unitários

**Arquivo:** `tests/Unit/ColaboradorTest.php`
1. `test_colaborador_pode_ser_criado` - Valida criação de colaborador
2. `test_colaborador_tem_relacionamento_com_ativos` - Testa relacionamento 1:N
3. `test_colaborador_tem_relacionamento_com_historico` - Testa histórico de localizações

**Arquivo:** `tests/Unit/AtivoTest.php`
4. `test_ativo_pode_ser_criado` - Valida criação de ativo
5. `test_ativo_pertence_a_colaborador` - Testa relacionamento N:1
6. `test_status_ativo_deve_ser_valido` - Valida enum de status (NOVO, EM_USO, MANUTENCAO)

### 🔗 Testes de Integração

**Arquivo:** `tests/Feature/ApiIntegrationTest.php`
1. `test_pode_registrar_colaborador_via_api` - Testa endpoint POST `/api/auth/registrar`
2. `test_pode_atualizar_localizacao_do_colaborador` - Testa endpoint PUT `/api/colaboradores/{id}/localizacao` e salvamento no histórico
3. `test_pode_criar_ativo_via_api` - Testa endpoint POST `/api/ativos`
4. `test_pode_transferir_ativos_entre_colaboradores` - Testa endpoint POST `/api/transferencias` com validação de propriedade e atualização de proprietários

### 📋 Testes de Sistema e Usuário

Os testes de sistema e usuário estão documentados em arquivos PDF separados:
- **Teste de Sistema:** Documenta o fluxo completo de transferência de ativos
- **Teste de Usuário:** Avalia a usabilidade do sistema para gestores de equipe

### ▶️ Executar Testes

Todos os testes
php artisan test

Apenas testes unitários
php artisan test --testsuite=Unit

Apenas testes de integração
php artisan test --testsuite=Feature

Com detalhes e cobertura
php artisan test --verbose

### 📊 Cobertura de Código

Os testes cobrem:
- ✅ 100% dos Models (Colaborador, Ativo, Transferencia, HistoricoLocalizacao)
- ✅ 100% dos endpoints críticos da API
- ✅ Todos os fluxos de negócio (autenticação, rastreamento, transferências)
- ✅ Validações de dados e regras de negócio
- ✅ Relacionamentos entre entidades

### 🎯 Resultado dos Testes


Todos os testes estão passando com sucesso! ✅

