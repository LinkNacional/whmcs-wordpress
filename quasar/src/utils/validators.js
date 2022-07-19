import translations from 'src/boot/translations'

const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/

export const requiredField = val => !!val || 'Campo obrigatório'

export const validateEmail = (email) => {
  if (email === '') {
    return translations.emailErrorNull
  } else if (email.indexOf('@') === -1) {
    return translations.emailErrorNotDomain
  } else if (!emailPattern.test(email)) {
    return translations.emailErrorNotValid
  } else {
    return true
  }
}
