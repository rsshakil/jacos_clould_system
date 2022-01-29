<?php
 
namespace Tests\Feature;
 
use App\Traits\CanAssertFlash;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
 
class RoleTest extends TestCase
{
    use WithoutMiddleware;
    // use RefreshDatabase;
    use CanAssertFlash;
    private $create_role_success;
    private $create_role_duplicated;
    private $create_role_invalid; 
    private $role_delete_success;
    private $role_delete_unsuccess;
    private $role_update_success;
    private $role_update_duplicated;
    private $assign_role_user;
    private $all_message;
    private $role_name;
    private $role_description;
    private $role_update_name;
    /**
     * Act as a constructor
     *
     * @return initialized all variable
     */
    protected function setUp()
    {
        parent::setUp();

        $this->create_role_success = __('messages.role_setup_completed');
        $this->create_role_duplicated = __('messages.role_duplicated');
        $this->create_role_invalid =  __('messages.role_name_required');
        $this->role_delete_success = __('messages.role_deleted');
        $this->role_delete_unsuccess = __('messages.role_not_deleted');
        $this->role_update_success = __('messages.role_updated');
        $this->role_update_duplicated = __('messages.role_updated');
        $this->assign_role_user = 'Success';
        $this->all_message = 'message';
        $this->role_name = 'Test Role';
        $this->role_description = 'Role Description';
        $this->role_update_name = 'Test Role Update';
 
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
     * Create role success test.
     *
     * @return void
     */
    public function test_create_role_success()
    {
        if(Role::where('name',$this->role_name)->exists()){
            Role::where('name',$this->role_name)->delete();
        }
        $response = $this->json(
            'POST', '/role_insert',
            ['role_update_id' => '', 
            'role' => $this->role_name,
            'permissions' => ['1', '2'],
            'role_description' => $this->role_description]);
        // $obj=$response->getOriginalContent();
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->create_role_success);
    }

    /**
    * Create role duplicated test.
    *
    * @return void
    */

    public function test_create_role_duplicated()
    {
        $roel_name_nfo = Role::select('name')->where('name',  $this->role_name)->first();
        $roel_name = $roel_name_nfo['name'];
        $response = $this->json('POST', '/role_insert', 
        ['role_update_id' => '', 
        'role' => $roel_name, 
        'permissions' => ['1', '2'], 
        'role_description' => $this->role_description]);
        
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->create_role_duplicated);
    }
    /**
     * Create role name blunk test.
     *
     * @return void
     */
    public function test_create_role_invalid()
    {
        $response = $this->json('POST', '/role_insert',
        [
            'role_update_id' => '',
            'role' => '', 
            'permissions' => ['1', '2'], 
            'role_description' => $this->role_description]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->create_role_invalid);
    }
    
    /**
     * Update role success test.
     *
     * @return void
     */
    public function test_role_update_success()
    {
        if(Role::where('name',$this->role_update_name)->exists()){
           Role::where('name',$this->role_update_name)->delete();
        }
        $new_role = factory(Role::class)->create();
        $role_id=$new_role->id;
        $response = $this->json('POST', '/role_insert', 
        [
            'role_update_id' => $role_id, 
            'role' => $this->role_update_name, 
            'permissions' => ['1', '2', '3', '4','5','6','7','8','9','10','11','12','13'], 
            'role_description' => $this->role_description]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->role_update_success);
    }
    /**
     * Update role duplicated test.
     *
     * @return void
     */
    public function test_role_update_duplicated()
    {
        $role_last_id = Role::orderBy('id', 'DESC')->first();
        $role_id = $role_last_id['id'];
        $roel_name_nfo = Role::select('name')->where('name', $this->role_update_name)->first();
        $roel_name = $roel_name_nfo['name'];
        $response = $this->json('POST', '/role_insert', 
        [
            'role_update_id' => $role_id, 
            'role' => $roel_name, 
            'permissions' => ['1', '2'], 
            'role_description' => $this->role_description]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHas($this->all_message, $this->role_update_duplicated);
    }
    /**
     * Delete role unsuccess where is_system is 1 test.
     *
     * @return void
     */

    /**
     * Assign role to user test.
     *
     * @return void
     */
    public function test_assign_role_user()
    {
        $response = $this->json('POST', '/assign_model_role', ['user_id' => 1, 'roles' => ['1']]);
        $response
            ->assertStatus(200)
            ->assertJson([
                $this->all_message => $this->assign_role_user,
            ]);
    }

     /**
     * Delete role success test.
     *
     * @return void
     */
    public function test_role_delete_success()
    {
        $role_id =  Role::where('name',$this->role_name)->first();
        $r_id = $role_id['id'];
        Role::where('id',$r_id)->update(['is_system'=>0]);
        $response = $this->get('role_delete/' . $r_id);
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertJson([$this->all_message => $this->role_delete_success]);
    }
    
    public function test_role_delete_unsuccess()
    {
        $not_deleteable_role = Role::where('is_system', 1)->get();
        $roles = json_decode($not_deleteable_role);
        if (!empty($roles)) {
            $response = $this->get('role_delete/' . $not_deleteable_role[0]['id']);
    
            $this->assertEquals(200, $response->getStatusCode());
            $response->assertJson([$this->all_message => $this->role_delete_unsuccess]);
        }
    }
 
}