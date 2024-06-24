# i-Educar Educacenso

Módulo desacoplado do Educacenso para o [i-Educar](https://github.com/portabilis/i-educar).

## Instalação

> Para usuários Docker, executar os comandos `# (Docker)` ao invés da linha seguinte.

Clone este repositório a partir da raiz do i-Educar:

```bash
git clone git@github.com:portabilis/i-educar-educacenso-package.git packages/portabilis/i-educar-educacenso-package
```

Instale o pacote:

```bash
# (Docker) docker-compose exec php composer plug-and-play
composer plug-and-play
```

Execute as migrations:

```bash
# (Docker) docker-compose exec php artisan migrate
php artisan migrate
```

Antes de executar as migrations certifique-se que sua variável de ambiente `LEGACY_SEED_DATA` está definida como `true` no arquivo `.env`.

Se você estiver atualizando o i-Educar aconselhamos que execute o seguinte comando: 
```bash
# (Docker) docker-compose exec php artisan cache:clear
php artisan cache:clear
```
Isso é necessário para que as mudanças de URL sejam refletidas no cache dos menus do usuário.

## Fluxo de trabalho

Todo commit, push e criação de branch de melhorias deverão ocorrer dentro da pasta
`packages/portabilis/i-educar-educacenso-package`, dessa forma você estará manipulando o 
repositório do Educacenso e não o repositório principal do i-Educar.

## Execução de testes

Os comandos abaixo devem ser executados a partir da raiz do i-Educar.

Adicionar as dependência do `orchestra/testbench` ao plug and play.

```bash
composer plug-and-play:add orchestra/testbench ^9
composer plug-and-play:update
```

### Executar os testes:

```bash
vendor/bin/pest -c packages/portabilis/i-educar-educacenso-package/phpunit.package.xml --test-directory=packages/portabilis/i-educar-educacenso-package/tests
``` 

## Perguntas frequentes (FAQ)

Algumas perguntas aparecem recorrentemente. Olhe primeiro por aqui:
[FAQ](https://github.com/portabilis/i-educar-website/blob/master/docs/faq.md).

---

Powered by [Portábilis](https://portabilis.com.br/).
