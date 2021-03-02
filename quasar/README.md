# Quasar App (readme)

A Quasar Framework app


## Install the dependencies
```bash
npm install
```

### Start the app in development mode (hot-code reloading, error reporting, etc.)
```bash
quasar dev
```

### Lint the files
```bash
npm run lint
```

### Build the app for production
```bash
quasar build
```

### Customize the configuration
See [Configuring quasar.conf.js](https://quasar.dev/quasar-cli/quasar-conf-js).

## Modificar o caminho para login
em **src/boot/axios.js** você pode mudar o caminho para o login
e em seguida pode usar o comando **quasar build** para compilar sua modificação.Para aplicar essa modificação copie a pasta **/dist**, subistiua a da versão da branch **build** e atualize os nomes dos arquivos .js em **dist/js** no arquivo **/index.php**