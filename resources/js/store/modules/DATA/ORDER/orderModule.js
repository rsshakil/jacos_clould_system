const state = {
    form: {
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        data_order_id: null,
        per_page: 10,
        page: 1,
        downloadType: 1,
        shipment_download_type: "order_pdf",
        send_cnt: "*",
        decission_cnt: "*",
        check_datetime: '*',
        trade_number: null,
        temperature: "*",
        category_code: { category_code: '*', category_name: '全て' },
        delivery_date_from: null,
        delivery_date_to: null,
        delivery_service_code: "*",
        mes_lis_ord_par_sel_code: "",
        receive_date_from: null,
        receive_date_to: null,
        sort_by: 'receive_datetime ',
        sort_type: "DESC",
        page_title: 'order_list',
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