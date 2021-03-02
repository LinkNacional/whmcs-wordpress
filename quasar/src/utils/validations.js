const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/
const senhaPattern = /^.{6,}$/
const cepPattern = /^.{9,}$/
const domainPattern = /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/
const dataPattern = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/
const nomePattern = /^(\S{2,})(.\s*)+$/
const estadoPattern = /^([A-Z]{2,2})/

export const email = val => emailPattern.test(val) || 'Por favor informe um endereço de e-mail válido'
export const required = val => !!val || 'Campo obrigatório'
export const senhaCheck = val => senhaPattern.test(val) || 'Senha com minimo de 6 caracteres'
export const cpf = val => checkCPF(val) || 'Informe um CPF válido'
export const cnpj = val => checkCNPJ(val) || 'Informe um CNPJ válido'
export const nascimento = val => checkNascimento(val) || 'Informe a data de nacimento completa, exemplo: 31/12/1981'
export const cep = val => cepPattern.test(val) || 'Informe um CEP válido'
export const domain = val => domainPattern.test(val) || 'Informe um domínio válido'
export const data = val => dataPattern.test(val) || 'Informe uma data válida no formato DD/MM/AAAA'
export const creditCardNumber = val => checkCard(val) || 'Número de cartão inválido'
export const nome = val => nomePattern.test(val) || 'Por favor informe seu nome completo'
export const estado = val => estadoPattern.test(val) || 'Informe um estado válido, exemplo: DF'
export const expCard = val => checkVencimentoCartao(val) || 'Informe uma data de expiração válida, exemplo: 05/2021'

function checkCPF (cpf) {
  // Verifica se o CPF é do tipo string
  if (typeof cpf !== 'string') return false

  // Substitui todos os caracteres que não forem números
  cpf = cpf.replace(/[\s.-]*/igm, '')

  // Valida se o valor passado é uma sequência inválida padrão ou com tamanho maior que 11 caracteres
  if (
    !cpf ||
          cpf.length !== 11 ||
          cpf === '00000000000' ||
          cpf === '11111111111' ||
          cpf === '22222222222' ||
          cpf === '33333333333' ||
          cpf === '44444444444' ||
          cpf === '55555555555' ||
          cpf === '66666666666' ||
          cpf === '77777777777' ||
          cpf === '88888888888' ||
          cpf === '99999999999'
  ) {
    return false
  }
  // Declaração das variáveis
  let soma = 0
  let resto
  let i = 1

  // Percorre o array de caracteres validando os números antes do dígito verificador
  for (i = 1; i <= 9; i++) { soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i) }
  resto = (soma * 10) % 11
  if ((resto === 10) || (resto === 11)) resto = 0
  if (resto !== parseInt(cpf.substring(9, 10))) return false

  // Reseta a variável soma
  soma = 0

  // Valida os dígitos verificadores
  for (i = 1; i <= 10; i++) { soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i) }
  resto = (soma * 10) % 11
  if ((resto === 10) || (resto === 11)) resto = 0
  if (resto !== parseInt(cpf.substring(10, 11))) return false
  // Se passar por todas as validações retorna true
  return true
}

function checkCNPJ (value) {
  if (!value) return false

  if (value === null || value === '') {
    return false
  }

  // Aceita receber o valor como string, número ou array com todos os dígitos
  const validTypes =
      typeof value === 'string' || Number.isInteger(value) || Array.isArray(value)

  // Elimina valor em formato inválido
  if (!validTypes) return false

  // Guarda um array com todos os dígitos do valor
  const match = value.toString().match(/\d/g)
  const numbers = Array.isArray(match) ? match.map(Number) : []

  // Valida se tiver 15 dígitos retirando o primeiro 0
  if (numbers[0] === 0) {
    if (numbers[14] !== undefined && numbers[14] !== null) {
      numbers.shift()
    }
  }

  // Valida a quantidade de dígitos
  if (numbers.length !== 14) return false

  // Elimina inválidos com todos os dígitos iguais
  const items = [...new Set(numbers)]
  if (items.length === 1) return false

  // Cálculo validador
  const calc = (x) => {
    const slice = numbers.slice(0, x)
    let factor = x - 7
    let sum = 0

    for (let i = x; i >= 1; i--) {
      const n = slice[x - i]
      sum += n * factor--
      if (factor < 2) factor = 9
    }

    const result = 11 - (sum % 11)

    return result > 9 ? 0 : result
  }

  // Separa os 2 últimos dígitos de verificadores
  const digits = numbers.slice(12)

  // Valida 1o. dígito verificador
  const digit0 = calc(12)
  if (digit0 !== digits[0]) return false

  // Valida 2o. dígito verificador
  const digit1 = calc(13)
  if (digit1 === digits[1]) {
    return true
  } else {
    return false
  }
}

