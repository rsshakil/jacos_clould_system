const state = {
    form: {
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        mes_lis_inv_pay_code: '',
        send_cnt: '*',
        decission_cnt: '*',
        trade_number: null,
        mes_lis_inv_per_begin_date: null,
        mes_lis_inv_per_end_date: null,
        send_datetime_status: "*",
        sort_by: 'mes_lis_inv_per_end_date ',
        sort_type: "DESC",
        page_title: 'invoice_list',
        downloadType: 1
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
    formValuesStore(state, payload) {
        state.form = payload;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}