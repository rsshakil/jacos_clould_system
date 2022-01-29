<template>
  <div>
    <div class="row">
                <div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">{{myLang.super_value_head}}</h4>
                </div>
                <div class="col-3"></div>
                <div class="col-6">
                    <!-- <tabList></tabList> -->
                </div>
                <div class="col-3"></div>

                <div class="col-7">
                    <div class="">
                      <form>
  <div class="form-group row">
    <label for="buyer_name" class="col-sm-4 col-form-label">{{myLang.buyer_name}}</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="buyer_name" value="スーパーバリュー">
    </div>
  </div>
  <div class="form-group row">
    <label for="jan_code" class="col-sm-4 col-form-label">{{myLang.jan_code}}</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="jan_code" value="000001">
    </div>
  </div>
  <div class="form-group row">
    <label for="super_code" class="col-sm-4 col-form-label">{{myLang.super_code}}</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="super_code" value="JHG">
    </div>
  </div>
  <div class="form-group row">
    <label for="postal_code" class="col-sm-4 col-form-label">{{myLang.postal_code}}</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="postal_code" value="141-0021">
    </div>
  </div>
  <div class="form-group row">
    <label for="street" class="col-sm-4 col-form-label">{{myLang.street}}</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="street" value="東京都品川区上大崎1-2-3">
    </div>
  </div>
</form>
                    </div>
                </div>
                <div class="col-5"></div>
                <div class="col-12">
                    <button class="btn btn-danger" @click="delete_company">{{myLang.delete}}</button>
                    <button class="btn btn-info" @click="delete_company">{{myLang.cancel}}</button>
                    <button class="btn btn-primary" @click="delete_company">{{myLang.save_changes}}</button>
                </div>
            </div>
  </div>
</template>
<script>
// import tabList from "../CMN/tabList";
export default {
name:'app',
components:{
// tabList,
},

  data() {
    return {
        'company_lists':{},
    };
  },
  methods: {
      delete_company(){

      },
       get_all_buyer(){
        axios.get(this.BASE_URL +"api/get_all_buyer").then(({data}) => {
            this.company_lists = data.company_list;
            // console.log(this.company_lists);
        });
    },
  },

  created() {
      this.get_all_buyer();

  },
  mounted() {

  }
};
</script>
