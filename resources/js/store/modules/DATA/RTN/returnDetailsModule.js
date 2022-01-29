const state = {
    form: {
        page: 1,
        select_field_page_num: 1,
        select_field_per_page_num: 10,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        data_return_id: null,
        sel_name: null,
        sel_code: null,
        major_category: null,
        ownership_date: null,
        // Search
        searchCode1: '',
        searchCode2: '',
        searchCode3: '',
        decesion_status: "*",
        voucher_class: "*",
        goods_classification_code: "*",
        trade_number: null,
        mes_lis_acc_par_shi_code: '',
        mes_lis_acc_par_rec_code: '',
        order_info: {},
        sort_by: 'data_return_voucher_id ',
        sort_type: "ASC",
        page_title: 'return_details_list',
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