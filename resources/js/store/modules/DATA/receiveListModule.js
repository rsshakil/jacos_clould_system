const state = {
    form: {
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        receive_date_from: null,
        receive_date_to: null,
        sel_code: null,
        ownership_date_from: null,
        ownership_date_to: null,
        trade_number: null,
        // major_category: "*",
        delivery_service_code: "*",
        temperature_code: "*",
        sta_doc_type: "*",
        check_datetime: '*',
        major_category: { category_code: '*', category_name: '全て' },
        sort_by: 'receive_datetime ',
        sort_type: "DESC",
        page_title: 'receive_list',
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
    updateFormValue(state, { target, value }) {
        // console.log(target)
        // console.log(value)
        state.form[target] = value;
    },
    updateFieldValue(state, { target, value }) {
        // console.log(target)
        // console.log(value)
        state[target] = value;
    },
    formValuesStore(state, payload) {
        state.form = payload;
    },
    formValuesStoreBYRID(state, byr_buyer_id) {
        state.form.byr_buyer_id = byr_buyer_id;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}