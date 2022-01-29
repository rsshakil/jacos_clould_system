const state = {
    form: {
        per_page: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        receive_date_from: null,
        receive_date_to: null,
        sel_code: '',
        ownership_date_from: null,
        ownership_date_to: null,
        trade_number: null,
        delivery_service_code: "*",
        temperature_code: "*",
        sta_doc_type: "*",
        check_datetime: '*',
        major_category: { category_code: '*', category_name: '全て' },
        sort_by: 'receive_datetime ',
        sort_type: "DESC",
        page_title: 'return_list',
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