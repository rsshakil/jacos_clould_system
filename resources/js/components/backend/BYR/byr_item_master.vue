<template>
    <div class="row" v-can="['byr_view']">

                <div class="col-12 text-center">
 <label for="insertItemCategory" class="custom-file-upload" style="float:right;margin-right:15px;padding:6px 15px;">
                <b-icon
                  icon="upload"
                  animation="fade"
                  font-scale="1.2"
                ></b-icon>
                アップロード
              </label>
              <input
                type="file"
                ref="file"
                @change="onChangeFileUpload"
                id="file"
                class="form-control uploadBtn"
                style="display: none"
              />

                </div>
                <div class="col-12">
                    <div class="">
                        <table class="table table-striped table-bordered order_item_details_table">
                            <thead>

                                <tr>
                                    <th><input type="checkbox" @click="checkAll()" v-model="isCheckAll"></th>
                                    <th class="sorting" data-input_type="text" data-sorting_type="asc" data-column_name="vendor_items.name"
                                        style="cursor: pointer">{{myLang.product_name}} <span id="vendor_items_name_icon"></span></th>
                                    <th class="sorting" data-input_type="text" data-sorting_type="asc" data-column_name="jan"
                                        style="cursor: pointer">{{myLang.jan_code}} <span id="jan_icon"></span></th>
                                    <th class="sorting" data-input_type="text" data-sorting_type="asc" data-column_name="spec"
                                    style="cursor: pointer">{{myLang.standerd}} <span id="spec_icon"></span></th>
                                    <th class="sorting" data-input_type="text" data-sorting_type="asc" data-column_name="case_inputs"
                                    style="cursor: pointer">{{myLang.number_of_case}} <span id="case_inputs_icon"></span></th>
                                    <th class="sorting" data-input_type="text" data-sorting_type="asc" data-column_name="cost_price"
                                    style="cursor: pointer">{{myLang.cost_price}} <span id="cost_price_icon"></span></th>
                                    <th class="sorting" data-input_type="text" data-sorting_type="asc" data-column_name="shop_price"
                                    style="cursor: pointer">{{myLang.selling_price}} <span id="shop_price_icon"></span></th>
                                    <th class="sorting" data-input_type="select" data-sorting_type="asc" data-column_name="vendors.name"
                                    style="cursor: pointer">{{myLang.maker_name}} <span id="vendors_name_icon"></span></th>
                                    <th class="sorting" data-input_type="select" data-sorting_type="asc" data-column_name="c.category_name"
                                    style="cursor: pointer">{{myLang.category_name}} <span id="c_category_name_icon"></span></th>
                                    <th class="sorting" data-input_type="date" data-sorting_type="asc" data-column_name="start_date"
                                    style="cursor: pointer">{{myLang.start_date}} <span id="start_date_icon"></span></th>
                                    <th class="sorting" data-input_type="date" data-sorting_type="asc" data-column_name="end_date"
                                    style="cursor: pointer">{{myLang.end_date}} <span id="end_date_icon"></span></th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(item_list) in item_lists" :key="item_list.byr_item_id">
                                    <td><input type="checkbox" v-model="selected" :value="item_list.byr_item_id" @change="updateCheckall()"></td>
                                    <td>{{item_list.name_kana}}</td>
                                    <td>{{item_list.jan}}</td>
                                    <td>{{item_list.spec}}</td>
                                    <td>{{item_list.case_inputs}}</td>
                                    <td>{{item_list.cost_price}}</td>
                                    <td>{{item_list.shop_price}}</td>
                                    <td>{{item_list.maker_name_kana}}</td>
                                    <td>{{item_list.category_name}}</td>
                                    <td>{{item_list.start_date}}</td>
                                    <td>{{item_list.end_date}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</template>
<script>
export default {
  data() {
    return {
        'item_lists':{},
        'byr_buyer_lists':{},
        'file':'',
        'selected_byr':'OUK',
        selected:[],
        isCheckAll:false,
    };
  },
  methods: {
    checkAll(){

      this.isCheckAll = !this.isCheckAll;
      this.selected = [];
      var temp_seleted = [];
      if(this.isCheckAll){
          this.item_lists.forEach(function (item_list) {
            temp_seleted.push(item_list.byr_item_id);
          });
          this.selected = temp_seleted;
      }
    },
    updateCheckall(){
      if(this.selected.length == this.item_lists.length){
         this.isCheckAll = true;
      }else{
         this.isCheckAll = false;
      }
    },
    //get Table data
    get_all_master_item(){
        axios.get(this.BASE_URL +"api/get_all_master_item/"+Globals.user_info_id).then(({data}) => {
            this.item_lists = data.item_list;
            this.byr_buyer_lists = data.byr_buyer_list;
        });
    },
    check_byr_item_master_api(){
       let formData = new FormData();
    formData.append("up_file", this.file);
    formData.append("cmn_job_id", 9);
        axios({
    method: 'POST',
    url: this.BASE_URL + "api/item_master_exec",
    data: formData,
    headers: {'Content-Type': 'multipart/form-data' }
    })
    .then (({data})=> {
       Fire.$emit('LoadByrmasterItem');
    })
    .catch(function (response) {
        //handle error

    });
    },
    onChangeFileUpload(){
        this.file = this.$refs.file.files[0];
        this.check_byr_item_master_api();
      },
      selectedOption(option) {
      if (this.value) {
        return option.byr_buyer_id === this.value.byr_buyer_id;
      }
      return false;
    },
    change(e) {
      const selectedCode = e.target.value;
      const option = this.options.find((option) => {
        return selectedCode === option.byr_buyer_id;
      });
    //   this.$emit("input", option);
    }
  },

  created() {
    // Fire.$emit('permission_check_for_buyer',this.$session.get('byr_buyer_id'));
      this.get_all_master_item();
      Fire.$on("LoadByrmasterItem", () => {
      this.get_all_master_item();
    });
  },
  mounted() {
  }
};
</script>
