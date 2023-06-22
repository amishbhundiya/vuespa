import axios from "axios";

export default {
  data() {
    return {
      apiPath:
        process.env.NODE_ENV == "development"
          ? "http://localhost/vuejs/vuespa/vuespa-api/public/api/"
          : "http://localhost/vuejs/vuespa/vuespa-api/public/api/",
      productSampleCSV: 'http://localhost/vuejs/vuespa/vuespa-api/public/uploads/product/Sample-Product-Import-File.csv',
      currencySign: '$'
    };
  },
  methods: {
    callApi(method, url, dataobj = "", token = "") {
      var thisis = this;
      try {
        const userData = localStorage.getItem("userData");
        if (userData) {
          token = userData;
        }

        return axios({
          method: method,
          url: url,
          data: dataobj,
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }).catch(function (error) {
          if (error.response.status == 401) {
            localStorage.removeItem("userData");
            thisis.$router.push({ name: "login" });
          }
          return error.response;
        });
      } catch (e) {
        return e.response;
      }
    },
    callApiFormData(method, url, formData = "", token = "") {
      var thisis = thisis;
      const userData = localStorage.getItem("userData");
      if (userData) {
        token = userData;
      }

      return axios
        .post(url, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
          },
        })
        .then(function (res) {
          return res;
        })
        .catch(function (err) {
          if (err.response.status == 401) {
            localStorage.removeItem("userData");
            thisis.$router.push({ name: "login" });
          }
          return err;
        });
    },
  }
};
