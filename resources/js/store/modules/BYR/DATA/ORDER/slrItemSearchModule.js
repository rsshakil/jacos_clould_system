const state = {
    form: {
        mes_lis_shi_lin_ite_gtin: null,
        mes_lis_shi_lin_ite_order_item_code: null,
        per_page: 10,
        data_order_id: null,
        page: 1,
        sort_by: 'mes_lis_shi_lin_ite_order_item_code ',
        sort_type: "ASC",
        order_info: [],
    },

};
const getters = {
    getFormData(state) {
        return state.form
    },
};
const actions = {};
const mutations = {
    // formValuesStore(state, payload) {
    //     state.form = payload;
    // },
    reset: () => {},
    updateFieldValue(state, { target, value, data_for }) {
        // console.log(data_for)
        // console.log(target)
        // console.log(value)
        if (data_for == 'form') {
            state.form[target] = value;
        } else {
            state[target] = value;
        }
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}