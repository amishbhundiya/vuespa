<template>
  <div>
    <b-container>
      <b-row align-h="center" align-v="center" class="h-100vh">
        <b-col md="6">
          <b-card header-tag="header">
            <template #header>
              <h6 class="mb-0">Reset Password</h6>
            </template>

            <b-form-group
              id="input-group-1"
              label="New Password"
              label-for="input-1"
              class="mb-4"
            >
              <b-form-input
                id="input-1"
                v-model="form.password"
                type="password"
                required
              ></b-form-input>
            </b-form-group>

            <b-form-group
              id="input-group-2"
              label="Confirm New Password"
              label-for="input-2"
              class="mb-4"
            >
              <b-form-input
                id="input-2"
                v-model="form.cpassword"
                type="password"
                required
              ></b-form-input>
            </b-form-group>

            <div class="d-flex align-items-center justify-content-between">
              <b-button
                type="button"
                @click="doResetPassword"
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
  name: "loginVue",
  data() {
    return {
      isLoading: false,
      form: {
        password: "",
        cpassword: "",
      },
    };
  },
  created: function () {
    this.getUserDetail(this.$route.params.token);
  },
  methods: {
    async getUserDetail(token) {
      this.isLoading = true;

      const getUserDetail = await this.callApi(
        "post",
        this.apiPath + "check-forgot-password-token",
        {
          token: token,
        }
      );

      if (getUserDetail.status == 200) {
        this.isLoading = false;
        if (getUserDetail.data.code == 1) {
          //
        } else {
          Vue.$toast.error("No Details found", {
            position: "top-right",
            duration: 5000,
          });
          this.$router.push({ name: "login" });
        }
      } else {
        this.isLoading = false;
        Vue.$toast.error("No Details found", {
          position: "top-right",
          duration: 5000,
        });
        this.$router.push({ name: "login" });
      }
    },
    async doResetPassword() {
      if (this.form.password == "") {
        Vue.$toast.error("Please Enter New Password", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.password.length < 6) {
        Vue.$toast.error("Password must be 6 character long", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.cpassword == "") {
        Vue.$toast.error("Please Enter Confirm New Password", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.password != this.form.cpassword) {
        Vue.$toast.error("New Password and Confirm New Password must be same", {
          position: "top-right",
          duration: 5000,
        });
      } else {
        this.isLoading = true;
        const doForgotPassword = await this.callApi(
          "post",
          this.apiPath + "reset-password",
          {
            password: this.form.password,
            token: this.$route.params.token,
          }
        );

        if (doForgotPassword.status == 200) {
          this.isLoading = false;
          if (doForgotPassword.data.code == 1) {
            this.form.password = "";
            this.form.cpassword = "";
            Vue.$toast.success(doForgotPassword.data.data, {
              position: "top-right",
              duration: 5000,
            });
            this.$router.push({ name: "login" });
          } else {
            Vue.$toast.error(doForgotPassword.data.data, {
              position: "top-right",
              duration: 5000,
            });
          }
        } else {
          this.isLoading = false;
          Vue.$toast.error("Password has not been reset, please try again", {
            position: "top-right",
            duration: 5000,
          });
        }
      }
    },
  },
};
</script>
