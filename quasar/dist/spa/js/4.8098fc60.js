(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[4],{"8b24":function(r,e,t){"use strict";t.r(e);var s=function(){var r=this,e=r.$createElement,t=r._self._c||e;return t("loginForm")},a=[],o=function(){var r=this,e=r.$createElement,t=r._self._c||e;return t("div",{class:r.varsFromWordpress.divSize?r.varsFromWordpress.divSize:"q-pa-xl"},[t("q-stepper",{ref:"stepper",attrs:{color:"primary",animated:"","done-color":"green","header-nagivation":!1,"no-header-navigation":""},scopedSlots:r._u([{key:"navigation",fn:function(){return[t("q-stepper-navigation",[t("div",{staticClass:"row"},[t("q-btn",{staticClass:"col-3 responsive-button",staticStyle:{color:"##0C71C3","min-width":"5%","max-height":"35px"},attrs:{color:"primary",label:2===r.step?r.varsFromWordpress.strings["btnEnter"]:r.varsFromWordpress.strings["btnNext"]},on:{click:function(e){return r.nextStep()}}}),1==r.step?t("div",{staticClass:"col-7 responsive-div"}):r._e(),r.BtnPassword?t("div",{staticClass:"col-5 responsive-div"}):r._e(),1==r.step?t("q-btn",{staticClass:"col-2 responsive-button",attrs:{flat:"",color:"primary",label:r.varsFromWordpress.strings["btnRegister"]},on:{click:function(e){return r.redirect_register()}}}):r._e(),r.BtnPassword?t("q-btn",{staticClass:"col-4 responsive-button",staticStyle:{color:"#E31E17"},attrs:{flat:"",label:r.varsFromWordpress.strings["btnPassword"]},on:{click:function(e){return r.redirect_password()}}}):r._e()],1)])]},proxy:!0}]),model:{value:r.step,callback:function(e){r.step=e},expression:"step"}},[t("q-step",{attrs:{name:1,icon:"email",done:r.step>1,title:"email"}},[r.varsFromWordpress.textHeader?t("div",{staticClass:"q-ma-none q-pb-lg"},[t("div",{staticClass:"text-h4 text-weight-thin"},[r._v(r._s(r.varsFromWordpress.textHeader))]),t("hr")]):r._e(),t("q-input",{ref:"email",attrs:{filled:"",autofocus:"",type:"email",label:r.varsFromWordpress.strings["emailLabel"],rules:[function(e){return""!==e.email||r.varsFromWordpress.strings["emailErrorNull"]},function(e){return-1!==e.indexOf("@")||r.varsFromWordpress.strings["emailErrorNotDomain"]},function(e){return r.errorInput.errorEmail||r.varsFromWordpress.strings["emailErrorNotUser"]},function(e){return e.indexOf("@")!==e.length-1||r.varsFromWordpress.strings["emailErrorNotValid"]}],"lazy-rules":"ondemand",loading:r.loadingState},on:{keydown:function(e){return!e.type.indexOf("key")&&r._k(e.keyCode,"enter",13,e.key,"Enter")?null:(e.preventDefault(),r.nextStep(e))}},model:{value:r.formulario.email,callback:function(e){r.$set(r.formulario,"email",e)},expression:"formulario.email"}})],1),t("q-step",{attrs:{name:2,icon:"password",done:r.step>2,title:"Senha"}},[r.varsFromWordpress.textHeader?t("div",{staticClass:"q-ma-none q-pb-lg"},[t("div",{staticClass:"text-h4 text-weight-thin"},[r._v(r._s(r.varsFromWordpress.textHeader))]),t("hr")]):r._e(),t("q-input",{ref:"senha",attrs:{filled:"",label:r.varsFromWordpress.strings["passwordLabel"],type:r.isPwd?"password":"text",autocomplete:"off",rules:[function(e){return r.errorInput.errorPassword||r.varsFromWordpress.strings["passwordError"]},r.checkButton],loading:r.loadingState,"lazy-rules":"ondemand"},on:{keydown:function(e){return!e.type.indexOf("key")&&r._k(e.keyCode,"enter",13,e.key,"Enter")?null:(e.preventDefault(),r.nextStep(e))}},scopedSlots:r._u([{key:"append",fn:function(){return[t("q-icon",{staticClass:"cursor-pointer",attrs:{name:r.isPwd?"visibility_off":"visibility"},on:{click:function(e){r.isPwd=!r.isPwd}}})]},proxy:!0}]),model:{value:r.formulario.senha,callback:function(e){r.$set(r.formulario,"senha",e)},expression:"formulario.senha"}})],1)],1)],1)},i=[],n=(t("e6cf"),t("758b")),l=login_whmcs_content,d=login_whmcs_size,c=email_label,p=email_error_null,u=email_error_not_domain,m=email_error_not_user,h=email_error_not_valid,f=password_label,v=password_error,g=btn_next,w=btn_enter,_=btn_register,b=btn_password,x={emailLabel:c,emailErrorNull:p,emailErrorNotDomain:u,emailErrorNotUser:m,emailErrorNotValid:h,passwordLabel:f,passwordError:v,btnNext:g,btnEnter:w,btnRegister:_,btnPassword:b},y={data(){return{step:1,loadingState:!1,isPwd:!0,urls:"",BtnPassword:!1,formulario:{senha:"",email:"",idCliente:"",passwordhash:""},errorInput:{errorEmail:!0,errorPassword:!0},varsFromWordpress:{textHeader:l,divSize:d,strings:x}}},methods:{async login(){this.$refs.senha.validate(),this.$refs.senha.hasError||""===this.formulario.senha?(this.errorInput.errorPassword=!1,this.$refs.senha.validate(),this.errorInput.errorPassword=!0,this.BtnPassword=!0):(this.loadingState=!0,n["a"].post("",{action:"ValidateLogin",email:this.formulario.email,senha:this.formulario.senha}).then((r=>{"success"===r.data.result?(this.url=r.data.redirect_url,window.location.href=this.url,this.loadingState=!1):"password"===r.data.result&&(this.BtnPassword=!0,this.loadingState=!1,this.errorInput.errorPassword=!1,this.$refs.senha.validate(),this.errorInput.errorPassword=!0)})).catch((r=>{this.loadingState=!1,console.log(r)})))},nextStep(){1===this.step?(this.$refs.email.validate(),this.$refs.email.hasError||""===this.formulario.email?(this.$refs.email.validate(),this.$refs.email.focus()):this.checkEmail()):this.login()},async checkEmail(){this.loadingState=!0,n["a"].post("",{action:"checkEmail",email:this.formulario.email}).then((r=>{"success"===r.data.result?(this.$refs.email.validate(),this.$refs.stepper.next(),setTimeout((()=>{this.$refs.senha.focus()}),200)):"notin"===r.data.result&&(this.errorInput.errorEmail=!1,this.$refs.email.validate(),this.errorInput.errorEmail=!0),this.loadingState=!1})).catch((r=>{this.loadingState=!1,console.log(r)}))},redirect_password(){n["a"].post("",{action:"url_redirect"}).then((r=>{this.urls=r.data,window.location.href=this.urls.password}))},redirect_register(){n["a"].post("",{action:"url_redirect"}).then((r=>{this.urls=r.data,window.location.href=this.urls.register}))}}},E=y,k=t("2877"),S=t("f531"),F=t("87fe"),P=t("27f9"),W=t("0016"),C=t("b19c"),$=t("9c40"),q=t("eebe"),I=t.n(q),N=Object(k["a"])(E,o,i,!1,null,null,null),B=N.exports;I()(N,"components",{QStepper:S["a"],QStep:F["a"],QInput:P["a"],QIcon:W["a"],QStepperNavigation:C["a"],QBtn:$["a"]});var z={components:{loginForm:B},name:"PageIndex",created(){},methods:{myTweak(r){return{minHeight:"10px"}}}},H=z,O=Object(k["a"])(H,s,a,!1,null,null,null);e["default"]=O.exports}}]);