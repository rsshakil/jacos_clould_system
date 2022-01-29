// import Home from './components/backend/home_component.vue'
const Home = () =>
    import ( /* webpackChunkName: "home" */ './components/backend/MAIN/home_component.vue')
    // admin
const Role = () =>
    import ( /* webpackChunkName: "role" */ './components/backend/ADM/ADM/role_component.vue')
const permission = () =>
    import ( /* webpackChunkName: "permission" */ './components/backend/ADM/ADM/permission_component.vue')
const assign_role_model = () =>
    import ( /* webpackChunkName: "assign_role_model" */ './components/backend/ADM/ADM/assign_role_model.vue')
const assign_permission_model = () =>
    import ( /* webpackChunkName: "assign_permission_model" */ './components/backend/ADM/ADM/assign_permission_model.vue')
const users = () =>
    import ( /* webpackChunkName: "users" */ './components/backend/ADM/ADM/users.vue')
const user_update = () =>
    import ( /* webpackChunkName: "user_update" */ './components/backend/ADM/ADM/user_update.vue')
const password_reset = () =>
    import ( /* webpackChunkName: "password_reset" */ './components/backend/ADM/ADM/password_reset.vue')

// data/order
const order_list = () =>
    import ( /* webpackChunkName: "order_list" */ './components/backend/DATA/ORDER/order_list.vue')
const order_vouchers = () =>
    import ( /* webpackChunkName: "order_vouchers" */ './components/backend/DATA/ORDER/order_vouchers.vue')
const item_search = () =>
    import ( /* webpackChunkName: "item_search" */ './components/backend/DATA/ORDER/item_search.vue')
const item_search_detail = () =>
    import ( /* webpackChunkName: "item_search_detail" */ './components/backend/DATA/ORDER/item_search_detail.vue')
const order_items = () =>
    import ( /* webpackChunkName: "order_items" */ './components/backend/DATA/ORDER/order_items.vue')


// data/shipment
// data/receive
// data/invoice
// data/payment

// common
const order_details_canvas = () =>
    import ( /* webpackChunkName: "order_details_canvas" */ './components/backend/CANVAS/order_details_canvas.vue')
    // import default_parent from './components/backend/default_parent.vue'
const voucher_setting = () =>
    import ( /* webpackChunkName: "voucher_setting" */ './components/backend/CANVAS/canvas.vue')
const byr_management = () =>
    import ( /* webpackChunkName: "byr_management" */ './components/backend/SLR/byr_management.vue')
    // import byr_management_edit from './components/backend/SLR/byr_management_edit.vue'
const cmn_company_user_list = () =>
    import ( /* webpackChunkName: "cmn_company_user_list" */ './components/backend/CMN/cmn_company_user_list.vue')
const cmn_company_partner_list = () =>
    import ( /* webpackChunkName: "cmn_company_partner_list" */ './components/backend/CMN/cmn_company_partner_list.vue')
const scenario_management = () =>
    import ( /* webpackChunkName: "scenario_management" */ './components/backend/JACOS_MANAGEMENT/scenario_management.vue')
const scenario_history = () =>
    import ( /* webpackChunkName: "scenario_history" */ './components/backend/JACOS_MANAGEMENT/scenario_history.vue')
const data_test = () =>
    import ( /* webpackChunkName: "data_test" */ './components/backend/JACOS_MANAGEMENT/data_test.vue')
const job_management = () =>
    import ( /* webpackChunkName: "job_management" */ './components/backend/JACOS_MANAGEMENT/job_management.vue')
const slr_management = () =>
    import ( /* webpackChunkName: "slr_management" */ './components/backend/SLR/slr_management.vue')
const slr_job_list = () =>
    import ( /* webpackChunkName: "slr_job_list" */ './components/backend/SLR/slr_job_list.vue')
    // import company_seller_user_list from './components/backend/CMN/cmn_company_seller_user_list.vue'
const item_master = () =>
    import ( /* webpackChunkName: "item_master" */ './components/backend/BYR/byr_item_master.vue')
const item_category = () =>
    import ( /* webpackChunkName: "item_category" */ './components/backend/BYR/item_category.vue')
const receive_list = () =>
    import ( /* webpackChunkName: "receive_list" */ './components/backend/DATA/RECEIVE/receive_list.vue')
const receive_detail = () =>
    import ( /* webpackChunkName: "receive_detail" */ './components/backend/DATA/RECEIVE/receive_detail.vue')
