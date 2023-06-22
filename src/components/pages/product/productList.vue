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
                <h6 class="mb-0">Products</h6>

                <div
                  class="d-flex justify-content-between align-items-center gap-2"
                >
                  <router-link
                    class="btn btn-primary btn-sm"
                    :to="{ name: 'productadd' }"
                    >Add Product</router-link
                  >
                  <router-link
                    class="btn btn-primary btn-sm"
                    :to="{ name: 'productimport' }"
                    >Import Product</router-link
                  >
                </div>
              </div>
            </template>
            <b-table
              striped
              :items="products"
              :fields="prodcutfields"
              :sort-desc.sync="sortDesc"
              v-if="products.length > 0"
            >
              <template #cell(image)="row">
                <img class="primage" :src="row.value" v-if="row.value" />
              </template>
              <template #cell(price)="row">
                {{ currencySign }}{{ row.value }}
              </template>
              <template #cell(status)="row">
                <b-button size="sm" v-if="row.value == 1" variant="success"
                  >Published</b-button
                >
                <b-button size="sm" v-if="row.value == 0" variant="danger"
                  >Unpublished</b-button
                >
              </template>

              <template #cell(pid)="row">
                <div class="d-flex gap-2">
                  <router-link
                    class="btn btn-primary btn-sm"
                    :to="{ name: 'productedit', params: { id: row.value } }"
                    ><b-icon icon="pencil-square" aria-hidden="true"></b-icon
                  ></router-link>

                  <b-button
                    size="sm"
                    variant="danger"
                    @click="doDeleteConfirm(row.value)"
                    ><b-icon icon="trash-fill" aria-hidden="true"></b-icon
                  ></b-button>
                </div>
              </template>
            </b-table>

            <div v-else class="alert alert-info">No Products found.</div>
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
  name: "productListVue",
  components: {
    Header,
  },
  data() {
    return {
      sortDesc: false,
      prodcutfields: [
        { key: "sr_no", label: "Sr No.", sortable: true },
        { key: "image", label: "Image", sortable: false },
        { key: "name", label: "Name", sortable: true },
        { key: "price", label: "Price", sortable: true },
        { key: "upc", label: "UPC", sortable: true },
        { key: "status", label: "Status", sortable: true },
        { key: "created_at", label: "Date", sortable: true },
        { key: "pid", label: "Options", sortable: false },
      ],
      products: [],
    };
  },
  created() {
    this.getProducts();
  },
  methods: {
    async getProducts() {
      const getProducts = await this.callApi("post", this.apiPath + "products");

      if (getProducts.status == 200) {
        if (getProducts.data.code == 1) {
          this.products = getProducts.data.data;
        } else {
          this.products = [];
        }
      } else {
        Vue.$toast.error("No Products found", {
          position: "top-right",
          duration: 5000,
        });
      }
    },
    async doDeleteConfirm(id) {
      this.$bvModal
        .msgBoxConfirm("Are you sure to delete this Product", {
          title: "",
          size: "sm",
          buttonSize: "sm",
          okVariant: "danger",
          okTitle: "YES",
          cancelTitle: "NO",
          footerClass: "p-2",
          hideHeaderClose: false,
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.doDelete(id);
          }
        })
        .catch(() => {
          // An error occurred
        });
    },
    async doDelete(id) {
      const doDelete = await this.callApi(
        "post",
        this.apiPath + "products/" + id + "/destroy",
        {
          id: id,
        }
      );

      if (doDelete.status == 200) {
        if (doDelete.data.code == 1) {
          Vue.$toast.success(doDelete.data.data, {
            position: "top-right",
            duration: 5000,
          });
          this.getProducts();
        } else {
          Vue.$toast.error(doDelete.data.data, {
            position: "top-right",
            duration: 5000,
          });
        }
      } else {
        Vue.$toast.error("Product not deleted, please try again", {
          position: "top-right",
          duration: 5000,
        });
      }
    },
  },
};
</script>
