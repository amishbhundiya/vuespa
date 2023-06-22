<template>
  <div>
    <b-container>
      <b-row align-h="center" align-v="center" class="h-100vh">
        <b-col md="6">
          <b-card header-tag="header">
            <template #header>
              <h6 class="mb-0">Signup</h6>
            </template>

            <b-form-group
              id="input-group-1"
              label="Name"
              label-for="input-1"
              class="mb-4"
            >
              <b-form-input
                id="input-1"
                v-model="form.name"
                type="text"
                required
              ></b-form-input>
            </b-form-group>

            <b-form-group
              id="input-group-2"
              label="Email address"
              label-for="input-2"
              class="mb-4"
            >
              <b-form-input
                id="input-2"
                v-model="form.email"
                type="email"
                required
              ></b-form-input>
            </b-form-group>

            <b-form-group
              id="input-group-3"
              label="Password"
              label-for="input-3"
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
                @click="doSignup"
                :disabled="isLoading"
                type="button"
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
  name: "signupVue",
  data() {
    return {
      isLoading: false,
      form: {
        name: "",
        email: "",
        password: "",
      },
    };
  },
  methods: {
    async doSignup() {
      if (this.form.name == "") {
        Vue.$toast.error("Please Enter Name", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.email == "") {
        Vue.$toast.error("Please Enter Email address", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.password == "") {
        Vue.$toast.error("Please Enter Password", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.password.length < 6) {
        Vue.$toast.error("Password must be 6 character long", {
          position: "top-right",
          duration: 5000,
        });
      } else {
        this.isLoading = true;
        const doSignup = await this.callApi("post", this.apiPath + "signup", {
          name: this.form.name,
          email: this.form.email,
          password: this.form.password,
        });

        if (doSignup.status == 200) {
          this.isLoading = false;
          if (doSignup.data.code == 1) {
            Vue.$toast.success(doSignup.data.data, {
              position: "top-right",
              duration: 5000,
            });

            this.form.name = "";
            this.form.email = "";
            this.form.password = "";

            this.$router.push({ name: "login" });
          } else {
            Vue.$toast.error(doSignup.data.data, {
              position: "top-right",
              duration: 5000,
            });
          }
        } else {
          Vue.$toast.error("Signup not done, please try again", {
            position: "top-right",
            duration: 5000,
          });
        }
      }
    },
  },
};
</script>