const receive_item_detail = () =>
    import ( /* webpackChunkName: "receive_item_detail" */ './components/backend/DATA/RECEIVE/receive_item_detail.vue')
const data_return_list = () =>
    import ( /* webpackChunkName: "data_return_list" */ './components/backend/DATA/RETURN/data_return_list.vue')
const return_detail = () =>
    import ( /* webpackChunkName: "return_detail" */ './components/backend/DATA/RETURN/return_detail.vue')
const return_item_detail = () =>
    import ( /* webpackChunkName: "return_item_detail" */ './components/backend/DATA/RETURN/return_item_detail.vue')
const order_corrected_receive = () =>
    import ( /* webpackChunkName: "order_corrected_receive" */ './components/backend/DATA/CRTRCV/order_corrected_receive.vue')
const return_item_list = () =>
    import ( /* webpackChunkName: "return_item_list" */ './components/backend/DATA/RETURN/return_item_list.vue')
const payment_list = () =>
    import ( /* webpackChunkName: "payment_list" */ './components/backend/DATA/PAYMENT/payment_list.vue')
const payment_detail = () =>
    import ( /* webpackChunkName: "payment_detail" */ './components/backend/DATA/PAYMENT/payment_detail.vue')
const payment_item_detail = () =>
    import ( /* webpackChunkName: "payment_item_detail" */ './components/backend/DATA/PAYMENT/payment_item_detail.vue')
const invoice_list = () =>
    import ( /* webpackChunkName: "invoice_list" */ './components/backend/DATA/INVOICE/invoice_list.vue')
const invoice_details = () =>
    import ( /* webpackChunkName: "invoice_details" */ './components/backend/DATA/INVOICE/invoice_details.vue')
const voucher_detail = () =>
    import ( /* webpackChunkName: "voucher_detail" */ './components/backend/CANVAS/voucher_detail.vue')
const pdf_platform_setting = () =>
    import ( /* webpackChunkName: "pdf_platform_setting" */ './components/backend/PDF_PLATFORM/pdf_platform_setting.vue')
const pdf_platform_view = () =>
    import ( /* webpackChunkName: "pdf_platform_view" */ './components/backend/PDF_PLATFORM/pdf_platform_view.vue')
const blog = () =>
    import ( /* webpackChunkName: "blog" */ './components/backend/CMN/cmn_blog_list.vue')
const document_management = () =>
    import ( /* webpackChunkName: "document_management" */ './components/backend/ADM/DFLT/document_management')
    // import ( /* webpackChunkName: "document_management" */ './components/backend/ADM/DFLT/document_management.vue')
const selected_buyer = () =>
    import ( /* webpackChunkName: "selected_buyer" */ './components/backend/SLR/selected_byr_page.vue')
const management_setting = () =>
    import ( /* webpackChunkName: "management_setting" */ './components/backend/CONFIG/management_setting.vue')
const stock_item_list = () =>
    import ( /* webpackChunkName: "stock_item_list" */ './components/backend/DATA/STOCK/stock_item_list.vue')

// Byr Menu Pages
const slr_order_list = () =>
    import ( /* webpackChunkName: "slr_order_list" */ './components/backend/BYR/DATA/ORDER/slr_order_list.vue')
const slr_order_list_details = () =>
    import ( /* webpackChunkName: "slr_order_list_details" */ './components/backend/BYR/DATA/ORDER/slr_order_list_details.vue')
const slr_order_list_items = () =>
    import ( /* webpackChunkName: "slr_order_list_items" */ './components/backend/BYR/DATA/ORDER/slr_order_list_items.vue')
const slr_item_search = () =>
    import ( /* webpackChunkName: "slr_item_search" */ './components/backend/BYR/DATA/ORDER/slr_item_search.vue')
const slr_item_search_detail = () =>
    import ( /* webpackChunkName: "slr_item_search_detail" */ './components/backend/BYR/DATA/ORDER/slr_item_search_detail.vue')

// Slr Receive
const slr_receive_list = () =>
    import ( /* webpackChunkName: "slr_receive_list" */ './components/backend/BYR/DATA/RECEIVE/slr_receive_list.vue')
const slr_receive_detail = () =>
    import ( /* webpackChunkName: "slr_receive_detail" */ './components/backend/BYR/DATA/RECEIVE/slr_receive_detail.vue')
const slr_receive_item_detail = () =>
    import ( /* webpackChunkName: "slr_receive_item_detail" */ './components/backend/BYR/DATA/RECEIVE/slr_receive_item_detail.vue')

