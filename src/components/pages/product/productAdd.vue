<template>
  <div>
    <!-- Header -->
    <Header />
    <b-container>
      <b-row align-h="center" class="mt-5">
        <b-col md="12">
          <b-card header-tag="header">
            <template #header>
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Add Product</h6>

                <div
                  class="d-flex justify-content-between align-items-center gap-2"
                >
                  <router-link
                    class="btn btn-primary btn-sm"
                    :to="{ name: 'productlist' }"
                    >List Product</router-link
                  >
                </div>
              </div>
            </template>

            <b-row align-h="center">
              <b-col md="6">
                <b-form-group
                  id="input-group-1"
                  label="Name*"
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
                  label="UPC*"
                  label-for="input-2"
                  class="mb-4"
                >
                  <b-form-input
                    id="input-2"
                    v-model="form.upc"
                    type="text"
                    required
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  id="input-group-3"
                  label="Status*"
                  label-for="input-3"
                  class="mb-4"
                  v-slot="{ ariaDescribedby }"
                >
                  <b-form-radio-group
                    :aria-describedby="ariaDescribedby"
                    v-model="form.status"
                    :options="form.status_options"
                    class="mb-3"
                    value-field="item"
                    text-field="name"
                  ></b-form-radio-group>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group
                  id="input-group-4"
                  label="Price*"
                  label-for="input-4"
                  class="mb-4"
                >
                  <b-form-input
                    id="input-4"
                    v-model="form.price"
                    type="text"
                    required
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  id="input-group-5"
                  label="Select File"
                  label-for="input-5"
                  class="mb-4"
                >
                  <input
                    type="file"
                    id="file"
                    ref="file"
                    accept=".jpg, .png"
                    v-on:change="handleFileUpload()"
                  />
                </b-form-group>
              </b-col>

              <div class="d-flex align-items-center justify-content-between">
                <b-button
                  @click="addProduct"
                  :disabled="isLoading"
                  type="button"
                  variant="primary"
                  >Submit</b-button
                >
              </div>
            </b-row>
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
  name: "productAddVue",
  components: {
    Header,
  },
  data() {
    return {
      isLoading: false,
      file: "",
      form: {
        name: "",
        price: "",
        upc: "",
        status: "1",
        status_options: [
          { item: "1", name: "Published" },
          { item: "0", name: "Unpublished" },
        ],
      },
    };
  },
  methods: {
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    async addProduct() {
      if (this.form.name == "") {
        Vue.$toast.error("Please Enter Name", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.price == "") {
        Vue.$toast.error("Please Enter Price", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.upc == "") {
        Vue.$toast.error("Please Enter UPC", {
          position: "top-right",
          duration: 5000,
        });
      } else if (this.form.status == "") {
        Vue.$toast.error("Please Select Status", {
          position: "top-right",
          duration: 5000,
        });
      } else {
        this.isLoading = true;

        let formData = new FormData();
        formData.append("name", this.form.name);
        formData.append("price", this.form.price);
        formData.append("upc", this.form.upc);
        formData.append("status", this.form.status);
        formData.append("image", this.file);

        const addProduct = await this.callApiFormData(
          "post",
          this.apiPath + "products/store",
          formData
        );

        if (addProduct.status == 200) {
          this.isLoading = false;
          if (addProduct.data.code == 1) {
            Vue.$toast.success(addProduct.data.data, {
              position: "top-right",
              duration: 5000,
            });

            this.form.name = "";
            this.form.price = "";
            this.form.upc = "";
            this.form.status = "1";

            this.$router.push({ name: "productlist" });
          } else {
            Vue.$toast.error(addProduct.data.data, {
              position: "top-right",
              duration: 5000,
            });
          }
        } else {
          this.isLoading = false;
          Vue.$toast.error("Product details not saved, please try again", {
            position: "top-right",
            duration: 5000,
          });
        }
      }
    },
  },
};
</script>
