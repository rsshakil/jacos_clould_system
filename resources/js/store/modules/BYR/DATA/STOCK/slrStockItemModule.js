const state = {
    form: {
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        data_order_id: null,
        date_to_receiver_from: null,
        date_to_receiver_to: new Date().toJSON().slice(0, 10),
        item_code: null,
        per_page: 10,
        page: 1,
        sort_by: 'mes_lis_shi_lin_ite_order_item_code ',
        sort_type: "ASC",
        page_title: 'stock_item_list',
    }
};
const getters = {
    getFormData(state) {
        return state.form
    },
};
const actions = {};
const mutations = {
    reset: () => {},
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}