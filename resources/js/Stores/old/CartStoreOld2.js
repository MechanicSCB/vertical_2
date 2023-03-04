import {defineStore} from "pinia";

export let useCartStore = defineStore('cart', {
    // data
    state() {
        return {
            products: getCookieProducts(),
            productsDataFromDb: {},
        }
    },

    // methods
    actions: {
        getProducts() {
            return JSON.parse(getCookie('cart') ?? '{}');
        },

        async getProductsDataFromDb() {
            this.productsDataFromDb = (await axios.post(route('products.data'), {ids: Object.keys(getCookieProducts())})).data;
        },

        // getProductsDataFromDb() {axios.post('/products_get', {count: 2}).then(response => this.productsData = response.data).catch(error => console.log(error))},
        // getProductsDataFromDbFetch() {fetch('/products_get', {method: "post", headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},/*make sure to serialize your JSON body*/ body: JSON.stringify({count: 4}),}).then((response) => response.json()).then((json) => this.productsData = json);},

        add(product){
            this.products[product.id] = {
                'id':product.id,
                'code':product.code, 'name':product.name, 'price':product.price,
                'quantity':(this.products[product.id]?.quantity ?? 0) + 1,
            };
            this.setProductsToCookie();
        },

        clear(){
            this.products = {};
            this.setProductsToCookie();
        },

        setProductsToCookie(){
            return setCookie('cart', JSON.stringify(this.products), 7);
        },

        getProductsFromCookie(){
            return this.products = JSON.parse(getCookie('cart') ?? '{}');
        },

        increment(productId) {
            this.products[productId]++;
        },
    },

    // computed
    getters: {
        productsIds() {
            return Object.keys(getCookieProducts());
        },
        codesCount() {
            return Object.keys(this.products).length;
        },
        itemsCount() {
            let cnt = 0;

            for (let n of Object.values(this.products)){
                cnt += n;
            }

            return cnt;
        },
    },
});

function getCookieProducts() {
    let products = {};
    let cookieData = JSON.parse(getCookie('cart') ?? '{}');


    for(let productId of Object.keys(cookieData)){
        products[productId] = cookieData[productId];
    }

    return products;
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);

    if (parts.length === 2) return parts.pop().split(';').shift();
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/;SameSite=Lax";
}

