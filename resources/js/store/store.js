import Vue from "vue";
import Vuex from "vuex";
import VuexReset from '@ianwalter/vuex-reset'

import orderModule from "./modules/DATA/ORDER/orderModule"
import orderDetailsModule from "./modules/DATA/ORDER/orderDetailsModule"
import itemSearchModule from "./modules/DATA/ORDER/itemSearchModule"
import defaultModule from "./modules/DEFAULT/defaultModule"
import receiveListModule from "./modules/DATA/receiveListModule"
import receiveDetailListModule from "./modules/DATA/receiveDetailListModule"
import returnListModule from "./modules/DATA/RTN/returnListModule"
import returnDetailsModule from "./modules/DATA/RTN/returnDetailsModule"

import invoiceListModule from "./modules/DATA/INVOICE/invoiceListModule"

import invoiceDetailsModule from "./modules/DATA/INVOICE/invoiceDetailsModule"
import paymentListModule from "./modules/DATA/PAYMENT/paymentListModule"
import paymentItemDetailsModule from "./modules/DATA/PAYMENT/paymentItemDetailsModule"
import stockItemModule from "./modules/DATA/STOCK/stockItemModule"

// BYR Menu
import slrOrderModule from "./modules/BYR/DATA/ORDER/slrOrderModule"
import slrOrderDetailsModule from "./modules/BYR/DATA/ORDER/slrOrderDetailsModule"
import slrItemSearchModule from "./modules/BYR/DATA/ORDER/slrItemSearchModule"
import slrReceiveListModule from "./modules/BYR/DATA/RECEIVE/slrReceiveListModule"
import slrReceiveDetailListModule from "./modules/BYR/DATA/RECEIVE/slrReceiveDetailListModule"
import slrReturnListModule from "./modules/BYR/DATA/RTN/slrReturnListModule"
import slrReturnDetailsModule from "./modules/BYR/DATA/RTN/slrReturnDetailsModule"
import slrInvoiceListModule from "./modules/BYR/DATA/INVOICE/slrInvoiceListModule"
import slrInvoiceDetailsModule from "./modules/BYR/DATA/INVOICE/slrInvoiceDetailsModule"
import slrPaymentListModule from "./modules/BYR/DATA/PAYMENT/slrPaymentListModule"
import slrPaymentItemDetailsModule from "./modules/BYR/DATA/PAYMENT/slrPaymentItemDetailsModule"
import slrStockItemModule from "./modules/BYR/DATA/STOCK/slrStockItemModule"

Vue.use(Vuex);

const store = new Vuex.Store({
    plugins: [VuexReset()],
    modules: {
        orderModule,
        orderDetailsModule,
        itemSearchModule,
        receiveListModule,
        receiveDetailListModule,
        returnListModule,
        returnDetailsModule,
        invoiceListModule,
        invoiceDetailsModule,
        paymentListModule,
        paymentItemDetailsModule,
        stockItemModule,
        // BYR Menu
        slrOrderModule,
        slrOrderDetailsModule,
        slrItemSearchModule,
        slrReceiveListModule,
        slrReceiveDetailListModule,
        slrReturnListModule,
        slrReturnDetailsModule,
        slrInvoiceListModule,
        slrInvoiceDetailsModule,
        slrPaymentListModule,
        slrPaymentItemDetailsModule,
        slrStockItemModule,
        defaultModule,
    },
    mutations: {
        reset: () => {},
    }
})
export default store;