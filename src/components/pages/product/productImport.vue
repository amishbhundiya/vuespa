<template>
  <div>
    <!-- Header -->
    <Header />
    <b-container>
      <b-row align-h="center" class="mt-5">
        <b-col md="6">
          <b-card header-tag="header">
            <template #header>
              <div
                class="d-flex justify-content-between align-items-center gap-2"
              >
                <h6 class="mb-0">Product Import</h6>
                <div
                  class="d-flex justify-content-between align-items-center gap-2"
                >
                  <a
                    :href="productSampleCSV"
                    class="btn btn-primary btn-sm"
                    target="_blank"
                    download
                    >Download Sample CSV</a
                  >
                  <router-link
                    class="btn btn-primary btn-sm"
                    :to="{ name: 'productlist' }"
                    >List Product</router-link
                  >
                </div>
              </div>
            </template>

            <b-form-group
              id="input-group-1"
              label="Select File"
              label-for="input-1"
              class="mb-4"
            >
              <input
                type="file"
                id="file"
                ref="file"
                accept=".xlsx, .csv"
                v-on:change="handleFileUpload()"
              />
            </b-form-group>

            <div class="d-flex align-items-center justify-content-between">
              <b-button
                type="button"
                @click="doImport"
                :disabled="isLoading"
                variant="primary"
                >Submit</b-button
              >
            </div>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Header from "../../common/header.vue";
import Vue from "vue";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
Vue.use(VueToast);

export default {
  name: "productImportVue",
  components: {
    Header,
  },
  data() {
    return {
      file: "",
      isLoading: false,
    };
  },
  methods: {
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    async doImport() {
      if (this.file == "") {
        Vue.$toast.error("Please file to import", {
          position: "top-right",
          duration: 5000,
        });
        return false;
      }

      let formData = new FormData();
      formData.append("file", this.file);

      const doImport = await this.callApiFormData(
        "post",
        this.apiPath + "products/import",
        formData
      );

      if (doImport.status == 200) {
        this.isLoading = false;
        if (doImport.data.code == 1) {
          Vue.$toast.success(doImport.data.data, {
            position: "top-right",
            duration: 5000,
          });
          this.$router.push({ name: "productlist" });
        } else {
          Vue.$toast.error(doImport.data.data, {
            position: "top-right",
            duration: 5000,
          });
        }
      } else {
        this.isLoading = false;

        Vue.$toast.error("Product not import, please try again", {
          position: "top-right",
          duration: 5000,
        });
      }

      // axios
      //   .post(url, formData, {
      //     headers: {
      //       "Content-Type": "multipart/form-data",
      //       Authorization: `Bearer ${token}`,
      //     },
      //   })
      //   .then(function (res) {
      //     thisis.isLoading = false;

      //     if (res.data.code == 1) {
      //       Vue.$toast.success(res.data.data, {
      //         position: "top-right",
      //         duration: 5000,
      //       });
      //       thisis.$router.push({ name: "productlist" });
      //     } else {
      //       Vue.$toast.error(res.data.data, {
      //         position: "top-right",
      //         duration: 5000,
      //       });
      //     }
      //   })
      //   .catch(function () {
      //     thisis.isLoading = false;

      //     Vue.$toast.error("Product not import, please try again", {
      //       position: "top-right",
      //       duration: 5000,
      //     });
      //   });
    },
  },
};
</script>
