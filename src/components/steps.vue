<template>
  <div class="q-pa-xl">
    <!-- <q-form class="q-gutter-md" > -->
      <q-stepper
      v-model="step"
      ref="stepper"
      color="primary"
      animated
      done-color="green"
    >
      <q-step
        :name="1"
        title="Email"
        icon="email"
        :done="step > 1"
      >
        <q-input
        filled
        autofocus
        v-model="formulario.email"
        ref="email"
        type="email"
        label="Seu email"
        :loading="emailInput.loadingState"
        :rules="[ val => val.email !== '' || 'Digite um Email',
        val => val.indexOf('@') !== -1 || 'Digite um Email Válido',
        checkEmail,
        val => emailInput.errorEmail || 'Não existe usuario com este email',
        val => val.indexOf('@') !== val.length - 1 || 'Digite um Email Válido']"
        @keydown.enter.prevent="nextStep"
        />

      </q-step>

       <q-step
        :name="2"
        title="Senha"
        icon="password"
        :done="step > 2"
      >
       <q-input
        v-model="formulario.senha"
        filled
        ref="senha"
        label="Sua senha"
        :type="isPwd ? 'password' : 'text'"
        @keydown.enter.prevent="nextStep"

      >
        <template v-slot:append>
          <q-icon
            :name="isPwd ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="isPwd = !isPwd"
          />
        </template>
      </q-input>
      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn @click="nextStep()" style="color:##0C71C3" color="primary" :label="step === 2 ? 'Entrar' : 'Proximo'" />
          <q-btn v-if="step > 1" flat style="color:##0C71C3" @click="$refs.stepper.previous()" label="Voltar" class="q-ml-sm" />
          <q-btn flat color="primary" onclick="window.location.href='https://whmcs.linknacional.com.br/register.php'" label="Registrar" class="q-ml-sm float-right" />
          <q-btn flat style="color:#E31E17" onclick="window.location.href='https://whmcs.linknacional.com.br/index.php?rp=/password/reset'" label="Esqueceu a senha?" class="q-ml-sm float-right" />
        </q-stepper-navigation>
      </template>
    </q-stepper>
    <!-- </q-form> -->
  </div>
</template>

<script>
import { axiosInstance } from 'boot/axios'

export default {
  data () {
    return {
      step: 1,
      isPwd: true,
      formulario: {
        senha: '',
        email: '',
        idCliente: '',
        passwordhash: ''
      },
      emailInput: {
        loadingState: false,
        errorEmail: true
      }
    }
  },
  methods: {
    async getUrl () {
      axiosInstance.post('index.php', { action: 'login_direct_user', uid: this.formulario.idCliente })
        .then((response) => {
          if (response.data.result === 'success') {
            this.url = response.data.redirect_url
            window.location.href = this.url
            return true
          } else {
            console.log('Erro nos dados informados')
          }
        }).catch((error) => {
          console.log(error)
        })
      return false
    },
    async login () {
      if (this.$refs.senha.hasError || this.formulario.senha === '') {
        this.mostrarMensagem('Para continuar, por favor informe uma senha.')
      } else {
        axiosInstance.post('index.php', { action: 'login', email: this.formulario.email, senha: this.formulario.senha })
          .then((response) => {
            if (response.data.result === 'success') {
              this.formulario.idCliente = response.data.userid
              this.formulario.passwordhash = response.data.passwordhash
              this.getUrl()
            } else if (response.data.message === 'Email or Password Invalid') {
              this.mostrarMensagem('E-mail ou senha inválido')
            }
          })
          .catch((error) => {
            console.log(error)
          })
      }
    },
    nextStep () {
      console.log(this.step)
      if (this.step === 1) {
        this.emailInput.errorEmail = false
        if (this.$refs.email.hasError || this.formulario.email === '') {
          this.mostrarMensagem('Para continuar, por favor informe um email válido.')
          this.emailInput.errorEmail = true
          this.$refs.email.validate()
          this.$refs.email.focus()
        } else {
          this.checkEmail(this.formulario.email, true)
        }
      } else {
        this.login()
      }
    },
    async checkEmail (value = this.formulario.email, next = false) {
      axiosInstance.post('index.php', { action: 'email_search_not_phone', email: this.formulario.email })
        .then((response) => {
          if (response.data.result === 'success') {
            this.emailInput.errorEmail = true
            this.$refs.email.validate()
            if (next) {
              this.$refs.stepper.next()
              setTimeout(() => {
                this.$refs.senha.focus()
              }, 200)
            }
            return true
          } else if (response.data.result === 'notin') {
            this.emailInput.errorEmail = false
            this.$refs.email.validate()
            return false
          }
        }).catch((error) => {
          console.log(error)
        })
    },
    mostrarMensagem (msg) {
      this.$q.notify({
        progress: true,
        message: msg,
        color: 'primary',
        multiLine: true,
        actions: [
          { label: 'Entendi', color: 'yellow', handler: () => { /* ... */ } }
        ]
      })
    }
  }
}
</script>
