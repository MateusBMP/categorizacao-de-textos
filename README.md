# Categorização de Textos

Categorização de Textos com o Algoritmo Genético de Holland.

## Executando

Para testar esse projeto, primeiro instale as dependências via [Composer](https://getcomposer.org/) e [npm](https://www.npmjs.com/):

```sh
composer install
npm install
```

Em seguida, rode os scripts para gerar os arquivos para plotagem dos gráficos:

```sh
php scripts/evoluir_n_vezes.php
```

Os arquivos gerados estão em formado **JSON** e se encontram nos diretórios `./storage/` e `./dist/files/`.

Ao editar aquivos presentes na pasta `./src/` deve-se executar o [webpack](https://webpack.js.org/):

```sh
npm run build
```

A visualização do projeto encontra-se em `./dist/` e, portanto, qualquer servidor **NGINX** ou **APACHE2** deve direcionar para esta pasta.

## Colaboradores

- Roberta Vilhena Vieira Lopes - [Lattes](http://lattes.cnpq.br/7000283790939630)
- Jadde de Freitas Leite - [GitHub](https://github.com/Jaddefreitas)