// Slr return
const slr_data_return_list = () =>
    import ( /* webpackChunkName: "slr_data_return_list" */ './components/backend/BYR/DATA/RETURN/slr_data_return_list.vue')
const slr_return_detail = () =>
    import ( /* webpackChunkName: "slr_return_detail" */ './components/backend/BYR/DATA/RETURN/slr_return_detail.vue')
const slr_return_item_detail = () =>
    import ( /* webpackChunkName: "slr_return_item_detail" */ './components/backend/BYR/DATA/RETURN/slr_return_item_detail.vue')

// Slr Payment
const slr_payment_list = () =>
    import ( /* webpackChunkName: "slr_payment_list" */ './components/backend/BYR/DATA/PAYMENT/slr_payment_list.vue')
const slr_payment_detail = () =>
    import ( /* webpackChunkName: "slr_payment_detail" */ './components/backend/BYR/DATA/PAYMENT/slr_payment_detail.vue')
const slr_payment_item_detail = () =>
    import ( /* webpackChunkName: "slr_payment_item_detail" */ './components/backend/BYR/DATA/PAYMENT/slr_payment_item_detail.vue')

// Slr Invoice
const slr_invoice_list = () =>
    import ( /* webpackChunkName: "slr_invoice_list" */ './components/backend/BYR/DATA/INVOICE/slr_invoice_list.vue')
const slr_invoice_details = () =>
    import ( /* webpackChunkName: "slr_invoice_details" */ './components/backend/BYR/DATA/INVOICE/slr_invoice_details.vue')
    // slr_stock_item_list
const slr_stock_item_list = () =>
    import ( /* webpackChunkName: "slr_stock_item_list" */ './components/backend/BYR/DATA/STOCK/slr_stock_item_list.vue')

// import login_body from './components/login/login_body.vue'

