# WHMCS LOGIN
O WHMCS login é um plugin wordpress que inclui um shortcode e uma API para login no WHMCS.

# SHORTCODE
Para inserir o shortcode do login da WHMCS primeiro autorize o acesso a `API REST` em `configurações>proteção por Senha` e marque a opção `Permitir acesso à API REST` ou `Allow REST API Access`

[![](http://whmcs.linknacional.com.br/prints/restAPI.png)](http://whmcs.linknacional.com.br/prints/restAPI.png)


Após isso adicione na sua página o código

    [whmcslogin][/whmcslogin]

o shortcode já vem com toda a comunicação com o WHMCS configurada,necessitando somente preencher os campos contidos no menu de configurações do plugin.

# Configurações
No admin do wordpress entre em Configurações

[![](http://whmcs.linknacional.com.br/prints/config_wordpress.png)](http://whmcs.linknacional.com.br/prints/config_wordpress.png)

após isso ira abrir submenus de configurações,se o plugin estiver instalado e ativado ira aparecer o submenu WHMCS login


[![](http://whmcs.linknacional.com.br/prints/config_open.png)](http://whmcs.linknacional.com.br/prints/config_open.png)

dentro da pagina de configurações temos os campos:
* **identificador da API WHMCS** = sequência conseguida gerando uma credencial de API no WHMCS
* **Segredo da API WHMCS** = sequência conseguida gerando uma credencial de API no WHMCS
* **url do WHMCS** = url da instalação do WHMCS 
* **link para recuperar a senha WHMCS** = link para redirecionar com o botão `ESQUECEU A SENHA?` do shortcode
* **link para registrar um novo usuario WHMCS** = link para redirecionar com o botão `REGISTRAR` do shortcode

[![](http://whmcs.linknacional.com.br/prints/tela_config_wordpress.png)](http://whmcs.linknacional.com.br/prints/tela_config_wordpress.png)
# API
acesse a API pelo endereço `/wp-json/login-whmcs/v1/login/` como no exemplo:
    
    www.meuwordpress.com.br/wp-json/login-whmcs/v1/login/
a API recebe Parâmetros via `POST` com o formato `JSON`.

o identifcador da ação solicitada é o parâmetro `action` contido no JSON da requisição

## VALIDAR LOGIN
A action `ValidateLogin` recebe o E-mail e senha do usuario como no exemplo:

    {
        "action":"ValidateLogin",
        "email":"emailDoUsuario@email.com",
        "senha":"senhaDoUsuario"
    }

* **action** = *a ação que esta sendo requisitada* (`String`) (`obrigatório`)
* **email** = *E-mail do usuario* (`String`) (`obrigatório`)
* **senha** = *senha do usuario* (`String`) (`obrigatório`)


 e como retorno:

    {
        "result": "success",
        "access_token": "2b736cb9a0fd6f72dde393051",
        "redirect_url": "https://meuwhmcs.com.br/oauth/singlesignon.php?access_token=2b736cb9a0fd6f72dde393051"
    }


* **result** = *se o usuario existe e a senha esta correta* (`String`)
* **access_token** = *token de acesso ao whmcs valido por 60 segundos* (`Int`)
* **redirect_url** = *url com o token de acesso também valido por 60 segundos* (`String`)

**possiveis erros**

usuario existe mas a senha esta incorreta:

    {"result":"password"}


não existe usuario com este E-mail:

    {"result":"notin"}

não é possivel criar uma sessão:

    {"result":"notinSession"}


## E-MAIL VALIDO
A action `checkEmail` valida se existe um usuario com este E-mail:

    {
        "action":"checkEmail",
        "email":"emailDoUsuario@email.com"
    }
* **action** = *a ação que esta sendo requisitada* (`String`) (`obrigatório`)
* **email** = *E-mail a ser verificado* (`String`) (`obrigatório`)

 e como retorno:

    {
        "result": "success"
    }


* **result** = *se existe usuario com esse E-mail* (`String`)

**possiveis erros**

não existe usuario com esse E-mail: 

    {"result":"notin"}



## URLS DE REDIRECIONAMENTO
A action `url_redirect` retorna as urls dos campos `link para recuperar a senha WHMCS` e de `link para registrar um novo usuario WHMCS` definidas na página de configurações do modulo :

    {
        "action":"url_redirect"
    }
* **action** = *a ação que esta sendo requisitada* (`String`) (`obrigatório`)

 e como retorno:

    {
        'password' => 'http:\/\/example.test\/password\/reset',
        'register' => 'http:\/\/example.test\/register'
    }
 

* **password** = *url para recuperar a senha* (`String`)
* **register** = *url para registrar-se* (`String`)

**possiveis erros**

se receber algum parâmetro vazio verificar se foram salvos nas configurações do plugin: 

    {
        'password' => '',
        'register' => ''
    }
