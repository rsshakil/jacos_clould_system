const state = {
    form: {
        data_invoice_id: null,
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        data_count: false,
        send_data: false,
        param_data: [],
        from_date: '',
        to_date: '',
        mes_lis_inv_lin_tra_code: '',
        mes_lis_inv_lin_lin_trade_number_reference: '',
        decision_datetime_status: '*',
        category_code: { category_code: '*', category_name: '全て' },
        send_datetime_status: '*',
        payment_datetime_status: '*',
        sort_by: 'data_invoice_pay_detail_id ',
        sort_type: "ASC",
        page_title: 'invoice_details_list',
        shipment_ids: []
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