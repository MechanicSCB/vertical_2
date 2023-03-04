import {defineStore} from "pinia";

export let useCartStore = defineStore('cart', {
    // data
    state() {
        return {
            cookieItems: {},
            productsData: {},
            products: {},
        }
    },

    // methods
    actions: {
        getCookieItems() {
            return this.cookieItems = JSON.parse(getCookie('cart') ?? '{}');
        },

        async getProducts() {
            this.getCookieItems();
            this.productsData = (await axios.post(route('products.data'), {ids: Object.keys(this.cookieItems)})).data;
            this.products = {};

            for (let productId of Object.keys(this.cookieItems)){
                let item = this.cookieItems[productId];
                let product = this.productsData[productId];

                this.products[productId] = {
                    'id':productId,
                    'quantity':item.quantity,
                    'name':product.name,
                    'slug':product.slug,
                    'code':product.code,
                    'price':product.price,
                };
            }
        },

        add(product, addQuantity = 1){
            this.cookieItems[product.id] = {
                'id':product.id,
                // 'code':product.code, 'name':product.name, 'price':product.price,
                'quantity':(this.cookieItems[product.id]?.quantity ?? 0) + addQuantity,
            };
            this.setItemsToCookie();
            this.getProducts().then();
        },

        clear(){
            this.cookieItems = {};
            this.setItemsToCookie();
            this.getProducts().then();
        },

        removePosition(productId){
            delete this.cookieItems[productId];
            this.setItemsToCookie();
            this.getProducts().then();
        },

        increment(productId) {
            if(typeof this.cookieItems[productId]?.quantity !== "undefined"){
                this.cookieItems[productId].quantity +=1;
                this.setItemsToCookie();
                this.getProducts().then();
            }else {
                console.log('no item:' + productId)
            }
        },

        decrement(productId) {
            if(typeof this.cookieItems[productId]?.quantity === "undefined"){
                console.log('no item:' + productId)
                return;
            }

            if(this.cookieItems[productId]?.quantity <= 1){
                return;
            }

            this.cookieItems[productId].quantity--;
            this.setItemsToCookie();
            this.getProducts().then();
        },

        setItemsToCookie(){
            setCookie('cart', JSON.stringify(this.cookieItems), 7);
        },
    },

    // computed
    getters: {
        positionsCount() {
            this.getCookieItems();
            return Object.keys(this.cookieItems).length;
        },
        itemsCount() {
            this.getCookieItems();

            let cnt = 0;

            for (let id of Object.keys(this.cookieItems)){
                cnt += this.cookieItems[id].quantity;
            }

            return cnt;
        },
        orderSum() {
            let orderSum = 0;

            for (let productId of Object.keys(this.products)){
                let product = this.products[productId];
                orderSum += product.price * product.quantity;
            }

            return orderSum;
        },
        discount() {
            return 0.95;
        },
    },
});

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

