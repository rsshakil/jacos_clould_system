import { Selector } from 'testcafe';
var PropertiesReader = require('properties-reader');
try {
    var properties = PropertiesReader('tests/Testcafe/properties/login.file');
    // console.log(properties)
    var waiting_time = properties.get('waiting_time');
    var super_user_name = properties.get('super_user_name');
    var sakaki_user_name = properties.get('sakaki_user_name');
    var slr_user_name = properties.get('slr_user_name');
    var byr_user_name = properties.get('byr_user_name');
    var biware_level3_user = properties.get('biware_level3_user');
    var password = properties.get('password');
    var sakaki_password = properties.get('sakaki_password');
    var login_url = properties.get('login_url');
    var user_identity = properties.get('user_identity');
    var password_identity = properties.get('password_identity');
    var login_btn_identity = properties.get('login_btn_identity');
    var logout_btn_identity = properties.get('logout_btn_identity');
    var writing_speed = properties.get('writing_speed');
} catch (err) {
    // alert("properties.fileが見つかりません。実行フォルダに配置してください。")
    // window.close();
    console.log(err.message);
}

fixture `Getting Started`.page `${login_url}`
    // .beforeEach(async t => {
    //     t.wait(1000)
    // });

test('Order test by Sakaki Seller', async t => {
    let today = new Date().toISOString().slice(0, 10)
    let order_item_selector = Selector('.order_item_list_table').find('tbody > tr');
    let partner_code_input = Selector('.cmnWidthTable ').find('tr').nth(0).find('td').nth(3).find('input[type=text]');
    await t
        .maximizeWindow()
        // .wait(waiting_time)
        // .setTestSpeed(0.1)

    .typeText(user_identity, sakaki_user_name, { speed: writing_speed })
        // .wait(waiting_time)
        .typeText(password_identity, sakaki_password, { speed: writing_speed })
        // .wait(waiting_time)
        // .takeScreenshot({
        //     path:     'my-fixture/welcome_page.png',
        //     fullPage: true
        // })
        .click(login_btn_identity)
        // .wait(waiting_time)
        .click(Selector('.buyer_button_list').nth(1).find('.btn-outline-primary').nth(0))
        // .wait(waiting_time)
        .click(Selector('.jcs_left_side_bar_menu').nth(4).child(0))
        // .wait(waiting_time)
        // ========Pagination Page click============
        .click(Selector('.page-item').find('.page-link').withText('2'))
        // ========Max line per page select============
        .click(Selector('.selectPagi').find('.selectPage'))
        .click(Selector('option').filter('[value="20"]'))
        // ========Partner Code modal to search============
        .click(Selector('.btn-primary').withText("参照"))
        .click(Selector('.order_list_partner_code_modal').find('tbody > tr').nth(0))
        .scrollIntoView(Selector('.order_list_table'))
        .scrollIntoView(Selector('.orderDetailTable '))
        .click(Selector('.srchBtn').withText("検索"))
    var partner_code_val = await partner_code_input.value;
    var trowsChecked = await Selector('.order_item_details_table tbody tr td').find('.btn-link-custom').count;
    if (trowsChecked <= 0) {
        console.log('no rows found');
    } else {
        console.log(trowsChecked + ' rows found');
    }
    for (let j = 0; j <= 5; j++) {

        // Go to order details page by click receive datetime from 1 to 6
        // await t.click(Selector('.order_list_table').find('tbody > tr').nth(j).find('td').nth(1).find('a'));
        await t.click(Selector('.order_list_table').find('tbody > tr').nth(j).find('td').nth(1).find('button'));
        if (j == 0) {
            await t.wait(waiting_time)
                .scrollBy(0, 300)
                // ========Max line per page select============
                .click(Selector('.selectPagi').find('.selectPage'))
                .click(Selector('option').filter('[value="20"]'))

        }
        if (j == 3) {



            await t.wait(waiting_time)
                .scrollBy(0, 300)
                // ========Go to order item page from order details page by first trade number============
                .click(Selector('.order_details_table').find('tbody > tr').nth(0).find('td').nth(4).find('a'))
                //correction delivery date change
                .typeText(Selector('.correction_delivery_date_table').find('tr').nth(0).find('td').nth(1).find('input[type=date]'), today)
                // Case, Bara and error change than save
                .typeText(order_item_selector.nth(0).find('td').nth(3).find('input[type=number]'), '1', { replace: true })
                .click(order_item_selector.nth(0).find('td').nth(11).find('.error_found'))
                .click(order_item_selector.nth(0).find('td').nth(11).find('.error_found').find('option').withText('納入者品切（取引先責）'))
                // .typeText(order_item_selector.nth(1).find('td').nth(3).find('input[type=number]'), '2', { replace: true })
                // .typeText(order_item_selector.nth(1).find('td').nth(5).find('input[type=number]'), '2', { replace: true })
                // .click(order_item_selector.nth(1).find('td').nth(11).find('.error_found'))
                // .click(order_item_selector.nth(1).find('td').nth(11).find('.error_found').find('option').withText('メーカー品切（取引先責）')) //will remove
                .click(Selector('.btn-primary').withText("更新"))

            .click(Selector('.swal2-confirm').withText("完了"))
                // Go to order details page by breadcumb
                .click(Selector('h6').find('a').withText('受注伝票一覧'));
        }
        if (j == 5) {
            // ========Go to order item page from order details page by first trade number============

            await t.click(Selector('.order_details_table').find('tbody > tr').nth(0).find('td').nth(4).find('a'))
                //correction delivery date change
                .typeText(Selector('.correction_delivery_date_table').find('tr').nth(0).find('td').nth(1).find('input[type=date]'), today)
                //correction delivery date Save
                .click(Selector('.btn-primary').withText("更新"))
                .click(Selector('.swal2-confirm').withText("完了"))
                // Go to order details page by breadcumb
                .click(Selector('h6').find('a').withText('受注伝票一覧'));
        }
        //
        // First row checkbox checked
        await t.click(Selector('.order_details_table').find('tbody > tr').nth(0).find('td').nth(1).find('input[type=checkbox]'))
            .click(Selector('.btn-primary').withText("選択行を伝票確定"))
            .click(Selector('.swal2-confirm').withText("はい"))
            .click(Selector('.swal2-confirm').withText("完了"))
            // Go to order list page
            .click(Selector('h6').find('a').withText('受注トップ'))
            .expect(partner_code_input.value).contains(partner_code_val)
    }
    for (let i = 0; i <= 5; i++) {
        // console.log(i);
        // Go to order details page by click receive datetime
        // await t.click(Selector('.order_list_table').find('tbody > tr').nth(i).find('td').nth(1).find('a'))
        await t.click(Selector('.order_list_table').find('tbody > tr').nth(i).find('td').nth(1).find('button'))
            // Order data send
            .click(Selector('.btn-danger').withText("確定データ送信"))
            .click(Selector('.swal2-confirm').withText("はい"))
            .click(Selector('.swal2-confirm').withText("完了"))
            // Go to order list page
            .click(Selector('h6').find('a').withText('受注トップ'))
            .expect(partner_code_input.value).contains(partner_code_val)
    }
    await t.scrollIntoView(Selector('.order_list_table'))
        // .click(logout_btn_identity)
        // .wait(waiting_time);
});