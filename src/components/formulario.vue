<template>
  <div class="q-px-xs">
    <div class="q-ma-none q-pb-lg">
            <div class="text-h4 text-weight-thin">Login:</div>
      <br />
    </div>
    <q-form class="q-gutter-md" v-on:submit.prevent="login">
      <q-input
        filled
        lazy-rules
        autofocus
        v-model="formulario.email"
        type="email"
        label="Seu email"
        hint="Insira um email válido"
      />

      <q-input
        v-model="formulario.senha"
        filled
        lazy-rules
        ref="senha"
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

      <div>
        <q-btn label="Continuar" type="submit" color="primary"/>
        <q-btn
          label="Mais informações"
          type="reset"
          color="primary"
          flat
          class="q-ml-sm"
          v-if="false"
        />
      </div>
    </q-form>
  </div>
</template>

<style></style>

<script>
// import { email, required, senha } from '../utils/validations'
import { axiosInstance } from 'boot/axios'
export default {
  data () {
    return {
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
          // console.log('Sucesso ' + response.data.redirect_url)
            this.url = response.data.redirect_url
            window.location.href = this.url
            return true
          } else {
          // console.log('Erro nos dados informados')
          }
        })
      return false
    },
    async login () {
      this.$refs.senha.validate()
      if (this.formulario.email === '') {
        this.mostrarMensagem('Para continuar, por favor informe um email.')
      } else if (this.$refs.senha.hasError || this.formulario.senha === '') {
        this.mostrarMensagem('Para continuar, por favor informe uma senha.')
      } else {
        axiosInstance.post('index.php', { action: 'login', email: this.formulario.email, senha: this.formulario.senha })
          .then((response) => {
            console.log(response)
            if (response.data.result === 'success') {
              this.formulario.idCliente = response.data.userid
              this.formulario.passwordhash = response.data.passwordhash
              this.getUrl()
            } else if (response.data.message === 'Email or Password Invalid') {
              this.mostrarMensagem('Email ou Senha Invalido')
              // this.$router.push({ name: 'pessoa', params: { data: this.formularioTelefone } })
              // console.log('novo cadastro')
            }
          })
          .catch((error) => {
            console.log('Error ' + error.message)
          })
      // this.$http.post(url, this.formulario)
      //   .then((response) => {
      //     console.log(response, 'funcionou')
      //   })
      //   .catch((error) => {
      //     console.log(error, 'nao funcionou')
      //   })
      }
    },
    mostrarMensagem (msg) {
      this.$q.notify({
        progress: true,
        message: msg,
        color: 'primary',
        multiLine: true,
        // avatar: 'https://cdn.quasar.dev/img/boy-avatar.png',
        actions: [
          { label: 'Entendi', color: 'yellow', handler: () => { /* ... */ } }
        ]
      })
    },
    name: 'formulario'
  }
}
</script>
