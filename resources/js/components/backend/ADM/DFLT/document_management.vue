<template>
  <div>
    <div class="row">
                <div class="col-12">
                   <button @click="zip_create_modal" class="btn pull-right text-right btn-primary" style="float:right">{{myLang.add_new}}</button>
                </div>



                <div class="col-12">
                    <div class="">
                       <table class="table table-striped table-bordered order_item_details_table">
                            <thead>

                                <tr>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer">No <span id="id_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="name" style="cursor: pointer">{{myLang.title}}<span id="orderdate_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">{{myLang.update_date}}<span id="delivery_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">Download<span id="delivery_icon"></span></th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-if="Object.keys(file_lists).length!=0">
                                    <td>1</td>
                                    <td>{{file_lists.file_name}}</td>
                                    <td>{{file_lists.file_create_date | ja_date_time}}</td>
                                    <td><a :href="file_lists.download_file_url">Download</a></td>


                                </tr>
                                <tr v-else>
                                  <td colspan="4" class="text-center">No File Found</td>
                                 </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

          <b-modal
      size="md"
      :hide-backdrop="true"
      title="Upload a Zip"
      :ok-title="myLang.save"
      :cancel-title="myLang.cancel"
      @ok.prevent="file_update()"
      v-model="usermanual_create_modal" :no-enforce-focus="true">
      <div class="panel-body add_item_body">
        <form enctype="multipart/form-data">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-6 col-form-label">Upload User Manual ZIP File</label>
                <div class="col-sm-6">
                <input type="file" name="zip_file" class="form-control" @change="onUploadFiles" accept=".zip">
                <!-- <input type="file" name="zip_file" class="form-control" :class="{ 'is-invalid': form.errors.has('zip_file') }" @change="onUploadFiles" accept=".zip">
                <has-error :form="form" field="zip_file"></has-error> -->
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
import Form from 'vform'
export default {

  data() {
    return {
        'file_lists':{},
        'usermanual_create_modal':false,
        formData:new FormData(),
    };
  },
  methods: {
    file_update(){
        let loaderrr = Vue.$loading.show();
            axios.post(this.BASE_URL + "api/zip_file_update",this.formData)
                .then(({data}) => {
                  this.usermanual_create_modal = false;
                    Fire.$emit('AfterCreate');
                    this.alert_text=""
                    this.alert_icon="success"
                    this.alert_title="document updated"
                    this.sweet_normal_alert();
                    loaderrr.hide();
                }).catch((error) => {
                // console.log(error);
                this.alert_title="Error"
                this.alert_icon="error"
                this.alert_text = "May be zip file invalid or Check you connection";
                this.sweet_advance_alert();
                loaderrr.hide();
        });
    },
    onUploadFiles(e){
        let loaderrr = Vue.$loading.show();
        let file = e.target.files[0];
        // console.log(file);
        if(file['type'] !="application/x-zip-compressed"){
            this.alert_text='File type must be zip';
            this.alert_icon='warning';
            this.alert_title= 'File Type';
            this.sweet_normal_alert();
            e.target.value = '';
            return 0;
        }
        // 21117750000
        // if(file['size'] >2097152 ){
        //     this.alert_text='File size can not be bigger than 2 MB';
        //     this.alert_icon='warning';
        //     this.alert_title= 'File Size';
        //     this.sweet_normal_alert();
        //     e.target.value = '';
        //     return 0;
        // }
        loaderrr.hide();
        this.formData.append("zip_file", file);
    },
       get_user_manual(){
        axios.post(this.BASE_URL +"api/getUserManual").then(({data}) => {
            this.file_lists = data.file_list;
        });
    },
    zip_create_modal(){
    //   this.form.reset();
      this.usermanual_create_modal = true;
    },
  },

  created() {
      this.get_user_manual();
      Fire.$on("AfterCreate", () => {
        this.get_user_manual();
    });
    Fire.$emit("loadPageTitle", "Document Management");
  },
  mounted() {

  }
};
</script>
