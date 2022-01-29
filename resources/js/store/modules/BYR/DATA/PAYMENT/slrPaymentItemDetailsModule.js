const state = {
    form: {
        per_page: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        mes_lis_pay_lin_tra_code: null,
        from_date: null,
        to_date: null,
        category_code: { category_code: '*', category_name: '全て' },
        mes_lis_pay_lin_sel_code: null,
        mes_lis_inv_lin_det_pay_code: '*',
        mes_lis_pay_lin_det_verification_result_code: '*',
        mes_lis_pay_lin_det_trade_type_code: '*',
        mes_lis_pay_lin_det_balance_carried_code: '*',
        mes_lis_pay_lin_lin_trade_number_reference: null,
        check_datetime: null,
        pay_code: null,
        end_date: null,
        out_date: null,
        submit_type: "page_load",
        payment_id: '',
        sort_by: 'data_payment_pay_detail_id ',
        sort_type: "ASC",
        pay_code_list: [1001, 1002, 1004]
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