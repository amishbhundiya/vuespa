<template>
  <div>
    <b-container>
      <b-row align-h="center" align-v="center" class="h-100vh">
        <b-col md="6">
          <b-card header-tag="header">
            <template #header>
              <h6 class="mb-0">Login</h6>
            </template>

            <b-form-group
              id="input-group-1"
              label="Email address"
              label-for="input-1"
              class="mb-4"
            >
              <b-form-input
                id="input-1"
                v-model="form.email"
                type="email"
                required
              ></b-form-input>
            </b-form-group>

            <b-form-group
              id="input-group-2"
              label="Password"
              label-for="input-2"
              class="mb-4"
            >
              <b-form-input
                id="input-2"
                v-model="form.password"
                type="password"
                required
              ></b-form-input>
            </b-form-group>

            <div class="d-flex align-items-center justify-content-between">
              <b-button
                type="button"
                @click="doLogin"
                :disabled="isLoading"
                variant="primary"
                >Submit</b-button
              >
              <router-link :to="{ name: 'forgotpassword' }"
                >Forgot Password?</router-link
              >
              <router-link :to="{ name: 'signup' }">Signup</router-link>
            </div>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Vue from "vue";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
Vue.use(VueToast);
export default {
  name: "loginVue",
  data() {
    return {
      isLoading: false,
      form: {
        email: "",
        password: "",
      },
    };
  },
  methods: {
    async doLogin() {
      if (this.form.email == "") {
        Vue.$toast.error("Please Enter Email address", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.password == "") {
        Vue.$toast.error("Please Enter Password", {
          position: "top-right",
          duration: 5000,
        });
      } else {
        this.isLoading = true;
        const doLogin = await this.callApi("post", this.apiPath + "login", {
          email: this.form.email,
          password: this.form.password,
        });

        if (doLogin.status == 200) {
          this.isLoading = false;
          if (doLogin.data.code == 1) {
            this.form.email = "";
            this.form.password = "";

            localStorage.setItem("userData", doLogin.data.token);

            this.$router.push({ name: "productlist" });
          } else {
            Vue.$toast.error(doLogin.data.data, {
              position: "top-right",
              duration: 5000,
            });
          }
        } else {
          this.isLoading = false;
          Vue.$toast.error("Login not done, please try again", {
            position: "top-right",
            duration: 5000,
          });
        }
      }
    },
  },
};
</script>
