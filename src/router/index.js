import Vue from "vue";
import Router from "vue-router";

const login = () =>
    import("@/components/pages/login");
const signup = () =>
    import("@/components/pages/signup");
const forgotpassword = () =>
    import("@/components/pages/forgotPassword");
const resetpassword = () =>
    import("@/components/pages/resetPassword");
const productList = () =>
    import("@/components/pages/product/productList");
const productAdd = () =>
    import("@/components/pages/product/productAdd");
const productEdit = () =>
    import("@/components/pages/product/productEdit");
const productImport = () =>
    import("@/components/pages/product/productImport");

Vue.use(Router);

const guest = (to, from, next) => {
    if (!localStorage.getItem("userData")) {
        return next();
    } else {
        return next("/products");
    }
};
const auth = (to, from, next) => {
    if (localStorage.getItem("userData")) {
        return next();
    } else {
        return next("/");
    }
};

const siterouter = new Router({
    routes: [
        {
            path: "/",
            name: "login",
            component: login,
            beforeEnter: guest,
        },
        {
            path: "/signup",
            name: "signup",
            component: signup,
            beforeEnter: guest,
        },
        {
            path: "/forgot-password",
            name: "forgotpassword",
            component: forgotpassword,
            beforeEnter: guest,
        },
        {
            path: "/reset-password/:token",
            name: "resetpassword",
            component: resetpassword
        },
        {
            path: "/products",
            name: "productlist",
            component: productList,
            beforeEnter: auth,
        },
        {
            path: "/products/create",
            name: "productadd",
            component: productAdd,
            beforeEnter: auth,
        },
        {
            path: "/products/:id/edit",
            name: "productedit",
            component: productEdit,
            beforeEnter: auth,
        },
        {
            path: "/products/import",
            name: "productimport",
            component: productImport,
            beforeEnter: auth,
        },
    ],
    scrollBehavior() {
        return { x: 0, y: 0 };
    },
    mode: "history"
});

// siterouter.beforeEach(function (to, from, next) {    
//     next();
// });

// siterouter.afterEach((to, from, failure) => {

// });

export default siterouter;