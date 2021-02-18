<template>

  <div class="q-pa-xl">
      <q-stepper
      v-model="step"
      ref="stepper"
      color="primary"
      animated
      done-color="green"
      :header-nagivation="false"
      no-header-navigation
    >
      <q-step
        :name="1"
        icon="email"
        :done="step > 1"
      >
    <div class="q-ma-none q-pb-lg">
      <div class="text-h4 text-weight-thin">Login</div>
      <hr />
    </div>
        <q-input
        filled
        autofocus
        v-model="formulario.email"
        ref="email"
        type="email"
        label="Seu email"
        :rules="[ val => val.email !== '' || 'Digite um Email',
        val => val.indexOf('@') !== -1 || 'Digite um Email Válido',
        val => errorInput.errorEmail || 'Não existe usuario com este email',
        val => val.indexOf('@') !== val.length - 1 || 'Digite um Email Válido']"
        @keydown.enter.prevent="nextStep"
        lazy-rules="ondemand"
        :loading="loadingState"
        />

      </q-step>
       <q-step
        :name="2"
        icon="password"
        :done="step > 2"
      >
      <div class="q-ma-none q-pb-lg">
      <div class="text-h4 text-weight-thin">Login</div>
      <hr />
    </div>
       <q-input
        v-model="formulario.senha"
        filled
        ref="senha"
        label="Sua senha"
        :type="isPwd ? 'password' : 'text'"
        @keydown.enter.prevent="nextStep"
        autocomplete="off"
        :rules="[ val => errorInput.errorPassword || 'Digite uma senha válida']"
        :loading="loadingState"
        lazy-rules="ondemand"
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
          <q-btn flat color="primary" @click="deleteAllCookies" v-bind:to="'https://whmcs.linknacional.com.br/register.php'" label="Registrar" class="q-ml-sm float-right" />
          <q-btn flat style="color:#E31E17" @click="deleteAllCookies" v-bind:to="'https://whmcs.linknacional.com.br/index.php?rp=/password/reset'" label="Esqueceu a senha?" class="q-ml-sm float-right" />
        </q-stepper-navigation>
      </template>
    </q-stepper>
  </div>
</template>

<script>
import { setUrl } from 'boot/axios'
export default {
  props: {
    url: { required: true }
  },
  data () {
    return {
      step: 1,
      loadingState: false,
      axios: setUrl(this.url),
      isPwd: true,
      formulario: {
        senha: '',
        email: '',
        idCliente: '',
        passwordhash: ''
      },
      errorInput: {
        errorEmail: true,
        errorPassword: true
      }
    }
  },
  methods: {
    async getUrl () {
      this.axios.post('index.php', { action: 'login_direct_user', uid: this.formulario.idCliente })
        .then((response) => {
          if (response.data.result === 'success') {
            this.url = response.data.redirect_url
            window.location.href = this.url
          } else {
            console.log('Erro nos dados informados')
          }
        }).catch((error) => {
          console.log(error)
        })
    },
    async login () {
      this.$refs.senha.validate()
      if (this.$refs.senha.hasError || this.formulario.senha === '') {
        this.errorInput.errorPassword = false
        this.$refs.senha.validate()
        this.errorInput.errorPassword = true
        this.mostrarMensagem('Para continuar, por favor informe uma senha.')
      } else {
        this.loadingState = true
        this.axios.post('index.php', { action: 'login', email: this.formulario.email, senha: this.formulario.senha })
          .then((response) => {
            if (response.data.result === 'success') {
              this.formulario.idCliente = response.data.userid
              this.formulario.passwordhash = response.data.passwordhash
              this.getUrl()
              this.loadingState = false
            } else if (response.data.message === 'Email or Password Invalid') {
              this.loadingState = false
              this.errorInput.errorPassword = false
              this.$refs.senha.validate()
              this.errorInput.errorPassword = true
              this.mostrarMensagem('E-mail ou senha inválido')
            }
          })
          .catch((error) => {
            this.loadingState = false
            console.log(error)
          })
      }
    },
    nextStep () {
      if (this.step === 1) {
        this.$refs.email.validate()
        if (this.$refs.email.hasError || this.formulario.email === '') {
          this.$refs.email.validate()
          this.mostrarMensagem('Para continuar, por favor informe um email válido.')
          this.$refs.email.focus()
        } else {
          this.checkEmail()
        }
      } else {
        this.login()
      }
    },
    async checkEmail () {
      this.loadingState = true
      this.axios.post('index.php', { action: 'email_search_not_phone', email: this.formulario.email })
        .then((response) => {
          if (response.data.result === 'success') {
            this.$refs.email.validate()
            this.$refs.stepper.next()
            setTimeout(() => {
              this.$refs.senha.focus()
            }, 200)
          } else if (response.data.result === 'notin') {
            this.errorInput.errorEmail = false
            this.$refs.email.validate()
            this.errorInput.errorEmail = true
          }
          this.loadingState = false
        }).catch((error) => {
          this.loadingState = false
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
    },
    btnRegistrar () {
      window.location.href = 'https://whmcs.linknacional.com.br/register.php'
    },
    btnSenha () {
      window.location.href = 'https://whmcs.linknacional.com.br/index.php?rp=/password/reset'
    }
  }
}
</script>
