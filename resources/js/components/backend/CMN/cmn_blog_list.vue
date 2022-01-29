<template>
  <div>
    <div class="row">
                <div class="col-12">
                   <button @click="new_blog_create_modal" class="btn pull-right text-right btn-primary" style="float:right">{{myLang.add_new}}</button>
                </div>



                <div class="col-12">
                    <div class="">
                       <table class="table table-striped table-bordered order_item_details_table">
                            <thead>

                                <tr>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer">No <span id="id_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="name" style="cursor: pointer">{{myLang.title}}<span id="orderdate_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">{{myLang.image}}<span id="delivery_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">{{myLang.update_date}}<span id="delivery_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">{{myLang.operation}}<span id="btn1_icon"></span></th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in blog_lists" :key="value.cmn_blog_id">
                                    <td>{{index+1}}</td>
                                    <td>{{value.blog_title}}</td>
                                    <td><img v-if="value.feature_img!=null" :src="BASE_URL+'storage/app/public/backend/images/blog_images/'+value.feature_img" alt="No image" class="img-responsive img-thumbnail" width="150" height="100" style="border: 1px solid gray;"></td>
                                    <td>{{value.updated_at | ja_date_time}}</td>
                                    <td><b-icon v-if="value.blog_status=='published'" v-tooltip.html="'無効'" font-scale="2" style="cursor:pointer" icon="eye-fill" variant="success" class="custom_blog_font" @click="blog_update_info(value,0)"></b-icon>
                                    <b-icon v-if="value.blog_status=='unpublished'" v-tooltip.html="'有効'" font-scale="2" style="cursor:pointer" icon="eye-slash-fill" variant="danger" class="custom_blog_font" @click="blog_update_info(value,1)"></b-icon>

                                    <b-icon icon="arrow-bar-up" v-tooltip.html="'お知らせ表示'" font-scale="2" style="cursor:pointer" variant="primary" class="custom_blog_font" @click="blog_update_info(value,2)"></b-icon>
                                    <b-icon icon="trash-fill" v-tooltip.html="'削除'" font-scale="2" style="cursor:pointer" class="custom_blog_font" @click="blog_update_info(value,3)" variant="danger"></b-icon>
                                    <b-icon icon="file-earmark-code" v-tooltip.html="'更新'" font-scale="2" style="cursor:pointer" variant="success" class="custom_blog_font" @click="blog_update_info(value,4)"></b-icon>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

          <b-modal
      size="lg"
      :hide-backdrop="true"
      title="新規　お知らせ"
      :ok-title="myLang.save"
      :cancel-title="myLang.cancel"
      @ok.prevent="create_new_blog()"
      v-model="blog_create_modal" :no-enforce-focus="true">
      <div class="panel-body add_item_body">
        <form enctype="multipart/form-data">
        <input type="hidden" v-model="form.cmn_blog_id">
          <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">{{myLang.title}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('blog_title') }" v-model="form.blog_title">
      <has-error :form="form" field="blog_title"></has-error>
    </div>
  </div>
  <div class="form-group row">
    <label for="staticEmail" class="col-sm-2 col-form-label">{{myLang.image}}</label>
    <div class="col-sm-10">
      <input type="file" name="feature_img" class="form-control" @change="onUploadFiles" accept="image/jpeg, image/png">
    <!--<has-error :form="form" field="feature_img"></has-error>-->
    <img v-if="form.feature_img!=null && form.feature_img" class="profile-user-img img-fluid img-circle" :src="getPhoto()" alt="Blog Images">
    </div>
  </div>
  <div class="form-group row">
    <label for="staticEmail" class="col-sm-2 col-form-label">{{myLang.contents}}</label>
    <div class="col-sm-10">
     <ckeditor :editor="editor" v-model="form.blog_content" :config="editorConfig" :class="{ 'is-invalid': form.errors.has('blog_content') }"></ckeditor>
    <has-error :form="form" field="blog_content"></has-error>
    </div>
  </div>
        </form>
      </div>
      <!-- </div>
        </div>
      </div>-->
    </b-modal>


  </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// import ClassicEditor from '@ckeditor/ckeditor5-editor-classic';
