import { Selector, Role } from 'testcafe'; // first import testcafe selectors

    //Test Spped for Checking
    const test_speed = 1;
    // ======================================
    // information
    var user_name = 'test';
    var user_update_name = 'fullname update';
    var user_update_mail ='update@gmail.com';
    var email = 'test@gmail.com';
    var role_name = 'test';
    var role_descr = "role description";
    var role_update_name = 'test role update';
    var role_update_description = 'role update description';
    var permission_name = "Permission Test";
    var permission_description = "Permission Test Description";
    var permission_update_name ="permission update name";
    var permission_update_description = "permission update description";
    var new_password = "456789";

    // ======================================


    const  staff  =  Role ('http://localhost/template/login',async t =>{ 
        await  t 
            .typeText ( '#email' ,  'mayeennbd@gmail.com' ) 
            .typeText ( '#password' ,  '123456' ) 
            .click ( '#login' ); 
            //.expect(Selector('.stats-small__value').innerText).eql('ジャコスのユーザー管理へようこそ'); 
    },{preserveUrl:true});

    const  new_user  =  Role ('http://localhost/template/login',async t =>{ 
        await  t 
            
            .typeText ( '#email', user_update_mail ) 
            .typeText ( '#password', new_password ) 
            .click ( '#login' ); 
    },{preserveUrl:true});
      
    const  user  =  Role ('http://localhost/template/login',async t =>{ 
        await  t 
            
            .typeText ( '#email', 'admin@gmail.com' ) 
            .typeText ( '#password', new_password ) 
            .click ( '#login' ); 
    },{preserveUrl:true});
      

    
