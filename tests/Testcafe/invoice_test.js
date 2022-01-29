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
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 2).toISOString().slice(0, 10);
    let today = date.toISOString().slice(0, 10);
    let cnt_selector = Selector('.cmnWidthTable').find('tr');
    // let order_item_selector = Selector('.order_item_list_table').find('tbody > tr');
    let partner_code_input = Selector('.cmnWidthTable ').find('tr').nth(0).find('td').nth(3).find('input[type=text]');
    await t
        .maximizeWindow()
        // .wait(waiting_time)
        .typeText(user_identity, sakaki_user_name, { speed: writing_speed })
        // .wait(waiting_time)
        .typeText(password_identity, sakaki_password, { speed: writing_speed })
        // .wait(waiting_time)
        .click(login_btn_identity)
        // ========Buyer selected============
        .click(Selector('.buyer_button_list').nth(1).find('.btn-outline-primary').nth(0))
        // ========Invoice menu selected============
        .click(Selector('.nav-item').find('a').withText('請求業務'))
        // ========Search============
        .typeText(cnt_selector.nth(0).find('td').nth(1).find('.input-group').find('input[type=date]').nth(0), firstDay)
        .typeText(cnt_selector.nth(0).find('td').nth(1).find('.input-group').find('input[type=date]').nth(1), today)
        // ========Partner Code modal to search============
        .click(Selector('.btn-primary').withText("参照"))
        .click(Selector('.invoice_list_partner_code_modal').find('tbody > tr').nth(0))
        // ========Send and decession datetime select============
        .click(cnt_selector.nth(1).find('td').nth(1).find('select'))
        .click(Selector('option').filter('[value="!0"]').nth(0))
        .click(cnt_selector.nth(1).find('td').nth(3).find('select'))
        .click(Selector('option').filter('[value="!0"]').nth(1))
        // ========Search============
        .click(Selector('.srchBtn').withText("検索"))
        // ========Scroll to invoice list============
        .scrollIntoView(Selector('.invoice_list_table'));
    var partner_code_val = await partner_code_input.value;
    await t
    // ========Go to invoice details page============
        .click(Selector('.invoice_list_table').find('tbody > tr').nth(0).find('td').nth(1).find('button'))
        // ========Details update button clicked============
        .click(Selector('.invoice_details_table').find('tbody > tr').nth(1).find('td').nth(11).find('button').withText("変更"))
        .typeText(Selector('.form-group').find('input[type=number]').nth(3), "0", { replace: true })
        .click(Selector('footer').find('.btn-primary').withText("変更"))
        .click(Selector('.swal2-confirm').withText("完了"))
        .click(Selector('.invoice_details_table').find('tbody > tr').nth(1).find('td').nth(12).find('button').withText("削除"))
        .click(Selector('.swal2-confirm').withText("はい"))
        .click(Selector('.swal2-confirm').withText("完了"))
        // ========Details update button clicked============
        .click(Selector('.invoice_details_table').find('tbody > tr').nth(0).find('td').nth(11).find('button').withText("変更"))
        .typeText(Selector('.form-group').find('input[type=number]').nth(3), "5000", { replace: true })
        .click(Selector('footer').find('.btn-primary').withText("変更"))
        .click(Selector('.swal2-confirm').withText("完了"))
        // ========Details Create button clicked============
        .click(Selector('.invoice_details_create_button').withText("新規伝票追加"))
        .typeText(Selector('.form-group').find('input[type=date]').nth(0), today)
        .typeText(Selector('.form-group').find('input[type=number]').nth(1), "101", { replace: true })
        .typeText(Selector('.form-group').find('input[type=number]').nth(2), "99999999", { replace: true })
        .click(Selector('.form-group').find('select').nth(0))
        .click(Selector('option').filter('[value="1001"]'))
        .click(Selector('.form-group').find('select').nth(1))
        .click(Selector('option').filter('[value="02"]'))
        .typeText(Selector('.form-group').find('input[type=number]').nth(3), "5000", { replace: true })
        .click(Selector('footer').find('.btn-primary').withText("追加"))
        .click(Selector('.swal2-confirm').withText("完了"))
        // ========Scroll to invoice details list============
        .scrollIntoView(Selector('.invoice_details_table'))
        // ========All invoice details list checked============
        .click(Selector('.invoice_details_table').find('thead').find('.first_heading_th').find('th').nth(1).find('input[type=checkbox]'))
        // ========Ddecession datetime updated============
        .click(Selector('.btn-primary').withText("選択行を請求確定"))
        .click(Selector('.swal2-confirm').withText("はい"))
        .click(Selector('.swal2-confirm').withText("完了"))
        // ========Send datetime updated============
        .click(Selector('.btn-danger').withText("請求データ送信"))
        .click(Selector('.swal2-confirm').withText("はい"))
        .click(Selector('.swal2-confirm').withText("完了"))
        // Go to invoice list page
        .click(Selector('h6').find('a').withText('請求トップ'))
        .expect(partner_code_input.value).contains(partner_code_val)
        // ========Send datetime select============
        .click(cnt_selector.nth(1).find('td').nth(3).find('select'))
        .click(Selector('option').filter('[value="0"]').nth(1))
        // ========Search============
        .click(Selector('.srchBtn').withText("検索"))
        // ========Scroll to invoice list============
        .scrollIntoView(Selector('.invoice_list_table'));
});
