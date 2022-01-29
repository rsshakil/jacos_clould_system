<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\users_details;

class PermissionTest extends TestCase
{
    use WithoutMiddleware;
    private $create_permission_success;
    private $create_permission_duplicated;
    private $create_permission_invalid;
    private $delete_permission_success;
    private $permission_update_success;
    private $permission_update_duplicated;
    private $assign_permission_user;
    private $permission_delete_label;
    private $all_message;
    private $permission_name;
    private $permission_description;
    private $permission_name_update;
    private $email_update;
    /**
     * Act as a constructor
     *
     * @return initialized all variable
     */
    protected function setUp()
    {
        parent::setUp();

        $this->create_permission_success = __('messages.permission_setup_completed');
        $this->create_permission_duplicated = __('messages.permission_name_duplicate');
        $this->create_permission_invalid = __('messages.permission_name_blank');
        $this->delete_permission_success = __('messages.permission_deleted');
        $this->permission_update_success = __('messages.permission_updated');
        $this->permission_update_duplicated = __('messages.permission_updated');
        $this->assign_permission_user = 'Success';
        $this->permission_name = "Admin";
        $this->permission_name_update = "Admin Update";
        $this->permission_description = "permission description";
        $this->permission_delete_label = 'delete_message';
        $this->all_message = 'message';
        $this->email_update = "updatetest@gmail.com";

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
    /**
     * Create permission success test.
     *
     * @return void
     */
    public function test_create_permission_success()
    {
        if(Permission::where('name',$this->permission_name)->exists()){
            Permission::where('name',$this->permission_name)->delete();
        }

        $response = $this->json('POST', '/permission_insert',
        [
            'permission' => $this->permission_name, 
            'permission_description' =>  $this->permission_description,
            'is_system' => 0
            ]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->create_permission_success);
    }

    /**
     * Create permission duplicated test.
     *
     * @return void
     */
    public function test_create_permission_duplicated()
    {

        $permission_name_nfo = Permission::select('name')->where('name', $this->permission_name )->first();
        $permission_name = $permission_name_nfo['name'];
        $response = $this->json('POST', '/permission_insert', 
        [
            'permission' => $permission_name,
            'permission_description' => $this->permission_description]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->create_permission_duplicated);
        \Log::info('Permission duplicated test passes');
    }
    /**
     * Create permission name blunk test.
     *
     * @return void
     */
    public function test_create_permission_invalid()
    {
        $response = $this->json('POST', '/permission_insert', 
        [
            'permission' => '', 
            'permission_description' => $this->permission_description]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, __('messages.permission_name_blank'));
    }
    
    public function test_permission_update_success()
    {
        if(Permission::where('name',$this->permission_name_update)->exists()){
           Permission::where('name',$this->permission_name_update)->delete();
        }
        $new_permission = factory(Permission::class)->create();
        $permission_id=$new_permission->id;
        $response = $this->json('POST', '/permission_insert',
        [
            'permission_update_id' => $permission_id, 
            'permission' => $this->permission_name_update,  
            'permission_descr' => $this->permission_description,]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->permission_update_success);
    }
    /**
     * Update permission duplicated test.
     *
     * @return void
     */
    public function test_permission_update_duplicated()
    {
        $last_data = Permission::orderBy('id', 'DESC')->first();
        $user_id = $last_data['id'];

        $permission_name_nfo = Permission::select('name')->where('name', $this->permission_name_update)->first();
        $permission_namee = $permission_name_nfo['name'];
        $response = $this->json('POST', '/permission_insert', 
        [
            'permission_update_id' => $user_id, 
            'permission' => $permission_namee, 
            'permission_descr' =>  $this->permission_description]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->permission_update_duplicated);
    }
    /**
     * Assign permissions to an user test.
     *
     * @return void
     */
    public function test_assign_permission_user()
    {
        $new_user = factory(\App\User::class)->create();
        $response = $this->json('POST', '/assign_permission_model', 
        [
            'user_id' => $new_user->id,
            'permission' => ['1', '3']]);
        $response
            ->assertStatus(200)
            ->assertJson([
                $this->all_message => $this->assign_permission_user,
            ]);
    }

    /**
     * Delete permission success test.
     *
     * @return void
     */
    public function test_delete_permission_success()
    {
        $p_data = Permission::where('name', $this->permission_name)->first();
        $p_id = $p_data['id'];
        permission::where('id',$p_id)->update(['is_system'=>0]);
        $response = $this->get('permission_delete/' . $p_id);
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertJson([$this->all_message => $this->delete_permission_success]);
    }
    /**
     * Update permission success test.
     *
     * @return void
     */

}
