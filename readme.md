# WHMCS LOGIN
O WHMCS login é um plugin wordpress que inclui um shortcode e uma API para login no WHMCS.

# SHORTCODE
Para inserir o shortcode do login da WHMCS adicione na sua página o código

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
        "userid": 1,
        "passwordhash": "078709e495230974fe7dd9c5ae31b9725d",
        "twoFactorEnabled": false
    }


* **result** = *se o usuario existe e a senha esta correta* (`String`)
* **userid** = *id do usuario* (`Int`)
* **passwordhash** = *hash da senha* (`String`)
* **twoFactorEnabled** = *autenticação em dois fatores* (`Bool`)

**possiveis erros**

usuario existe mas a senha esta incorreta:

    {"result":"password"}


não existe usuario com este E-mail:

    {"result":"notin"}


## CRIAR TOKEN
A action `CreateSsoToken` valida o login e retorna um token de acesso valido por `60 segundos` e uma url já autenticada:

    {
        "action":"CreateSsoToken",
        "clid":"1",
        "uid":"2",
        "destination":"profile"
    }
* **action** = *a ação que esta sendo requisitada* (`String`) (`obrigatório`)
* **clid** = *ID do cliente* (`String`) (`obrigatório`)
* **uid** = *ID do usuário administrador que deve ser authenitcado. Se não for fornecido, o proprietário do cliente solicitado será assumido* (`String`) (`opcional`)
* **destination** = *página para onde o redirecionamento vai mandar o usuario autenticado. Se não for fornecido, o destino será a página inicial da área do cliente* (`String`) (`opcional`)

pode se ver a lista de `destination` em 
https://docs.whmcs.com/WHMCS_Single_Sign-On_Developer_Guide#Supported_Destinations
 (retirar a `clientarea:` da requisição)

 e como retorno:

    {
        "result": "success",
        "access_token": "afaed4482ad1e6b75492cdd421",
        "redirect_url": "http:\/\/example.test\/oauth\/singlesignon.php?access_token=afaed4482ad1e6b75492cdd421"
    }


* **result** = *se foi possivel criar o token* (`Int`)
* **access_token** =token de acesso valido por 60 segundos* (`Int`)
* **redirect_url** = *url do WHMCS com token de acesso* (`String`)

**possiveis erros**

dados enviados invalidos para criar o token:

    {"result":"notin"}

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
