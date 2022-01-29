<?php

namespace Tests\Feature;


use  Artisan;
use App\User;
use App\users_details;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use DB;

class UserTest extends TestCase
{
    use WithoutMiddleware;

    // public function __construct() {
    //     parent::__construct();
    //     // Your construct here
    //     $this->success_message="Success";
    // }
    private $user_delete; 
    private $user_create_success;
    private $user_create_duplicated;
    private $user_create_invalid_name;
    private $user_create_invalid_email;
    private $user_create_invalid_password;
    private $user_user_update_invalid_fname;
    private $user_update_invalid_lname;
    private $user_update_invalid_full_name;
    private $user_update_invalid_email;
    private $user_update_invalid_phone;
    private $user_update_invalid_dob;
    private $user_update_invalid_image;
    private $user_update_invalid_postal;
    private $user_update_success;
    private $user_update_no_permission;
    private $user_update_duplicated;
    private $all_message;
    private $email;
    private $name;
    private $password;
    private $admin_user_id;
    private $email_update;
    private $fullname;
    private $firstname;
    private $lastname;
    private $phone;
    private $dob;
    private $postal;
    private $gender;
    /**
     * Act as a constructor
     *
     * @return initialized all variable
     */
    protected function setUp()
    {
        parent::setUp();
        // Artisan::call('migrate:refresh');
        // Artisan::call('db:seed');

        $last_data=User::orderBy('id', 'ASC')->first();
        $user_id=$last_data['id'];

        $this->admin_user_id = $user_id;
        $this->user_delete = __('messages.user_deleted');
        $this->user_create_success = 'success';
        $this->user_create_duplicated = 'invalid';
        $this->user_create_invalid_name = 'name_required';
        $this->user_create_invalid_email = 'email_required';
        $this->user_create_invalid_password = 'pass_required';
        $this->user_user_update_invalid_fname = 'fname_required';
        $this->user_update_invalid_lname = 'lname_required';
        $this->user_update_invalid_full_name = 'full_name_required';
        $this->user_update_invalid_email = 'email_required';
        $this->user_update_invalid_phone = 'phone_required';
        $this->user_update_invalid_dob = 'dob_required';
        $this->user_update_invalid_image = 'image_required';
        $this->user_update_invalid_postal = 'postal_required';
        $this->user_update_success = 'success';
        $this->user_update_no_permission = 'no_permission';
        $this->user_update_duplicated = 'success';
        $this->all_message = 'message';
        $this->email = "test@gmail.com";
        $this->email_update = "updatetest@gmail.com";
        $this->name = "Admin";
        $this->password = "123456";
        $this->fullname = "Full name";
        $this->firstname = "First Name";
        $this->lastname = "Last Name";
        $this->phone = "01670514306";
        $this->postal = "01670514306";
        $this->dob = "1992-01-01";
        $this->gender = "m";
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
     * Create user success test.
     *
     * @return void
     */

    public function test_create_user_success()
    {
         if( User::where('email',$this->email)->exists()){
             User::where('email',$this->email)->delete();
         }
        $response = $this->json('POST', '/user_create',
        [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_create_success,
            ]);
    }

    /**
     * Create user duplicated test.
     * 
     * @return void
     */

    
    public function test_create_user_duplicated()
    {
        $last_data=User::latest()->first();
            $response = $this->json('POST', 
            '/user_create', 
            [
                'name' => $this->fullname, 
                'email' => $last_data['email'], 
                'password' => $this->password]);
            $response
                ->assertStatus(200)
                ->assertJson([
                    $this->all_message => $this->user_create_duplicated,
                ]);
    }
    /**
     * Create user name length invalid test.
     *
     * @return void
     */

 
    public function test_create_user_invalid_name()
    {
        $response = $this->json('POST', '/user_create',
            [
            'name' => str_repeat('a', 51), 
            'email' =>   $this->email,
            'password' => $this->password]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_create_invalid_name,
            ]);
    }

