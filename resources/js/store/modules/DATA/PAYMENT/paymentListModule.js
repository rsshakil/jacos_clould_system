const state = {
    form: {
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        mes_lis_pay_pay_code: null,
        receive_date_from: null,
        receive_date_to: null,
        mes_lis_buy_name: null,
        mes_lis_pay_per_end_date_from: null,
        mes_lis_pay_per_end_date_to: null,
        mes_lis_pay_lin_det_pay_out_date_from: null,
        mes_lis_pay_lin_det_pay_out_date_to: null,
        trade_number: null,
        check_datetime: '*',
        page_title: "payment_list",
        sort_by: 'receive_datetime ',
        sort_type: "DESC",
        downloadType: 1,
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
    updateFieldValue(state, { target, value, data_for }) {
        if (data_for == 'form') {
            state.form[target] = value;
        } else {
            state[target] = value;
        }
    },
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