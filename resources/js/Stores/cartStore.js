import {reactive} from "vue";

export let cart = reactive(JSON.parse(getCookie('cart') ?? '{}'))

export function getCart() {
    updateCartFromCookie();

    return cart;
}

export function updateCartFromCookie() {
    cart = reactive(JSON.parse(getCookie('cart') ?? '{}'))
}

export function addToCart(productId, quantity = 1) {
    cart[productId] ??= 0;
    cart[productId] += quantity;
    saveCartToCookie();
}

export function removeItemFromCart(productId) {
    delete cart[productId];
    saveCartToCookie();
}

export function clearCart() {
    for (let productId of Object.keys(cart)) {
        delete cart[productId];
    }

    saveCartToCookie();
}

export function incrementProductQuantity(productId) {
    cart[productId]++;
    saveCartToCookie();
}

export function decrementProductQuantity(productId) {
    cart[productId]--;
    saveCartToCookie();
}

function saveCartToCookie() {
    setCookie('cart', JSON.stringify(cart), 7);
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

export function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);

    if (parts.length === 2) return parts.pop().split(';').shift();
}
