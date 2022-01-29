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

test('Order details download test by Sakaki Seller', async t => {
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 2).toISOString().slice(0, 10);
    // let today = date.toISOString().slice(0, 10);
    await t.maximizeWindow()
        // .wait(waiting_time)
        .typeText(user_identity, sakaki_user_name, { speed: writing_speed })
        // .wait(waiting_time)
        .typeText(password_identity, sakaki_password, { speed: writing_speed })
        // .wait(waiting_time)
        .click(login_btn_identity)
        // ========Buyer selected============
        .click(Selector('.buyer_button_list').nth(1).find('.btn-outline-primary').nth(0))
        // ========Order menu selected============
        .click(Selector('.nav-item').find('a').withText('受注・出荷業務'))
        .click(Selector('.order_list_table').find('tbody > tr').nth(0).find('td').nth(1).find('button'))
        .click(Selector('.btn-group').find('button').withText('ダウンロード'))
        .click(Selector('.dropdown-menu').find('button').withText('CSV'))
    let files_of_folder = fs.readdirSync(file_downloaded_path);
    let last_file_name = null;
    let stats = null;
    files_of_folder.forEach(element => {
        try {
            stats = fs.statSync(file_downloaded_path + element);
        } catch (error) {
            console.log(error);
        }
        const diffTimeMilliSec = Math.abs(stats.ctime - date);
        const diffDaySec = Math.ceil(diffTimeMilliSec / (1000));
        if (diffDaySec <= 10) {
            last_file_name = element;
        }
    });
    console.log(last_file_name)
        // print file last modified date
        // console.log(`File Data Last Modified: ${stats.mtime}`);
        // console.log(`File Status Last Modified: ${stats.ctime}`);
    if (last_file_name != null) {
        // csv format check
        let name_split = last_file_name.split(".");
        let last_element = name_split[name_split.length - 1];
        if (last_element == "csv") {
            console.log("CSV file found");
        }
        // console.log('Fileconetent are ' + csv_content);
        // // READ CSV INTO STRING
        // var data = fs.readFileSync(file_downloaded_path + last_file_name).toLocaleString();
        var file_data = fs.readFileSync(file_downloaded_path + last_file_name)
            // console.log(data_array)
            // =================
        var data_array = encoding.convert(file_data, {
            to: 'UNICODE',
            from: 'SJIS'
        });
        var data = encoding.codeToString(data_array);
        // console.log(data);
        // =====================
        // STRING TO ARRAY
        var rows = data.split("\n"); // SPLIT ROWS
        // data check and data count
        if (data != null) {
            console.log("Good file and it has " + (rows.length - 1) + " data")
        }

        // column count and check
        let row1 = rows[0].split(",");
        for (let i = 1; i < (rows.length - 1); i++) {
            let others_row = rows[i].split(",");
            if (row1.length != others_row.length) {
                console.log("Bad file")
            }
        }
        // data date/number/text
        var flag_array = [];
        for (let j = 1; j < (rows.length - 1); j++) {
            let column = rows[j].split(",");
            for (let k = 0; k < column.length; k++) {
                // if (k == 0) {
                flag_array.push(formatCheck(removeTags(column[k]), k, j))
                    // }
            }
        }
        // console.log(flag_array)
        if (flag_array.includes(false)) {
            console.log("Bad data format")
        } else {
            console.log("Good data format")
        }

    }

    // csv format  //OK
    // data check  //OK
    // data count  //OK
    // column count  //OK
    // data date/number/text //OK
    // data requre

});

function formatCheck(columnVal, columnNumber = 0, rowNum = 0) {
    let typeArray = typeChecking(columnNumber)
    let returnObj = true;
    if (Object.keys(typeArray).length > 0) {
        let checkType = typeArray['checkType'];
        let objLength = typeArray['objLength'];

        if (columnVal !== '') {
            if (checkType == 'text') {
                // console.log('Actual length: ' + objLength + ' Get: ' + columnVal.length + ' Problem in format: ' + checkType)
                returnObj = (typeof columnVal === 'string' || columnVal instanceof String) && columnVal.length <= objLength
            } else if (checkType == 'number') {
                returnObj = !isNaN(columnVal) && (columnVal.length <= objLength)
            } else if (checkType == 'date') {
                returnObj = isValidDate(columnVal) && (columnVal.length <= objLength)
            }
            if (!returnObj) {
                console.log('Problem in row number: ' + rowNum + ' and column number: ' + columnNumber)
                console.log('Actual length: ' + objLength + ' Get: ' + columnVal.length + ' Problem in format: ' + checkType)
            }
        } else {

        }
    }

    return returnObj;
}

