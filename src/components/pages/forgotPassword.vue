<template>
  <div>
    <b-container>
      <b-row align-h="center" align-v="center" class="h-100vh">
        <b-col md="6">
          <b-card header-tag="header">
            <template #header>
              <h6 class="mb-0">Forgot Password</h6>
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

            <div class="d-flex align-items-center justify-content-between">
              <b-button
                type="button"
                @click="doForgotPassword"
                :disabled="isLoading"
                variant="primary"
                >Submit</b-button
              >
              <router-link :to="{ name: 'login' }">Login</router-link>
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
  name: "forgotPasswordVue",
  data() {
    return {
      isLoading: false,
      form: {
        email: "",
      },
    };
  },
  methods: {
    async doForgotPassword() {
      if (this.form.email == "") {
        Vue.$toast.error("Please Enter Email address", {
          position: "top-right",
          duration: 5000,
        });
      } else {
        this.isLoading = true;
        const doForgotPassword = await this.callApi(
          "post",
          this.apiPath + "forgot-password",
          {
            email: this.form.email,
          }
        );

        if (doForgotPassword.status == 200) {
          this.isLoading = false;
          if (doForgotPassword.data.code == 1) {
            this.form.email = "";
            Vue.$toast.success(doForgotPassword.data.data, {
              position: "top-right",
              duration: 5000,
            });
          } else {
            Vue.$toast.error(doForgotPassword.data.data, {
              position: "top-right",
              duration: 5000,
            });
          }
        } else {
          this.isLoading = false;
          Vue.$toast.error(
            "Password reset request not sent, please try again",
            {
              position: "top-right",
              duration: 5000,
            }
          );
        }
      }
    },
  },
};
</script>
