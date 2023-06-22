<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="dark">
      <b-container>
        <b-navbar-brand href="javascript:;">VueSPA</b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" class="justify-content-end" is-nav>
          <b-navbar-nav>
            <li class="nav-item">
              <router-link class="nav-link" :to="{ name: 'productlist' }"
                >Products</router-link
              >
            </li>
            <li class="nav-item">
              <a href="javascript:;" @click="doLogout()" class="nav-link"
                >Logout</a
              >
            </li>
          </b-navbar-nav>
        </b-collapse>
      </b-container>
    </b-navbar>
  </div>
</template>

<script>
export default {
  name: "headerVue",
  methods: {
    async doLogout() {
      await this.callApi("post", this.apiPath + "logout");
      localStorage.removeItem("userData");
      this.$router.push({ name: "login" });
    },
  },
};
</script>