function removeTags(str) {
    return str.replace(/['"]+/g, '')
}

function isValidDate(dateStr) {
    return !isNaN(new Date(dateStr).getDate()) || dateStr === '0000-00-00';
}

function typeChecking(keyNum = 0) {
    var typeArray = {};
    var numberArray = [{ key: 0, length: 13 }, { key: 2, length: 13 }, { key: 6, length: 20 }, { key: 13, length: 8 },
        { key: 14, length: 8 }, { key: 15, length: 8 }, { key: 16, length: 7 }, { key: 19, length: 5 }, { key: 20, length: 5 },
        { key: 23, length: 13 }, { key: 24, length: 13 }, { key: 27, length: 13 }, { key: 28, length: 13 }, { key: 31, length: 10 },
        { key: 32, length: 10 }, { key: 34, length: 13 }, { key: 35, length: 13 }, { key: 38, length: 13 }, { key: 39, length: 13 },
        { key: 42, length: 13 }, { key: 43, length: 13 }, { key: 49, length: 13 }, { key: 50, length: 13 }, { key: 53, length: 13 },
        { key: 54, length: 13 }, { key: 60, length: 2 }, { key: 61, length: 2 }, { key: 62, length: 2 }, { key: 63, length: 2 },
        { key: 74, length: 10 }, { key: 75, length: 10 }, { key: 83, length: 2 }, { key: 86, length: 2 }, { key: 87, length: 2 },
        { key: 88, length: 2 }, { key: 89, length: 2 }, { key: 90, length: 2 }, { key: 91, length: 2 }, { key: 94, length: 2 },
        { key: 95, length: 2 }, { key: 96, length: 4 }, { key: 99, length: 10 }, { key: 100, length: 10 }, { key: 100, length: 10 },
        { key: 101, length: 10 }, { key: 102, length: 6 }, { key: 103, length: 6 }, { key: 104, length: 14 }, { key: 105, length: 4 },
        { key: 110, length: 10 }, { key: 111, length: 10 }, { key: 117, length: 14 }, { key: 118, length: 14 }, { key: 119, length: 3 },
        { key: 120, length: 14 }, { key: 132, length: 4 }, { key: 151, length: 11 }, { key: 152, length: 15 }, { key: 153, length: 11 },
        { key: 154, length: 11 }, { key: 155, length: 10 }, { key: 156, length: 4 }, { key: 157, length: 2 }, { key: 158, length: 2 },
        { key: 159, length: 10 }, { key: 160, length: 11 }, { key: 161, length: 10 }, { key: 162, length: 11 }, { key: 163, length: 10 },
        { key: 164, length: 11 }, { key: 165, length: 5 }, { key: 166, length: 10 }, { key: 168, length: 10 }, { key: 169, length: 10 },
        { key: 170, length: 10 }, { key: 171, length: 16 }, { key: 172, length: 2 }, { key: 173, length: 6 }
    ];
    var textArray = [{ key: 1, length: 4 }, { key: 3, length: 4 }, { key: 4, length: 20 }, { key: 5, length: 2 }, { key: 7, length: 30 },
        { key: 12, length: 80 }, { key: 29, length: 20 }, { key: 30, length: 20 }, { key: 36, length: 20 }, { key: 37, length: 20 },
        { key: 40, length: 20 }, { key: 41, length: 20 }, { key: 45, length: 20 }, { key: 51, length: 20 }, { key: 52, length: 20 },
        { key: 55, length: 20 }, { key: 56, length: 20 }, { key: 121, length: 25 }, { key: 122, length: 25 }, { key: 124, length: 25 }
    ]
    var dateArray = [{ key: 8, length: 20 }, { key: 76, length: 10 }, { key: 77, length: 10 }, { key: 78, length: 10 }, { key: 79, length: 10 }]
        // { key: 79, length: 10 }
        // console.log('Index : ' + jQuery.inArray({ key: keyNum }, numberArray));
    if (numberArray.some(item => item.key === keyNum)) {
        let obj = numberArray.find(o => o.key === keyNum);
        typeArray = { checkType: 'number', objLength: obj.length }
    }
    if (textArray.some(item => item.key === keyNum)) {
        let obj = textArray.find(o => o.key === keyNum);
        typeArray = { checkType: 'text', objLength: obj.length }
    }
    if (dateArray.some(item => item.key === keyNum)) {
        let obj = dateArray.find(o => o.key === keyNum);
        typeArray = { checkType: 'date', objLength: obj.length }
    }
    return typeArray;
}