    /**
     * Create user email invalid test.
     *
     * @return void
     */
    public function test_create_user_invalid_email()
    {

        $response = $this->json('POST', '/user_create',
        [
        'name' => $this->name,
        'email' => 'test' . rand(1, 10), 
        'password' => $this->password]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_create_invalid_email,
            ]);
    }
    /**
     * Create user password length invalid test.
     *
     * @return void
     */
    public function test_create_user_invalid_password()
    {

        $response = $this->json('POST', '/user_create',
        ['name' => $this->name, 
        'email' => $this->email,
        'password' => '1234']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_create_invalid_password,
            ]);
    }
    /**
     * Update user first name length invalid test.
     *
     * @return void
     */


    public function test_user_update_invalid_fname()
    {
        Storage::fake('photos');
        $response = $this->json('POST', 'update_user', [
            'id' =>         $this->admin_user_id,
            'full_name' =>  $this->fullname,
            'email' =>      $this->email,
            'f_name' => str_repeat('First Name', 21),
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_user_update_invalid_fname,
            ]);
    }
    /**
     * Update user last name length invalid test.
     *
     * @return void
     */
    public function test_user_update_invalid_lname()
    {
        Storage::fake('photos');
        $response = $this->json('POST', '/update_user', [
            'id' => $this->admin_user_id,
            'full_name' =>  $this->fullname,
            'email' => $this->email,
            'f_name' =>  $this->firstname,
            'l_name' => str_repeat('Last Name', 21),
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_update_invalid_lname,
            ]);
    }
    /**
     * Update user fullname length invalid test.
     *
     * @return void
     */
    public function test_user_update_invalid_full_name()
    {
        Storage::fake('photos');
        $response = $this->json('POST', '/update_user', [
            'id' => $this->admin_user_id,
            'full_name' => str_repeat('Full Name', 51),
            'email' => $this->email,
            'f_name' =>  $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_update_invalid_full_name,
            ]);
    }
    /**
     * Update user email invalid test.
     *
     * @return void
     */
    public function test_user_update_invalid_email()
    {
        Storage::fake('photos');
        $response = $this->json('POST', '/update_user', [
            'id' => $this->admin_user_id,
            'full_name' => $this->fullname,
            'email' => 'mayeennbd',
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_update_invalid_email,
            ]);
    }
    /**
     * Update user phone number invalid test.
     *
     * @return void
     */
    public function test_user_update_invalid_phone()
    {
        Storage::fake('photos');
        $response = $this->json('POST', '/update_user', [
            'id' => $this->admin_user_id,
            'full_name' => $this->fullname,
            'email' => $this->email,
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => str_repeat(01670514306, 10),
            'dob' => $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_update_invalid_phone,
            ]);
    }
    /**
     * Update user date of birth format invalid test.
     *
     * @return void
     */
    public function test_user_update_invalid_dob()
    {
        Storage::fake('photos');
        $response = $this->json('POST', '/update_user', [
            'id' => $this->admin_user_id,
            'full_name' => $this->name,
            'email' => $this->email,
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' => '1993',
            'gender' => $this->gender,
            'postal_code' =>$this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_update_invalid_dob,
            ]);
    }
    /**
     * Update user image size invalid test.
     *
     * @return void
     */
    public function test_user_update_invalid_image()
    {
        Storage::fake('photos');
        $response = $this->json('POST', '/update_user', [
            'id' => $this->admin_user_id,
            'full_name' => $this->fullname,
            'email' => $this->email,
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.docs')->size(1200),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_update_invalid_image,
            ]);
    }
    /**
     * Update user postal code length invalid test.
     *
     * @return void
     */
    public function test_user_update_invalid_postal()
    {
        Storage::fake('photos');
        $response = $this->json('POST', '/update_user', [
            'id' => $this->admin_user_id,
            'full_name' => $this->fullname,
            'email' => $this->email,
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => str_repeat(1207, 21),
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                $this->all_message => $this->user_update_invalid_postal,
            ]);
    }
    /**
     * Update user success test.
     *
     * @return void
     */
    public function test_user_update_success()
    {
        // User login for test
        $user = new User();
        $user->id = $this->admin_user_id;
        $this->be($user);

        if(User::where('email',$this->email_update)->exists()){
            User::where('email',$this->email_update)->delete();
        }
        
        $new_user = factory(\App\User::class)->create();
       
        $new_user_id=$new_user->id;
        users_details::insert(['users_id'=>$new_user_id]);

        \Log::info('User_id Test'.$new_user_id);
        Storage::fake('photos');
        $response =
        $this->json('POST', '/update_user', [
            'id' => $new_user_id,
            'full_name' => $this->fullname,
            'email' => $this->email_update,
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg')->size(100),
        ]);
// return dd($response);
        $response
            ->assertStatus(200)
            ->assertJson([
                $this->all_message => $this->user_update_success,
            ]);



    }

    /**
     * Update user success test.
     *
     * @return void
     */
    public function test_user_update_own_success()
    {
        //User login for test
        $new_user = factory(\App\User::class)->create();
        $new_user_id=$new_user->id;
        users_details::insert(['users_id'=>$new_user_id]);
        $user = new User();
        $user->id = $new_user_id;
        $this->be($user);
        if(User::where('email',$this->email_update)->exists()){
            User::where('email',$this->email_update)->delete();
        }
        Storage::fake('photos');
        $response =
        $this->json('POST', '/update_user', [
            'id' => '553456382u6hsdgh',
            'full_name' => $this->fullname,
            'email' => $this->email_update,
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg')->size(100),
        ]);
        $response
            ->assertStatus(200)
            ->assertJson([
                $this->all_message => $this->user_update_success,
            ]);
    }




    /**
     * Update user no permission invalid test.
     *
     * @return void
     */
    public function test_user_update_no_permission()
    {
        // User login for test
        $user = new User();
        $user->id = 13;
        $this->be($user);

        Storage::fake('photos');
        $response =
        $this->json('POST', '/update_user', [
            'id' => 1,
            'full_name' => $this->fullname,
            'email' => $this->email,
            'f_name' => $this->firstname,
            'l_name' => $this->lastname,
            'phone' => $this->phone,
            'dob' =>  $this->dob,
            'gender' => $this->gender,
            'postal_code' => $this->postal,
            'image' => UploadedFile::fake()->image('photo1.jpg'),
        ]);
        $response
            ->assertStatus(200)
            ->assertJson([
                $this->all_message => $this->user_update_no_permission,
            ]);
    }

     /**
     * User delete success test.
     *
     * @return void
     */
    public function test_user_delete_success()
    {
        $last_data=User::where('email', $this->email_update)->first();
        $user_id=$last_data['id'];
        $response = $this->get('user_delete/'.$user_id);
        $response
            ->assertStatus(200)
            ->assertJson([
                $this->all_message => $this->user_delete,
            ]);
    }
    
}
