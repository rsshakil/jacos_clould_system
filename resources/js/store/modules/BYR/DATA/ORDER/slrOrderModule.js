const state = {
    form: {
        data_order_id: null,
        byr_buyer_id: null,
        per_page: 10,
        page: 1,
        receive_date_from: null,
        receive_date_to: null,
        category_code: { category_code: '*', category_name: '全て' },
        delivery_date_from: null,
        delivery_date_to: null,
        check_datetime: '*',
        trade_number: null,
        delivery_service_code: "*",
        temperature: "*",
        mes_lis_ord_par_sel_code: "",
        send_cnt: "*",
        decission_cnt: "*",
        confirmation_status_data: "*",
        decisionDateTime: '*',
        sort_by: 'receive_datetime ',
        sort_type: "DESC",
        downloadType: 1,
        page_title: 'slr_order_list',
        trash_status: '*'
    },
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