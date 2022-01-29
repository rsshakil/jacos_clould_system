import { Selector } from 'testcafe';
var fs = require('fs');
var encoding = require('encoding-japanese');
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
    var file_downloaded_path = properties.get('file_downloaded_path');
} catch (err) {
    // alert("properties.fileが見つかりません。実行フォルダに配置してください。")
    // window.close();
    console.log(err.message);
}
fixture `Getting Started`.page `${login_url}`;

test('Shipment file upload test by Sakaki Seller', async t => {
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 2).toISOString().slice(0, 10);
    // let today = date.toISOString().slice(0, 10);
    await t.maximizeWindow().setTestSpeed(0.3)
        .typeText(user_identity, sakaki_user_name, { speed: writing_speed })
        .typeText(password_identity, sakaki_password, { speed: writing_speed })
        .click(login_btn_identity)
        .click(Selector('.buyer_button_list').nth(1).find('.btn-outline-primary').nth(0))
        .click(Selector('.nav-item').find('a').withText('受注・出荷業務'))
        .click(Selector('.order_list_table').find('tbody > tr').nth(0).find('td').nth(1).find('button'))
    await t.scrollIntoView(Selector('.custom-file-upload'))
    for (let i = 1; i <= 12; i++) {
        var shipment_file_path = properties.get('shipment_file_path');
        if (i == 10) {
            shipment_file_path = shipment_file_path + 'shipment_20210416_error_10.csv';
        } else if (i == 11) {
            shipment_file_path = shipment_file_path + 'shipment_20210416_OK_01.csv';
        } else if (i == 12) {
            shipment_file_path = shipment_file_path + 'shipment_20210420_OK.csv';
        } else {
            shipment_file_path = shipment_file_path + 'shipment_20210416_error_0' + i + '.csv';
        }
        await t.hover(Selector('.custom-file-upload'))
            .setFilesToUpload('#updateordershipmentcsv', [shipment_file_path])
            .click(Selector('.swal2-confirm').withText("はい"))
            .click(Selector('.swal2-confirm').withText("完了"))
    }
});
