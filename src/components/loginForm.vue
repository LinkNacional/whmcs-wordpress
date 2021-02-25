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
        title="email"
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
        :rules="[ val => val.email !== '' || 'Digite um e-mail',
        val => val.indexOf('@') !== -1 || 'Digite um e-mail cadastrado',
        val => errorInput.errorEmail || 'Não existe nenhum usuário cadastrado com este e-mail',
        val => val.indexOf('@') !== val.length - 1 || 'Digite um e-mail válido']"
        @keydown.enter.prevent="nextStep"
        lazy-rules="ondemand"
        :loading="loadingState"
        />

      </q-step>
       <q-step
        :name="2"
        icon="password"
        :done="step > 2"
        title="senha"
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
        :rules="[ val => errorInput.errorPassword || 'Digite sua senha de acesso']"
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
          <q-btn @click="nextStep()" style="color:##0C71C3" color="primary" :label="step === 2 ? 'Entrar' : 'Próximo'" />
          <q-btn v-if="step > 1" flat style="color:##0C71C3" @click="$refs.stepper.previous()" label="Voltar" class="q-ml-sm" />
          <q-btn flat  color="primary" v-on:click="redirect_register()" label="Registrar" class="q-ml-sm float-right" />
          <q-btn flat style="color:#E31E17" v-on:click="redirect_password()" label="Esqueceu a senha?" class="q-ml-sm float-right" />
        </q-stepper-navigation>
      </template>
    </q-stepper>
  </div>
</template>

<script>
import { axiosInstance } from 'boot/axios'
export default {
  data () {
    return {
      step: 1,
      loadingState: false,
      isPwd: true,
      urls:'',
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
  mounted: function () {
  },
  methods: {
    async getUrl () {
      axiosInstance.post('', { action: 'CreateSsoToken', uid: this.formulario.idCliente })
        .then((response) => {
            console.log(response)
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
        // this.mostrarMensagem('Para continuar, por favor informe uma senha.')
      } else {
        this.loadingState = true
        axiosInstance.post('', { action: 'ValidateLogin', email: this.formulario.email, senha: this.formulario.senha })
          .then((response) => {
            console.log(response)
            if (response.data.result === 'success') {
              this.formulario.idCliente = response.data.userid
              this.formulario.passwordhash = response.data.passwordhash
              this.getUrl()
              this.loadingState = false
            } else if (response.data.result === 'password') {
              this.loadingState = false
              this.errorInput.errorPassword = false
              this.$refs.senha.validate()
              this.errorInput.errorPassword = true
              // this.mostrarMensagem('senha inválida')
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
          // this.mostrarMensagem('Para continuar, por favor informe um email válido.')
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
      axiosInstance.post('', { action: 'checkEmail', email: this.formulario.email })
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
    // mostrarMensagem (msg) {
    //   this.$q.notify({
    //     progress: true,
    //     message: msg,
    //     color: 'primary',
    //     multiLine: true,
    //     actions: [
    //       { label: 'Entendi', color: 'yellow', handler: () => { /* ... */ } }
    //     ]
    //   })
    // },
    redirect_password () {
      axiosInstance.post('', { action: 'url_redirect' })
        .then((response) => {
          this.urls = response.data
          window.location.href=this.urls.password
      })
     
    },
    redirect_register () {
       axiosInstance.post('', { action: 'url_redirect' })
        .then((response) => {
          this.urls = response.data
          window.location.href=this.urls.register
      })
    },
  }
}
</script>
