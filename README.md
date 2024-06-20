# i-Educar Educacenso

Módulo desacoplado do Educacenso para o [i-Educar](https://github.com/portabilis/i-educar).

## Instalação

> Para usuários Docker, executar os comandos `# (Docker)` ao invés da linha seguinte.

Clone este repositório a partir da raiz do i-Educar:

```bash
git clone git@github.com:portabilis/i-educar-educacenso.git packages/portabilis/i-educar-educacenso
```

Instale o pacote:

```bash
# (Docker) docker-compose exec php composer plug-and-play
composer plug-and-play
```

## Fluxo de trabalho

Todo commit, push e criação de branch de melhorias deverão ocorrer dentro da pasta
`packages/portabilis/i-educar-educacenso`, dessa forma você estará manipulando o 
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
vendor/bin/pest -c packages/portabilis/i-educar-educacenso/phpunit.package.xml --test-directory=packages/portabilis/i-educar-educacenso/tests
``` 

## Perguntas frequentes (FAQ)

Algumas perguntas aparecem recorrentemente. Olhe primeiro por aqui:
[FAQ](https://github.com/portabilis/i-educar-website/blob/master/docs/faq.md).

---

Powered by [Portábilis](https://portabilis.com.br/).