function checkNascimento (stringData) {
  /** ****** VALIDA DATA NO FORMATO DD/MM/AAAA *******/
  const regExpCaracter = /[^\d]/ // Expressão regular para procurar caracter não-numérico
  if (stringData.length !== 10) {
    return false
  }

  // Divide a String de data com o caractere /
  const splitData = stringData.split('/')

  // Verifica se a string foi dividida corretamente
  if (splitData.length !== 3) {
    return false
  }

  // Verifica a quantidade de caracteres da data
  if ((splitData[0].length !== 2) || (splitData[1].length !== 2) || (splitData[2].length !== 4)) {
    return false
  }

  /* Procura por caracter não-numérico. EX.: o "x" em "28/09/2x11" */
  if (regExpCaracter.test(splitData[0]) || regExpCaracter.test(splitData[1]) || regExpCaracter.test(splitData[2])) {
    return false
  }

  // Variáveis a serem utilizadas para validação
  const dia = parseInt(splitData[0], 10)
  const mes = parseInt(splitData[1], 10) - 1 // O JavaScript representa o mês de 0 a 11 (0->janeiro, 1->fevereiro... 11->dezembro)
  const ano = parseInt(splitData[2], 10)
  const novaData = new Date(ano, mes, dia)
  const hoje = new Date()
  const idade = hoje.getFullYear() - novaData.getFullYear()

  // Caso a data informada seja maior que a data de hoje
  if (novaData > hoje) {
    return false
  }

  // Inválido para anos menores que 1900
  if (novaData.getFullYear() < 1900) {
    return false
  }

  // Inválido se a pessoa tiver mais de 150 anos ou menos que 16
  if (idade > 150 || idade < 16) {
    return false || 'Precisa ter mais que 16 anos'
  }

  // Caso os atributos do objeto data estejam diferentes dos valores passados
  if ((novaData.getDate() !== dia) || (novaData.getMonth() !== mes) || (novaData.getFullYear() !== ano)) {
    return false
  } else {
    return true
  }
}

function checkCard (card) {
  // Atributos com os regex para validação dos cartões usado para failsafe
  const visa = /^4\d{12}(\d{3})?$/,
    mastercard = /^(5[1-5]\d{4}|677189)\d{10}$/,
    diners = /^3(0[0-5]|[68]\d)\d{11}$/,
    discover = /^6(?:011|5[0-9]{2})[0-9]{12}$/,
    amex = /^3[47]\d{13}$/,
    jcb = /^(?:2131|1800|35\d{3})\d{11}$/

  // Aceita apenas numeros
  if (/[^0-9\s]+/.test(card)) {
    return false || 'Retire barras, traços e/ou pontuação'
  }

  if (card.length < 13 || card.length > 19) {
    return false || 'Número de cartão inválido'
  }
  // Algoritmo de Luhn para verificação de cartões genéricos
  let nCheck = 0
  let par = false

  card = card.replace(/\D/g, '')
  card = card.replace(/\s/g, '')

  // Percorre toda a string do cartão
  for (var n = card.length - 1; n >= 0; n--) {
    // Pega o digito verificador do cartão
    var cDigit = card.charAt(n),
      // Pega o valor atual da string do cartão
      nDigit = parseInt(cDigit, 10)

    if (par && (nDigit *= 2) > 9) {
      nDigit -= 9
    }

    nCheck += nDigit
    par = !par
  }

  // Fim do algoritmo de Luhn
  if ((nCheck % 10) === 0 && nCheck > 0) {
    // Failsafe com regex
    const arrayFlags = [visa, mastercard, diners, discover, amex, jcb]

    for (var i = 0; i < arrayFlags.length; i++) {
      if (arrayFlags[i].test(card)) {
        return true
      }
    }
  } else {
    return false || 'Número de cartão inválido'
  }
}

function checkVencimentoCartao (stringData) {
/** ****** VALIDA DATA NO FORMATO DD/MM/AAAA *******/
  const regExpCaracter = /[^\d]/ // Expressão regular para procurar caracter não-numérico
  if (stringData.length !== 7) {
    return false
  }

  // Divide a String de data com o caractere /
  const splitData = stringData.split('/')

  // Verifica se a string foi dividida corretamente
  if (splitData.length !== 2) {
    return false
  }

  // Verifica a quantidade de caracteres da data
  if ((splitData[0].length !== 2) || (splitData[1].length !== 4)) {
    return false
  }

  /* Procura por caracter não-numérico. EX.: o "x" em "28/09/2x11" */
  if (regExpCaracter.test(splitData[0]) || regExpCaracter.test(splitData[1])) {
    return false
  }

  // Variáveis a serem utilizadas para validação
  const hoje = new Date()
  const mes = parseInt(splitData[0], 10) - 1 // O JavaScript representa o mês de 0 a 11 (0->janeiro, 1->fevereiro... 11->dezembro)
  const ano = parseInt(splitData[1], 10)
  const novaData = new Date(ano, mes)

  // Caso os atributos do objeto data estejam diferentes dos valores passados
  if ((novaData.getMonth() !== mes) || (novaData.getFullYear() !== ano)) {
    return false
  }

  // Verifica se a data de expiração é válida se baseando no ano atual
  if (ano > hoje.getFullYear()) {
    return true
    // Caso estejamos no ano do vencimento verifica se o mês de vencimento já passou
  } else if (ano === hoje.getFullYear() && mes >= hoje.getMonth()) {
    return true
  } else {
    return false || 'Certifique-se que seu cartão não esteja expirado'
  }
}
