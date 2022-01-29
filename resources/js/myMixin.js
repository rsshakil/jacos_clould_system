import moment from 'moment';
export default {
    data() {
        return {
            global_user_id: Globals.user_info_id,
            global_user_type: Globals.global_user_type,
            global_user_name: Globals.user_info_name,
            global_cmn_company_id: Globals.cmn_company_id,
            myLang: Globals,
            APP_ENV: Globals.APP_ENV,
            BASE_URL: Globals.base_url,
            alert_icon: "error",
            alert_title: "内部エラー",
            alert_text: "システム内でのエラーが発生しました。",
            alert_footer: "システム担当へご連絡ください。",
            global_image_path: null,
            table_col_setting_list: "",
            table_col_arry: [],
            selected_columns: [],
            col_lists: [],
            buyer_info_for_saller: [],
            mes_lis_shi_lin_qua_sto_reason_codeList: [],
            json_temperature_codeList: [],
            json_delivery_service_codeList: [],
            mes_lis_pay_lin_det_trade_type_codeList: [],
            mes_lis_ord_tra_ins_goods_classification_codeList: [],
            mes_lis_ord_tra_ins_trade_type_codeList: [],
            mes_lis_inv_lin_det_balance_carried_codeList: [],
            mes_lis_inv_lin_det_pay_code_list: [],
            byr_buyer_category_lists: [],
            filter_select_box: false,
            buyers: [],
            selected_buyer: [],
            sellers: [],
            selected_seller: [],
            yes_btn: 'Yes, delete it!',
            cancel_btn: "キャンセル",
            byr_buyer_id: null,

            buyer_settings: {},
            ordersJson: {},
            shipmentsJson: {},
            receivesJson: {},
            corrected_receivesJson: {},
            returnsJson: {},
            invoicesJson: {},
            paymentsJson: {},
            paramInfo: {
                component_name: '',
                byr_buyer_id: '',
                adm_user_id: Globals.user_info_id
            },

            // loader: "",
        };
    },
    // beforeCreate: function() {
    //     // console.log(this.$route);
    //     if (typeof(this.$route) !== 'undefined') {
    //         if (Permissions.indexOf(this.$route.name) === -1) {
    //             this.$router.push('/home');
    //         }
    //         // console.log(this.$route.name);
    //     }
    //     // if (!this.$session.exists()) {
    //     //     this.$router.push('/home');
    //     // }
    // },
    methods: {
        // Database created and updated datetime conversion
        getbuyerJsonSettingvalue() {
            this.paramInfo.component_name = this.$route.name;
            if (Globals.global_user_type == 'others') {
                this.paramInfo.byr_buyer_id = this.$session.get("slr_byr_buyer_id");
            }
            axios.post(this.BASE_URL + "api/buyerJsonSetting", this.paramInfo)
                .then(({ data }) => {
                    this.buyer_settings = data.buyer_settings;
                    this.mes_lis_ord_tra_ins_trade_type_codeList = data.buyer_settings.returns.mes_lis_ret_tra_ins_trade_type_code;
                    this.mes_lis_shi_lin_qua_sto_reason_codeList = this.buyer_settings.shipments.mes_lis_shi_lin_qua_sto_reason_code;
                    this.json_temperature_codeList = this.buyer_settings.orders.mes_lis_ord_tra_ins_temperature_code;
                    this.json_delivery_service_codeList = this.buyer_settings.orders.mes_lis_ord_log_del_delivery_service_code;
                    this.mes_lis_pay_lin_det_trade_type_codeList = this.buyer_settings.payments.mes_lis_pay_lin_det_trade_type_code;

                    this.mes_lis_ord_tra_ins_goods_classification_codeList = data.buyer_settings.orders.mes_lis_ord_tra_ins_goods_classification_code;

                    this.mes_lis_inv_lin_det_balance_carried_codeList = data.buyer_settings.invoices.mes_lis_inv_lin_det_balance_carried_code;
                    this.mes_lis_inv_lin_det_pay_code_list = data.buyer_settings.invoices.mes_lis_inv_lin_det_pay_code;

                    this.ordersJson = this.buyer_settings.orders;
                    this.shipmentsJson = this.buyer_settings.shipments;
                    this.receivesJson = this.buyer_settings.receives;
                    this.corrected_receivesJson = this.buyer_settings.corrected_receives;
                    this.returnsJson = this.buyer_settings.returns;

                    this.invoicesJson = this.buyer_settings.invoices;
                    this.paymentsJson = this.buyer_settings.payments;
                    this.byr_buyer_category_lists = data.buyer_category_list;
                    this.byr_buyer_category_lists.unshift({ category_code: '*', category_name: '全て' });
                    // console.log(this.byr_buyer_category_lists);
                });
        },
        getbyrjsonValueBykeyName(arrName, arrKey, orderType = "orders", buyer_settings = []) {
            if (buyer_settings.length > 0) {
                this.buyer_settings = buyer_settings;
            }
            if (arrKey != '') {
                var newarr = [];
                var buyer_settings_length_check = Object.keys(this.buyer_settings).length;
                if (buyer_settings_length_check > 0) {
                    var values = this.buyer_settings[orderType][arrName][arrKey];
                    return values;
                }
            } else {
                return '';
            }

        },
        formatDate(date_string) {
            var date = new Date(date_string)
            return date.getFullYear() + '-' +
                this.length_fill(date.getMonth() + 1) + '-' +
                this.length_fill(date.getDate()) + ' ' +
                this.length_fill(date.getHours()) + ':' +
                this.length_fill(date.getMinutes()) + ':' +
                this.length_fill(date.getSeconds());
        },

        length_fill(data_string) {
            var strlenth = data_string.toString().length;
            var str;
            if (strlenth < 2) {
                str = "0" + data_string;
            } else {
                str = data_string;
            }
            return str;
        },
        imageSrc(image_name) {
            if (image_name) {
                this.global_image_path =
                    this.BASE_URL +
                    "/storage/app/public/backend/images/users/" +
                    image_name;
            } else {
                this.global_image_path =
                    this.BASE_URL +
                    "/storage/app/public/backend/images/default/no-image.png";
            }
            return this.global_image_path;
        },
        logout() {
            this.loader = Vue.$loading.show()
            var _this = this;
            this.app.req.post(this.BASE_URL + "logout").then(({ data }) => {
                _this.$session.destroy()
                _this.$router.push({ name: 'home' })
                window.location.reload();
            }).catch(function(error) {
                if (error.response && error.response.status == 419) {
                    _this.$session.destroy()
                    _this.$router.push({ name: 'home' })
                    window.location.reload();
                }
            });
        },
        // display_table_col_setting() {
        //     // console.log(this.$route.name);
        //     if (this.$route.name == 'order_list_details') {
        //         this.$root.$emit(
        //             "bv::show::modal",
        //             "table_col_setting",
        //             "#table_colShowHide"
        //         );
        //         axios
        //             .get(this.BASE_URL + "api/tblecolsetting/" + this.$route.name)
        //             .then(data => {
        //                 // console.log(data);
        //                 this.table_col_setting_list = data.data.result;
        //                 this.table_col_arry = data.data.arrs;
        //                 this.selected_columns = data.data.selected_columns;
        //                 this.col_lists = data.data.col_lists;
        //             });
        //     } else {
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Table column setting',
        //             text: 'Table column setting not found for this page'
        //         });
        //     }
        // },

        // update_col_setting() {
        //     // console.log("update col setting");

        //     var post_data = {
        //         url_slug: this.$route.name,
        //         user_id: Globals.user_info_id,
        //         content_setting: this.selected_columns,
        //         setting_list: this.table_col_arry,
        //     };
        //     axios.put(
        //             this.BASE_URL + "api/tblecolsetting/" + this.$route.name,
        //             post_data
        //         )
        //         .then(data => {
        //             this.$root.$emit(
        //                 "bv::hide::modal",
        //                 "table_col_setting",
        //                 "#table_colShowHide"
        //             );
        //             Fire.$emit('LoadByrorderDetail');
        //             // window.location.reload();
        //         });
        // },
        // handleChange: function(col_setting) {
        //     if (col_setting.header_status === true) {
        //         this.selected_columns.push(col_setting.header_field);
        //     } else {
        //         for (var i = 0; i < this.selected_columns.length; i++) {
        //             if (this.selected_columns[i] == col_setting.header_field) {
        //                 this.selected_columns.splice(i, 1)
        //             }
        //         }
        //     }
        // },

        sweet_normal_alert() {
            Swal.fire({
                icon: this.alert_icon,
                title: this.alert_title,
                html: this.alert_text,
                confirmButtonText: "完了"
            });
        },
        sweet_advance_alert() {
            Swal.fire({
                icon: this.alert_icon,
                title: this.alert_title,
                html: this.alert_text,
                footer: this.alert_footer
            });
        },
        delete_sweet() {
            this.alert_icon = "warning";
            this.alert_title = "Are you sure?";
            this.alert_text = "You won't be able to revert this!";
            var status = Swal.fire({
                icon: this.alert_icon,
                title: this.alert_title,
                html: this.alert_text,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            });
            return status;
        },
        confirm_sweet() {
            var status = Swal.fire({
                icon: this.alert_icon,
                title: this.alert_title,
                html: this.alert_text,
                showCancelButton: true,
                cancelButtonText: this.cancel_btn,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: this.yes_btn
            });
            return status;
        },
        downloadFromUrl(data) {
            const link = document.createElement("a");
            link.href = data.url;
            link.setAttribute("download", data.new_file_name); //ここらへんは適当に設定する
            document.body.appendChild(link);
            link.click();
        },
        is_disabled(is_system = 0) {
            if (is_system == 0) {
                return true;
            } else {
                return false;
            }
        },
        selectedOption(option) {
            if (this.value) {
                return option.cmn_company_id === this.value.cmn_company_id;
            }
            return false;
        },
        allBuyerInfoBySaller(user_id) {
            // console.log(user_id);
            // return 0;
            axios.post(this.BASE_URL + "api/get_byr_order_data_by_slr", { user_id: user_id })
                .then(({ data }) => {
                    // console.log(data);
                    this.buyer_info_for_saller = data.slr_order_info;
                    // return data.slr_order_info;
                });
        },
        buyer_route_change(byr_buyer_id) {
            this.$session.start()
            this.$session.set('byr_buyer_id', byr_buyer_id);
            if (this.$route.path != '/home/selected_buyer') {
                this.$router.push("/home/selected_buyer");
            }
            Fire.$emit('selectedByuerBlog', byr_buyer_id);
            Fire.$emit('byr_has_selected', byr_buyer_id);
            Fire.$emit('permission_check_for_buyer', byr_buyer_id);
        },
        byr_session_check() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
        number_format(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        get_byr_slr_company(cmn_company_id) {
            if (cmn_company_id == null) {
                this.filter_select_box = true
                    // var company_info=this.get_byr_slr_company(this.cmn_company_id);
                axios.get(this.BASE_URL + "api/get_byr_slr_company/" + this.cmn_company_id)
                    .then(({ data }) => {
                        this.buyers = data.buyer_info;
                        this.sellers = data.seller_info;
                        this.selected_buyer = this.buyers[0]
                        Fire.$emit('company_partner_list_emit', this.selected_buyer.cmn_company_id);
                        Fire.$emit('get_all_company_users_emit', this.selected_buyer.cmn_company_id);
                        // console.log(data)
                    });
            }
        },
        iconSet(text_value) {
            return this.form.sort_by == text_value ? (this.form.sort_type == 'ASC' ? 'fa fa-caret-up' : 'fa fa-caret-down') : '';
        },
        zeroShow(value) {
            return value === 0 ? '0' : value
        },
    },
    filters: {
        subStr: function(string) {
            return string.substring(0, 300) + '...';
        },
        diffForHumans(str) {
            moment.locale('ja');
            return moment(str).from(moment());
        },
        ja_date_time(str) {
            moment.locale('ja');
            return moment(str).format('LLL');
        },
        ja_date(str) {
            moment.locale('ja');
            return moment(str).format('LL');
        },
        priceFormat(str) {
            if (!str) return ''
            return str.toString().replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        priceFormatFloat(str) {
            if (!str) return ''
            var x = str.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            x1 = x1.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            var returnValue = x1 + x2;
            return returnValue;
        },
        priceFormatNullZero(str) {
            if (!str) return '0'
            return str.toString().replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        customArrReverse(str) {
            return str.slice().reverse();
        },
        momentDateTimeFormat(dateString) {
            if (dateString != null) {
                return moment(dateString).format('YYYY-MM-DD H:mm:ss');
            }
        }
    },
    created() {
        // this.beforeCreate();
        // if (!this.$session.exists()) {
        //     this.$router.push('/home')
        // }
        this.byr_buyer_id = this.$session.get('byr_buyer_id');
        this.paramInfo.byr_buyer_id = this.$session.get('byr_buyer_id');

        //this.getbuyerJsonSettingvalue();
        // this.user_data = this.app._data;

        // this.global_user_id = Globals.user_info_id;
        // axios
        //     .get(this.BASE_URL + "api/tblecolsetting/" + this.$route.name)
        //     .then(data => {
        //         this.table_col_setting_list = data.data.result;
        //         this.table_col_arry = data.data.arrs;
        //         this.selected_columns = data.data.selected_columns;
        //         this.col_lists = data.data.col_lists;
        //     });
    }
};