export const routes = [

    {
        path: '/home',
        component: Home,
        name: 'home',
        meta: {
            breadcrumb: {
                label: 'HOME'
            }
        },
    },
    {
        path: '/role',
        name: "role",
        component: Role,
        meta: {
            breadcrumb: {
                label: '役割'
            },
        },
    },
    {
        path: '/permission',
        name: 'permission',
        component: permission,
        meta: {
            breadcrumb: {
                label: '許可'
            },
        },
    },
    {
        path: '/assign_role_to_user',
        name: 'assign_role_to_user',
        component: assign_role_model,
        meta: {
            breadcrumb: {
                label: 'ユーザーに役割を割り当てる'
            },
        },
    },
    {
        path: '/assign_permission_to_user',
        name: 'assign_permission_to_user',
        component: assign_permission_model,
        meta: {
            breadcrumb: {
                label: 'ユーザーに権限を割り当てる'
            },
        },
    },
    {
        path: '/users',
        name: 'users',
        component: users,
        meta: {
            breadcrumb: {
                label: 'ユーザー'
            },
        },
    },
    {
        path: '/users/:id/:auth_id',
        name: 'user_update',
        component: user_update,
        meta: {
            breadcrumb: {
                label: 'ユーザーアップデート',
                parent: 'users'
            },
        },
    },
    {
        path: '/password_reset/:id/:auth_id',
        name: 'password_reset',
        component: password_reset,
        meta: {
            breadcrumb: {
                label: 'パスワードのリセット',
                parent: 'users'
            },
        },
    },
    {
        path: '/blog',
        component: blog,
        name: 'blog',
        meta: {
            breadcrumb: {
                label: 'お知らせ'
            }
        },
    },
    {
        path: '/order_list',
        component: order_list,
        name: 'order_list',
        meta: {
            breadcrumb: {
                label: '受注トップ',
                parent: 'home'
            },
        },
        children: [

            {
                path: '/order_list/order_details_canvas/:byr_order_id',
                component: order_details_canvas,
                name: 'order_details_canvas',
                meta: {
                    breadcrumb: 'canvas order'
                }
            }
        ]
    },


    {
        path: '/order_list/order_list_details',
        component: order_vouchers,
        name: 'order_list_details',
        meta: {
            // breadcrumb: 'Order detail'
            breadcrumb: {
                label: '受注伝票一覧',
                parent: 'order_list'
            },
        },

    },

    {
        path: '/order_list/order_list_details/item_search',
        component: item_search,
        name: 'item_search',
        meta: {

            breadcrumb: {
                label: '受注商品別一覧',
                parent: 'order_list_details'
            },
        },

    },

    {
        path: '/order_list/order_list_details/item_search/item_search_detail',
        component: item_search_detail,
        name: 'item_search_detail',
        meta: {

            breadcrumb: {
                label: '受注商品別明細',
                parent: 'item_search'
            },
        },

    },
    {
        path: '/order_list/order_list_details/order_item_list_detail/:data_order_list_voucher_id',
        component: order_items,
        name: 'order_item_list_detail',
        meta: {
            breadcrumb: {
                label: '受注伝票明細',
                parent: 'order_list_details'
            }
        }
    },


    {
        path: '/voucher_setting',
        name: 'voucher_setting',
        component: voucher_setting,
        meta: {

            breadcrumb: {
                label: 'Voucher seting'
            }

        },
    },
    {
        path: '/byr_management',
        component: byr_management,
        name: 'byr_management',
        meta: {

            breadcrumb: {
                label: '小売管理',
            }

        },


    },
    {
        path: '/byr_management/byr_company_user_list',
        component: cmn_company_user_list,
        name: 'byr_company_user_list',
        meta: {
            breadcrumb: {
                label: 'ユーザーリスト管理',
                parent: 'byr_management'
            },
        },
    },

    {
        // path: '/byr_management/byr_company_partner_list/:byr_buyer_id/slr_job_list/:slr_seller_id',
        path: '/byr_management/byr_company_partner_list',
        component: slr_job_list,
        name: 'slr_job_list',
        meta: {
            breadcrumb: {
                label: 'ユーザーリスト管理',
                parent: 'byr_management'
            },
        }
    },
    {
        path: '/partner_list_manage',
        component: cmn_company_partner_list,
        name: 'partner_list_manage',
        meta: {
            breadcrumb: {
                label: 'パートナーリスト',
            },
        },

    },
    {
        path: '/byr_management/partner_list_manage',
        component: cmn_company_partner_list,
        name: 'adm_partner_list_manage',
        meta: {
            breadcrumb: {
                label: 'パートナーリスト',
                parent: 'byr_management'
            },
        },

    },
    {
        path: '/user_list_manage',
        component: cmn_company_user_list,
        name: 'user_list_manage',
        meta: {

            breadcrumb: {
                label: 'ユーザーリスト管理'
            },
        },
    },
    {
        path: '/scenario_management',
        component: scenario_management,
        name: 'scenario_management',
        meta: {
            breadcrumb: {
                label: 'シナリオ管理'
            }
        },
    },
    {
        path: '/document_management',
        component: document_management,
        name: 'document_management',
        meta: {
            breadcrumb: {
                label: 'Document Management'
            }
        },
    },
    {
        path: '/scenario_history',
        component: scenario_history,
        name: 'scenario_history',
        meta: {
            breadcrumb: {
                label: 'シナリオ履歴'
            }
        },
    },
    {
        path: '/data_test',
        component: data_test,
        name: 'data_test',
        meta: {
            breadcrumb: {
                label: 'データテスト'
            }
        },
    },
    {
        path: '/job_management',
        component: job_management,
        name: 'job_management',
        meta: {
            breadcrumb: {
                label: '仕事の管理'
            }
        },
    },
    {
        path: '/slr_management',
        component: slr_management,
        name: 'slr_management',
        meta: {
            breadcrumb: {
                label: '問屋管理'
            }
        },
    },
    {
        path: '/slr_management/slr_company_user_list',
        component: cmn_company_user_list,
        name: 'slr_company_user_list',
        meta: {
            breadcrumb: {
                label: '問屋管理'
            }
        },

    },
    {
        path: '/slr_management/slr_company_partner_list',
        component: cmn_company_partner_list,
        name: 'slr_company_partner_list',
        meta: {
            breadcrumb: {
                label: '問屋管理'
            }
        },

    },
    {
        path: '/item_master',
        component: item_master,
        name: 'item_master',
        meta: {
            breadcrumb: {
                label: '商品メンテ'
            }
        },
    },
    {

        path: '/item_category',
        component: item_category,
        name: 'item_category',
        meta: {
            breadcrumb: '分類'
        },

    },

    {
        path: '/receive_list',
        component: receive_list,
        name: 'receive_list',
        meta: {
            breadcrumb: {
                label: '受領トップ',
                parent: 'home'
            }
        },
    },
    {
        path: '/receive_list/receive_detail',
        component: receive_detail,
        name: 'receive_detail',
        meta: {
            breadcrumb: {
                label: '受領伝票一覧',
                parent: 'receive_list'
            }
        },
    },
    {
        path: '/receive_list/receive_detail/receive_item_detail/:data_receive_voucher_id',
        component: receive_item_detail,
        name: 'receive_item_detail',
        meta: {
            breadcrumb: {
                label: '受領伝票明細',
                parent: 'receive_detail'
            }
        },
    },
    //return list

    {
        path: '/return_list',
        component: data_return_list,
        name: 'return_list',
        meta: {
            breadcrumb: {
                label: '返品トップ',
                parent: 'home'
            }
        },
    },
    {
        path: '/return_list/return_detail',
        component: return_detail,
        name: 'return_detail',
        meta: {
            breadcrumb: {
                label: '返品伝票一覧',
                parent: 'return_list'
            }
        },
    },
    {
        path: '/return_list/return_detail/return_item_detail',
        component: return_item_detail,
        name: 'return_item_detail',
        meta: {
            breadcrumb: {
                label: '返品伝票明細',
                parent: 'return_detail'
            }
        },
    },

    //return list end

    //slr_return list

    {
        path: '/slr_return_list',
        component: slr_data_return_list,
        name: 'slr_return_list',
        meta: {
            breadcrumb: {
                label: '返品トップ',
                parent: 'home'
            }
        },
    },
    {
        path: '/slr_return_list/slr_return_detail',
        component: slr_return_detail,
        name: 'slr_return_detail',
        meta: {
            breadcrumb: {
                label: '返品伝票一覧',
                parent: 'slr_return_list'
            }
        },
    },
    {
        path: '/slr_return_list/slr_return_detail/slr_return_item_detail',
        component: slr_return_item_detail,
        name: 'slr_return_item_detail',
        meta: {
            breadcrumb: {
                label: '返品伝票明細',
                parent: 'slr_return_detail'
            }
        },
    },

    //slr_return list end

    {
        path: '/order_corrected_receive',
        component: order_corrected_receive,
        name: 'order_corrected_receive',
        meta: {
            breadcrumb: {
                label: '受領訂正データ'
            }
        },

    },
    {
        path: '/return_item_list',
        component: return_item_list,
        name: 'return_item_list',
        meta: {
            breadcrumb: {
                label: '返品データ'
            }
        },

    },
    //payment_list start
    {
        path: '/payment_list',
        component: payment_list,
        name: 'payment_list',
        meta: {
            breadcrumb: {
                label: '支払トップ',
                parent: 'home'
            }
        }

    },
    {
        path: '/payment_list/payment_detail',
        component: payment_detail,
        name: 'payment_detail',
        meta: {

            breadcrumb: {
                label: '支払合計',
                parent: 'payment_list'
            },
        },

    },
    {
        path: '/payment_list/payment_detail/payment_item_detail',
        component: payment_item_detail,
        name: 'payment_item_detail',
        meta: {

            breadcrumb: {
                label: '支払伝票一覧',
                parent: 'payment_detail'
            },
        },

    },
    //slr_payment_list start
    {
        path: '/slr_payment_list',
        component: slr_payment_list,
        name: 'slr_payment_list',
        meta: {
            breadcrumb: {
                label: '支払トップ',
                parent: 'home'
            }
        }

    },
    {
        path: '/slr_payment_list/slr_payment_detail',
        component: slr_payment_detail,
        name: 'slr_payment_detail',
        meta: {

            breadcrumb: {
                label: '支払合計',
                parent: 'slr_payment_list'
            },
        },

    },
    {
        path: '/slr_payment_list/slr_payment_detail/slr_payment_item_detail',
        component: slr_payment_item_detail,
        name: 'slr_payment_item_detail',
        meta: {

            breadcrumb: {
                label: '支払伝票一覧',
                parent: 'slr_payment_detail'
            },
        },

    },
    //payment end
    {
        path: '/invoice_list',
        component: invoice_list,
        name: 'invoice_list',
        meta: {
            breadcrumb: {
                label: '請求トップ',
                parent: 'home'
            },
        },
    },
    {
        path: '/invoice_list/invoice_details',
        component: invoice_details,
        name: 'invoice_details',
        meta: {
            breadcrumb: {
                label: '請求伝票一覧',
                parent: 'invoice_list'
            }
        }
    },
    //slr invoice list
    {
        path: '/slr_invoice_list',
        component: slr_invoice_list,
        name: 'slr_invoice_list',
        meta: {
            breadcrumb: {
                label: '請求トップ',
                parent: 'home'
            }
        },


    },
    {
        path: '/slr_invoice_list/slr_invoice_details',
        component: slr_invoice_details,
        name: 'slr_invoice_details',
        meta: {
            breadcrumb: {
                label: '請求伝票一覧',
                parent: 'slr_invoice_list'
            }
        }
    },
    //slr invoice list
    {
        path: '/invoice_list/voucher_detail/:voucher_number',
        component: voucher_detail,
        name: 'voucher_detail',
        meta: { breadcrumb: '伝票一覧・新規請求' }
    },
    {
        path: '/pdf_platform_setting',
        component: pdf_platform_setting,
        name: 'pdf_platform_setting',
        meta: {
            breadcrumb: {
                label: 'pdfプラットフォーム設定'
            }
        },

    },
    {
        path: '/pdf_platform_view',
        component: pdf_platform_view,
        name: 'pdf_platform_view',
        meta: {
            breadcrumb: {
                label: 'pdfプラットフォーム'
            }
        },

    },
    {
        path: '/home/selected_buyer',
        component: selected_buyer,
        name: 'selected_buyer',
        props: true,

        meta: { breadcrumb: { label: '得意先別HOME', parent: 'home' } },


    },
    {
        path: '/management_setting',
        component: management_setting,
        name: 'management_setting',
        meta: {
            breadcrumb: {
                label: '管理',
                parent: 'home'
            }
        },
    },
    {
        path: '/stock_item_list',
        component: stock_item_list,
        name: 'stock_item_list',
        meta: {
            breadcrumb: {
                label: '集計情報',
                parent: 'home'
            }
        },
    },
    {
        path: '/slr_order_list',
        component: slr_order_list,
        name: 'slr_order_list',
        meta: {
            breadcrumb: {
                label: '受注トップ',
                parent: 'home'
            }
        },
    },
    {
        path: '/slr_order_list/slr_order_list_details',
        component: slr_order_list_details,
        name: 'slr_order_list_details',
        meta: {
            // breadcrumb: 'Order detail'
            breadcrumb: {
                label: '受注伝票一覧',
                parent: 'slr_order_list'
            },
        },

    },
    {
        path: '/slr_order_list/slr_order_list_details/slr_order_item_list_detail',
        component: slr_order_list_items,
        name: 'slr_order_item_list_detail',
        meta: {
            breadcrumb: {
                label: '受注伝票明細',
                parent: 'slr_order_list_details'
            }
        }
    },

    {
        path: '/slr_order_list/slr_order_list_details/slr_item_search',
        component: slr_item_search,
        name: 'slr_item_search',
        meta: {

            breadcrumb: {
                label: '受注商品別一覧',
                parent: 'slr_order_list_details'
            },
        },

    },

    {
        path: '/slr_order_list/slr_order_list_details/slr_item_search/slr_item_search_detail',
        component: slr_item_search_detail,
        name: 'slr_item_search_detail',
        meta: {

            breadcrumb: {
                label: '受注商品別明細',
                parent: 'slr_item_search'
            },
        },

    },


    //slr receive list
    {
        path: '/slr_receive_list',
        component: slr_receive_list,
        name: 'slr_receive_list',
        meta: {
            breadcrumb: {
                label: '受領トップ',
                parent: 'home'
            }
        },
    },
    {
        path: '/slr_receive_list/slr_receive_detail',
        component: slr_receive_detail,
        name: 'slr_receive_detail',
        meta: {
            breadcrumb: {
                label: '受領伝票一覧',
                parent: 'slr_receive_list'
            }
        },
    },
    {
        path: '/slr_receive_list/slr_receive_detail/slr_receive_item_detail/:data_receive_voucher_id',
        component: slr_receive_item_detail,
        name: 'slr_receive_item_detail',
        meta: {
            breadcrumb: {
                label: '受領伝票明細',
                parent: 'slr_receive_detail'
            }
        },
    },
    // slr_stock_item_list
    {
        path: '/slr_stock_item_list',
        component: slr_stock_item_list,
        name: 'slr_stock_item_list',
        meta: {
            breadcrumb: {
                label: '集計情報',
                parent: 'home'
            }
        },
    },
    //slr receive list end
    // { path: '/login', name: 'login', component: login_body },
];