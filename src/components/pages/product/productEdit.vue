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
                <h6 class="mb-0">Edit Product</h6>

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
                  @click="updateProduct"
                  :disabled="isLoading"
                  type="button"
                  variant="primary"
                  >Update</b-button
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
  name: "productEditVue",
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
  created: function () {
    this.getProductDetail(this.$route.params.id);
  },
  methods: {
    async getProductDetail(id) {
      this.isLoading = true;

      const getProduct = await this.callApi(
        "post",
        this.apiPath + "products/" + id + "/edit",
        {
          id: id,
        }
      );

      if (getProduct.status == 200) {
        this.isLoading = false;
        if (getProduct.data.code == 1) {
          //
          this.form.name = getProduct.data.data.name;
          this.form.price = getProduct.data.data.price;
          this.form.upc = getProduct.data.data.upc;
          this.form.status = getProduct.data.data.status;
        } else {
          Vue.$toast.error("No Details found", {
            position: "top-right",
            duration: 5000,
          });
          this.$router.push({ name: "productlist" });
        }
      } else {
        this.isLoading = false;
        Vue.$toast.error("No Details found", {
          position: "top-right",
          duration: 5000,
        });
        this.$router.push({ name: "productlist" });
      }
    },
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    async updateProduct() {
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
      } else if (this.form.status == null) {
        Vue.$toast.error("Please Select Status", {
          position: "top-right",
          duration: 5000,
        });
      } else {
        this.isLoading = true;
        var id = this.$route.params.id;

        let formData = new FormData();
        formData.append("name", this.form.name);
        formData.append("price", this.form.price);
        formData.append("upc", this.form.upc);
        formData.append("status", this.form.status);
        formData.append("image", this.file);

        const updateProduct = await this.callApiFormData(
          "post",
          this.apiPath + "products/" + id + "/update",
          formData
        );

        if (updateProduct.status == 200) {
          this.isLoading = false;
          if (updateProduct.data.code == 1) {
            Vue.$toast.success(updateProduct.data.data, {
              position: "top-right",
              duration: 5000,
            });

            this.form.name = "";
            this.form.price = "";
            this.form.upc = "";
            this.form.status = "1";

            this.$router.push({ name: "productlist" });
          } else {
            Vue.$toast.error(updateProduct.data.data, {
              position: "top-right",
              duration: 5000,
            });
          }
        } else {
          this.isLoading = false;
          Vue.$toast.error("Product details not updated, please try again", {
            position: "top-right",
            duration: 5000,
          });
        }
      }
    },
  },
};
</script>