import '@ckeditor/ckeditor5-build-classic/build/translations/ja';
import UploadAdapter from '../../../UploadAdapter';
// import MediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed';
import Form from 'vform'
export default {

  data() {
    return {
        'blog_lists':{},
        'blog_create_modal':false,
        editor: ClassicEditor,

        editorConfig: {
            // The configuration of the editor.
            // language: "ja",
            // language: this.myLang.editor_lang,
            extraPlugins: [ this.uploader ],

        },
        form: new Form({
                    blog_title : '',
                    feature_img: '',
                    blog_content: '',
                    cmn_blog_id: '',
                    blog_by:Globals.user_info_id,
                    cmn_company_id:Globals.cmn_company_id

                })
    };
  },
  methods: {
    uploader(editor)
            {
                editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                    return new UploadAdapter( loader,Globals.base_url + "api/ckeditor_file_up" );
                };
            },
    blog_update_info(blog,action_type){
      if(action_type==4){
        this.blog_create_modal = true;
        this.form.fill(blog);
      }else if(action_type==3){
      this.delete_sweet().then((value)=>{

        if(value.isConfirmed){
          this.blog_update(blog,action_type);
        }
      })

      }else{
        this.blog_update(blog,action_type);
      }
    },
    blog_update(blog,action_type){
      var post_data = {
                blog: blog,
                action_type: action_type,
            };
            axios.post(
                    this.BASE_URL + "api/update_blog_infos",
                    post_data
                )
                .then(({data}) => {
                    Fire.$emit('AfterCreateblog');
                     var alert_text='';
                    if(action_type==0){
                      var alert_icon='warning';
                      var alert_title= 'お知らせを無効化しました';
                    }else if(action_type==1){
                      var alert_icon='success';
                      var alert_title= 'お知らせを有効化しました';
                    }else if(action_type==2){
                      var alert_icon='success';
                      var alert_title= 'お知らせ表示を変更しました';
                    }else{
                      var alert_icon='success';
                      var alert_title= 'お知らせを削除しました';
                    }
                    Swal.fire({
                icon: alert_icon,
                title: alert_title,
                text: alert_text
            });
                });
    },
    onUploadFiles(e){
      let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                    //console.log('RESULT', reader.result)
                     this.form.feature_img = reader.result;
                    }
                     reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
    },
    getPhoto(){
               let photo = (this.form.feature_img.length > 100) ? this.form.feature_img : this.BASE_URL+'storage/app/public/backend/images/blog_images/'+ this.form.feature_img;
                return photo;
            },
       get_all_blogs(){
        axios.get(this.BASE_URL +"api/get_all_blog_list").then(({data}) => {
            this.blog_lists = data.blog_list;
        });
    },
    new_blog_create_modal(){
      this.form.reset();
      this.form.cmn_blog_id ='';
      this.blog_create_modal = true;


    },
     create_new_blog(){
      this.form.post(this.BASE_URL +'api/blog_create')
                .then((data)=>{
                  this.blog_create_modal = false;
                    Fire.$emit('AfterCreateblog');
                    Swal.fire({
            icon: 'success',
            title: 'お知らせ登録完了',
            text: ''
        });

                })
                .catch((error)=>{
                  console.log(error);
                  console.log(this.form.errors);
                  Swal.fire({
            icon: 'warning',
            title: 'お知らせ登録エラー',
            text: 'お知らせ内容を確認してください'
        });
                })
    },
  },

  created() {
      this.get_all_blogs();
      Fire.$on("AfterCreateblog", () => {
        this.get_all_blogs();
    });
    if(Globals.global_user_type=='BYR' || Globals.global_user_type=='SLR'){
      this.form.cmn_company_id = Globals.cmn_company_id;
    }else{
      this.form.cmn_company_id = 0;
    }
    Fire.$emit("loadPageTitle", "お知らせ一覧");
  },
  mounted() {
    this.editorConfig.language=this.myLang.editor_lang;

  }
};
</script>
