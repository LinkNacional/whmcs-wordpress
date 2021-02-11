<template>
  <div class="q-pa-md">
    <q-form class="q-gutter-md" >
    <q-stepper
      v-model="step"
      header-nav
      ref="stepper"
      color="primary"
      animated
    >
     <q-step
        :name="1"
        title="Email"
        icon="email"
        :done="step > 1"
        :header-nav="step > 1"
      >
        <q-input
        filled
        lazy-rules
        autofocus
        v-model="formulario.email"
        type="email"
        label="Seu email"
        />

      </q-step>

      <q-step
        :name="2"
        title="Senha"
        icon="password"
      >
       <q-input
        v-model="formulario.senha"
        filled
        lazy-rules
        ref="senha"
        label="Sua senha"
        :type="isPwd ? 'password' : 'text'"
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
          <q-btn @click="nextStep()" color="primary" :label="step === 2 ? 'Entrar' : 'Proximo'" />
          <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" label="Voltar" class="q-ml-sm" />
        </q-stepper-navigation>
      </template>
    </q-stepper>
    </q-form>
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
        })
      return false
    },
    async login () {
      if (this.formulario.email === '') {
      } else if (this.$refs.senha.hasError || this.formulario.senha === '') {
        this.mostrarMensagem('Para continuar, por favor informe uma senha.')
      } else {
        console.log({ action: 'login', email: this.formulario.email, senha: this.formulario.senha })
        axiosInstance.post('index.php', { action: 'login', email: this.formulario.email, senha: this.formulario.senha })
          .then((response) => {
            console.log(response)
            if (response.data.result === 'success') {
              this.formulario.idCliente = response.data.userid
              this.formulario.passwordhash = response.data.passwordhash
              this.getUrl()
            } else if (response.data.message === 'Email or Password Invalid') {
              this.mostrarMensagem('Email ou Senha Invalido')
            }
          })
          .catch((error) => {
            console.log(error)
          })
      }
    },
    nextStep () {
      if (this.step !== 2) {
        console.log(this.formulario.email.indexOf('@'))
        console.log(this.formulario.email.length - 1)
        if (this.formulario.email === '' || this.formulario.email.indexOf('@') === -1 || this.formulario.email.indexOf('@') === this.formulario.email.length - 1) {
          this.mostrarMensagem('Para continuar, por favor informe um email válido.')
        } else {
          this.$refs.stepper.next()
        }
      } else {
        this.login()
      }
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