fixture `Template Project Test`// declare the fixture

        //then create a test and place your code there
        test('My lOGIN Login', async t => {
            await t
            .setPageLoadTimeout(100)
            .setTestSpeed(test_speed)
            .useRole(staff)
        });

        // create User Test 
        test('User Create Page', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ管理'))
            .click(Selector('#create_new'))
            .typeText('#name', user_name)
            .typeText('#email', email)
            .typeText('#password', '123456')
            .typeText('#password-confirm', '123456')
            .click('#new_user_save')
            // create check
           .expect(Selector('#user_main_message').child('div').innerText).contains('メッセージ: ユーザー作成')
           // User name check 
           .expect(Selector('#user_list_tbl').find('tr').nth((await Selector('#user_list_tbl').find('tr').count) - 1).child('td').nth(1).innerText).eql(user_name)
            // email check
            .expect(Selector('#user_list_tbl').find('tr').nth((await Selector('#user_list_tbl').find('tr').count) - 1).child('td').nth(2).innerText).eql(email)
        })

        // create Role Test 
        test('Create role test', async t => {

            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ロール管理'))
            .typeText(Selector('#role'), role_name)
            .typeText(Selector('.select2-selection__rendered'), 'update_users')
            .pressKey('enter')
            .typeText(Selector('#role_description'), role_descr)
            .click(Selector('#role_button'))
            .expect(Selector("#role_main_message").child('div').innerText).contains('メッセージ：ロールを追加しました')
            //Role Create Check
           .expect(Selector('#role_list_tbl').find('tr').nth((await Selector('#role_list_tbl').find('tr').count) - 1).child('td').nth(1).innerText).eql(role_name)
            //console.log(await Selector('#role_list').find('tr').nth((await Selector('#role_list').find('tr').count) - 1).child('td').nth(0).innerText);
           //.expect(Selector("#role_main_message").child('div').innerText).contains('メッセージ：ロールを追加しました')
        })

        // create Permission Test 
        test('Create Permission test', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('権限管理'))
            .typeText(Selector('#permission_name'), permission_name)
            //.typeText(Selector('#permission_descr'), permission_description)
            .typeText(Selector('#permission_descr'), permission_description)
            .click(Selector('#permission_button'))
             //Permission Create Check
             //.expect(Selector("#permission_main_message").child('div').innerText).contains('メッセージ：権限設定完了')
            .expect(Selector('#permission_list_tbl').find('tr').nth((await Selector('#permission_list_tbl').find('tr').count) - 1).child('td').nth(1).innerText).eql(permission_name)
            //console.log('count :' + await Selector('#permission_list_tbl').find('tr').count)
        })


        test('User permission Test', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ-権限'))
            .click(Selector('#user_id_for_permission'))
            .click(Selector('option').withText(user_name))
            .click(Selector('label').withText('update_users').find('[name="permission[]"]'))
            .click(Selector('label').withText('ban').find('[name="permission[]"]'))
            .click(Selector('#save_permission'))
            //User Permission Assig Check
            .expect(Selector("#assign_permission_message").child('div').innerText).contains('メッセージ: 成功')
            .expect(Selector('label').withText('update_users').find('[name="permission[]"]').checked).ok()
            .expect(Selector('label').withText('ban_users').find('[name="permission[]"]').checked).ok()
        })


        // User Role Assign Test
        test('User Role Assign Test', async t => {
           // const checkbox = Selector('#role');
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ-ロール'))
            .click(Selector('#user_id_for_role'))
            .click(Selector('option').withText(user_name))
            .click(Selector('label').withText(role_name))
            .click(Selector('#user_role_assign'))
            //User Role Assign Check
            .expect(Selector("#assign_role_message").child('div').innerText).contains('設定しました')
            // .click(Selector('input[name="role[]"]').withText('test role'))
            .expect(Selector('label').withText(role_name).find('[name="role[]"]').checked).ok()

        })

        

        test('USer Update Test', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ管理'))
            .click(Selector('td').withText(user_name).nextSibling('td').find('a'))
            .selectText(Selector('#f_name'))
            .typeText(Selector('#f_name'), 'first name', {
                caretPos: 0
            })
            .selectText(Selector('#l_name'))
            .typeText(Selector('#l_name'), 'last name', {
                caretPos: 0
            })
            .selectText(Selector('#full_name'))
            .typeText(Selector('#full_name'),user_update_name)
            .selectText(Selector('#email'))
            .typeText(Selector('#email'),user_update_mail)
            .typeText(Selector('#dob'), '1993-01-01')

            .click(Selector('#gender'))
            .click(Selector('#gender').find('option').nth(2))
            .click(Selector('#update'))
            //User Update Message Check
            .expect(Selector("#user_update_message").child('div').innerText).contains('メッセージ: ユーザー管理を更新しました')
            .click(Selector('a').withText('ユーザ管理'))
            .expect(Selector("td").withText(user_update_name).innerText).eql(user_update_name)
            .expect(Selector("td").withText(user_update_mail).innerText).eql(user_update_mail)
        })

        test('Role Permission Update Test', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ロール管理'))
            // .click(Selector('td').withText(role_name).nextSibling('td').find('a.btn.btn-info'))
            .click(Selector('td').withText(role_name).nextSibling('td').find('i.fa-edit'))

            .selectText(Selector('#role'))
            .typeText(Selector('#role'),role_update_name)
            .typeText(Selector('.select2-selection__rendered'), 'delete_roles')
            .pressKey('enter')

            .selectText(Selector('#role_description'))
            .typeText(Selector('#role_description'), role_update_description)

            .click(Selector('#role_button'))
            //Check role update test
            .expect(Selector("#role_main_message").child('div').innerText).contains('メッセージ：役割が更新されました')
            // .click(Selector('a').withText('ロール管理'))
            .expect(Selector("td").withText(role_update_name).innerText).eql(role_update_name)
            //.expect(Selector("td").withText(role_update_description).innerText).eql(role_update_description)
             
        })

        test('Permission update Test', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            // .click(Selector('a').withText('account'))
            // .click(Selector('.btn.btn-info').nth(18).find('.fas.fa-edit'))
            .click(Selector('a').withText('権限管理'))
            // .click(Selector('td').withText(role_name).nextSibling('td').find('a.btn.btn-info'))
            .click(Selector('td').withText(permission_name).nextSibling('td').find('.btn.btn-info'))

            .selectText(Selector('#permission_name'))
            .typeText(Selector('#permission_name'),permission_update_name)

            .selectText(Selector('#permission_descr'))
            .typeText(Selector('#permission_descr'), permission_update_description)
            
            .click(Selector('#permission_button'))
            //Check permission update test 
            .expect(Selector("#permission_main_message").child('div').innerText).contains('権限を更新しました')
            .click(Selector('a').withText('権限管理'))
            .expect(Selector("td").withText(permission_update_name).innerText).eql(permission_update_name)
            
        })

        test
            .before( async t => {
                /* test initialization code */
                console.log('role create');
                role_name = 'role test 2';
                role_descr = 'role description 2'
                await t
                .useRole(staff)
                .click(Selector('a').withText('ロール管理'))
                .typeText(Selector('#role'), role_name)
                .typeText(Selector('.select2-selection__rendered'), 'update_roles')
                .pressKey('enter')
                .typeText(Selector('#role_description'), role_descr)
                .click(Selector('#role_button'))
            })
            ('user  Role  Update Test', async t => {

            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ-ロール'))
            //.click(Selector('a').withText('all').find('span'))
            .click(Selector('#user_id_for_role'))
            .click(Selector('option').withText(user_update_name))
            .click(Selector('label').withText(role_update_name).find('[name="role[]"]'))
            .click(Selector('label').withText(role_name).find('[name="role[]"]'))
            .click(Selector('#user_role_assign'))
            //Check user role update test 
            .expect(Selector("#assign_role_message").child('div').innerText).contains('メッセージ: 設定しました')
            //.expect(Selector('label').withText(role_update_name).find('[name="role[]"]').checked).ok()
            
        })
        .after( async t => {
            /* test initialization code */
            console.log('role create');
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ロール管理'))
            .click(Selector('td').withText(role_name).nextSibling('td').find('.btn.btn-danger.role_delete'))
            .click(Selector('#delete_from_modal'))
            //Delete Permission Check
            .expect(Selector("#role_main_message").child('div').innerText).contains('ロールを削除しました') 
        })

        
        test('User Permission update Test', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ-権限'))
             //.click(Selector('a').withText('enhanced').find('span'))
            .click(Selector('#user_id_for_permission'))
            .click(Selector('option').withText(user_update_name))
            .click(Selector('label').withText('update_users').find('[name="permission[]"]'))
            .click(Selector('label').withText('update_permissions').find('[name="permission[]"]'))
            .click(Selector('#save_permission'))
            //Check user permission updaet test
            .expect(Selector("#assign_permission_message").child('div').innerText).contains('メッセージ: 成功')
         })


        test('user  Role  Delete Test', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ-ロール'))
            //.click(Selector('a').withText('all').find('span'))
            .click(Selector('#user_id_for_role'))
            .click(Selector('option').withText(user_update_name))
            .click(Selector('label').withText(role_update_name).find('[name="role[]"]'))
            .click(Selector('#user_role_assign'))
            //Check user role delete test
            .expect(Selector("#assign_role_message").child('div').innerText).contains('メッセージ: 設定しました')
            //.expect(Selector('label').withText(role_update_name).find('[name="role[]"]').checked).ok();
     
        })

        test('User Permission delete Test', async t => {
            await t
             .setTestSpeed(test_speed)
             .useRole(staff)
             .click(Selector('a').withText('ユーザ-権限'))
             //.click(Selector('a').withText('enhanced').find('span'))
            .click(Selector('#user_id_for_permission'))
            .click(Selector('option').withText(user_update_name))
            .click(Selector('label').withText('ban_users').find('[name="permission[]"]'))
            .click(Selector('label').withText('update_permissions').find('[name="permission[]"]'))
            .click(Selector('#save_permission'))
            //Check user permission updaet test
            .expect(Selector("#assign_permission_message").child('div').innerText).contains('メッセージ: 成功')
         })


         
        test('New Password Create', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ管理'))
            .click(Selector('td').withText(user_update_name).nextSibling('td').find('button').withText("パスワード変更"))
            //process.exit(1)
            .typeText(Selector('#new_password'), new_password)
            .typeText(Selector('#new_password_confirm'), new_password)
            .click(Selector('#change_password_save'))
            //Check new password create test
            .expect(Selector("#change_password_message").child('h3').innerText).contains('パスワード変更済み') 
            .click(Selector('#close_user'))
            .useRole(new_user)
            .expect(Selector('a').withText(user_update_name).innerText).contains(user_update_name)
        
        })


        test('Delete User', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ユーザ管理'))
            .click(Selector('td').withText(user_update_name).nextSibling('td').find('button').withText("削除"))
            .click(Selector('#delete_from_modal'))
            //Check delete user test 
            .expect(Selector("#user_main_message").child('div').innerText).contains('ユーザーを削除しました') 
            .expect(Selector('td').withText(user_update_name).exists).notOk()
        })

        test('Delete Role', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('ロール管理'))
            .click(Selector('td').withText(role_update_name).nextSibling('td').find('button').withText("削除"))
            //.expect(Selector('td').withText(role_update_name).exists).notOk()
            .click(Selector('#delete_from_modal'))
            //Delete Permission check
            .expect(Selector("#role_main_message").child('div').innerText).contains('ロールを削除しました')
            .expect(Selector('td').withText(role_update_name).exists).notOk()
        })

        test('Delete Permission', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('権限管理'))
            .click(Selector('td').withText(permission_update_name).nextSibling('td').find('.fas.fa-trash-alt'))
            .click(Selector('#delete_from_modal'))
            //Delete permission check
            .expect(Selector("#permission_main_message").child('div').innerText).contains('権限を削除しました')
            .expect(Selector('td').withText(permission_update_name).exists).notOk() 
        })

        test('Logout', async t => {
            await t
            .setTestSpeed(test_speed)
            .useRole(staff)
            .click(Selector('a').withText('Mayeen Uddin'))
            .click(Selector('a').withText('ログアウト'))
            .expect(Selector('#login').value).contains('ログイン')
        })