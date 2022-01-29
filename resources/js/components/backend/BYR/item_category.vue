<template>
  <div v-can="['byr_view']">
    <div class="row">


      <div class="col-12">
      <div class="row"><div class="col-2"></div><div class="col-8">

<div class="searchCategoryForm row">
<div class="col-6">
<label class="sr-only" for="inline-form-input-category_code">category_code</label>
    <b-input-group prepend="分類コード" class="mb-2 mr-sm-2 customPrependColor mb-sm-0">
      <b-form-input id="inline-form-input-category_code" placeholder="分類コード"></b-form-input>
    </b-input-group>
    </div>
    <div class="col-6">
    <label class="sr-only" for="inline-form-input-category_name">category_name</label>
    <b-input-group prepend="分類名" class="mb-2 mr-sm-2 customPrependColor mb-sm-0">
      <b-form-input id="inline-form-input-category_name" placeholder="分類名"></b-form-input>
    </b-input-group>
    </div>
</div>

<div class="searchBtn text-center">
  <button class="btn btn-primary"> {{ myLang.search }}</button>
</div>
</div><div class="col-2"></div></div>
<p>
              <span class="tableRowsInfo"
                >{{ cat_lists.from }}〜{{
                  cat_lists.to
                }}
                件表示中／全：{{ cat_lists.total }}件</span
              >
              <span class="pagi">
                <advanced-laravel-vue-paginate
                  :data="cat_lists"
                  :onEachSide="2"
                  previousText="<"
                  nextText=">"
                  alignment="center"
                  @paginateTo="get_all_cat"
                />
              </span>
              <span class="selectPagi">
                <select
                  @change="selectNumPerPage"
                  v-model="select_field_per_page_num"
                  class="form-control selectPage"
                >
                  <!--<option value="0">表示行数</option>
                  <option v-for="n in order_detail_lists.last_page" :key="n"
                :value="n">{{n}}</option>-->
                  <option value="10">10行</option>
                  <option value="20">20行</option>
                  <option value="50">50行</option>
                  <option value="100">100行</option>
                </select>
              </span>
            </p>


        <div class="">
          <table class="table table-striped table-bordered data_table">
            <thead>
              <tr>
                <th colspan="100%" style="border: none">
                  <div class="row">
                    <div class="col-6">
                      <form class="form-inline">
                        <!--<input class="form-control" type="text" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-primary">検索</button>-->
                      </form>
                    </div>
                    <div class="col-6">
                      <button
                        @click="add_new_category_cmn"
                        class="btn custom_right btn-primary"
                      >
                        新規分類追加
                      </button>

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
                @change="insertItemCategory"
                id="insertItemCategory"
                class="form-control uploadBtn"
                style="display: none"
              />
              <!-- <button class="btn btn-primary active" type="button">
                <b-icon
                  icon="upload"
                  animation="fade"
                  font-scale="1.2"
                ></b-icon>
                アップロード
              </button>-->
                    </div>
                  </div>
                </th>
              </tr>
              <tr>
                <th rowspan="2" style="cursor: pointer">No</th>
                <th rowspan="2" style="cursor: pointer">分類コード</th>
                <th  colspan="2" style="cursor: pointer">大分類</th>
                <th  colspan="2" style="cursor: pointer">中分類</th>
                <th  colspan="2" style="cursor: pointer">小分類</th>
                <th rowspan="2" style="cursor: pointer">{{ myLang.details }}</th>
              </tr>
              <tr>
                <th>分類コード</th>
                <th>分類名</th>
                <th>分類コード</th>
                <th>分類名</th>
                 <th>分類コード</th>
                <th>分類名</th>
              </tr>

            </thead>
            <tbody>
              <tr
                v-for="(cat_list, index) in (cat_lists.data)"
                :key="index"
              >
                <td>{{
                    cat_lists.current_page*select_field_per_page_num-select_field_per_page_num+index+1
                  }}</td>
                  <td>{{ cat_list.category_full_code }}</td>
                  <td>{{ cat_list.m_code }}</td>
                <td>{{ cat_list.m_name }}</td>

                <td>{{ cat_list.sm_code }}</td>
                <td>{{ cat_list.sm_name }}</td>

                <td>{{ cat_list.mm_code }}</td>
                <td>{{ cat_list.mm_name }}</td>

                <td>
                  <button
                    @click="edit_category_data(cat_list)"
                    class="btn btn-primary"
                  >
                    {{ myLang.details }}</button
                  ><button
                    @click="delete_category_data(cat_list)"
                    class="btn btn-danger"
                  >
                    {{ myLang.delete }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <b-modal
      size="md"
      :hide-backdrop="true"
      :title="myLang.category_modal_title"
      :ok-title="myLang.add_new"
      :cancel-title="myLang.cancel"
      @ok.prevent="save_new_cat()"
      v-model="add_cmn_cat_modal"
      :no-enforce-focus="true"
    >
      <!-- <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      <div class="modal-body">-->
      <div class="panel-body add_item_body">
        <form>
          <input type="hidden" v-model="form.cmn_category_id" />
          <!--<div class="form-group row">
    <label for="staticEmail" class="col-sm-4 col-form-label">category 名</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" v-model="form.name">
      <has-error :form="form" field="name"></has-error>
    </div>
  </div>-->
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">{{
              myLang.category_code
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                maxlength="3"
                :class="{ 'is-invalid': form.errors.has('category_code') }"
                v-model="form.category_code"
              />
              <has-error :form="form" field="category_code"></has-error>
            </div>
          </div>

          <div class="form-group row">
            <label for="category_name" class="col-sm-4 col-form-label"> 部門名</label>
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                maxlength="80"
                :class="{ 'is-invalid': form.errors.has('category_name') }"
                v-model="form.category_name"
              />
              <has-error :form="form" field="category_name"></has-error>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">{{
              myLang.select_parent_category
            }}</label>
            <div class="col-sm-8">
              <select
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('parent_category_id') }"
                v-model="form.parent_category_id"
              >
                <option v-bind:value="0">{{ myLang.select_category }}</option>
                <option
                  v-for="option in options"
                  v-bind:value="option.cmn_category_id"
                  v-bind:key="option.cmn_category_id"
                >
                  {{ option.category_name }}
                </option>
              </select>
              <has-error :form="form" field="parent_category_id"></has-error>
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
import AdvancedLaravelVuePaginate from "advanced-laravel-vue-paginate";
import "advanced-laravel-vue-paginate/dist/advanced-laravel-vue-paginate.css";

export default {
  components: {
    AdvancedLaravelVuePaginate,
  },
  data() {
    return {
      cat_lists: {},
      add_cmn_cat_modal: false,
      options: [],
      select_field_per_page_num:10,
      select_field_page_num:0,
      form: new Form({
        cmn_category_id: "",
        name: "",
        category_name: "",
        category_code: "",
        parent_category_id: 0,
        adm_user_id: Globals.user_info_id,
      }),
    };
  },
  methods: {
    selectNumPerPage() {

      if (this.select_field_per_page_num != 0) {
        Fire.$emit("AfterCreatecat",this.select_field_page_num);
      }
    },
    insertItemCategory(e){
      var _this = this;
      this.alert_icon = "warning";
      this.alert_title = "";
      this.alert_text = "分類コードファイルをアップロードしますか？";
      this.yes_btn = "はい";
      this.cancel_btn = "キャンセル";
      this.confirm_sweet().then((result) => {
        if (result.value) {
          const formData = new FormData();
          let file = e.target.files[0];

          this.loader = Vue.$loading.show();
          formData.append("file", file);
          formData.append("adm_user_id", Globals.user_info_id);
          axios.post(this.BASE_URL + "api/uploadByrCategoryCsv", formData)
            .then(({ data }) => {
              _this.alert_icon = "success";
              _this.alert_title = "Inserted";
              _this.alert_text = "Category CSV inserted";
              _this.sweet_normal_alert();

              _this.get_all_cat(_this.select_field_page_num);

            });
        }
      });
    },
    add_new_category_cmn() {
      this.form.reset();
      this.add_cmn_cat_modal = true;
      this.form.parent_id = 0;
    },
    edit_category_data(form_data) {
      this.add_cmn_cat_modal = true;

      this.form.reset();

      this.form.fill(form_data);
      this.form.parent_category_id = form_data.parent_category_id;
      if(form_data.level=='1'){
        this.form.category_name =  form_data.m_name;
        this.form.category_code =  form_data.m_code;
      }else if(form_data.level=='2'){
        this.form.category_name =  form_data.sm_name;
        this.form.category_code =  form_data.sm_code;
      }else{
        this.form.category_name =  form_data.mm_name;
        this.form.category_code =  form_data.mm_code;
      }

    },
    save_new_cat() {

      this.form.adm_user_id = Globals.user_info_id;
      this.form
        .post(this.BASE_URL + "api/cmn_category_create")
        .then((data) => {
          if (data.data.message == "fail") {
            var tittles = "Invalid Category";
            var msg_text = "please check parent";
            var icon = "warning";
          } else {
            this.add_cmn_cat_modal = false;
            Fire.$emit("AfterCreatecat",this.select_field_page_num);
            if (this.form.cmn_category_id != "") {
              var tittles = "Category Update success";
              var msg_text = "You have successfully updated category";
              var icon = "success";
            } else {
              var tittles = "Category added success";
              var msg_text = "You have successfully added category";
              var icon = "success";
            }
          }
          Swal.fire({
            icon: icon,
            title: tittles,
            text: msg_text,
          });

        })
        .catch((error) => {

          Swal.fire({
            icon: "warning",
            title: "Invalid category info",
            text: "check category info!",
          });
        });
    },
    get_all_cat(page = 1) {
      var post_data = {
        adm_user_id:Globals.user_info_id,
        select_field_per_page_num:this.select_field_per_page_num,
        select_field_page_num:page,
        page : page,
      };
      this.select_field_page_num=page;
      axios
        .post(this.BASE_URL + "api/get_all_cat_list",post_data)
        .then(({ data }) => {
          this.cat_lists = data.cat_list;

          this.options = data.allCatForParent;
          this.loader.hide();
        });
    },
  },

  created() {
    // Fire.$emit('permission_check_for_buyer',this.$session.get('byr_buyer_id'));
    this.loader = Vue.$loading.show();
    this.get_all_cat();
    Fire.$on("AfterCreatecat", (page=1) => {
      this.get_all_cat(page);
    });
    Fire.$emit("loadPageTitle", "分類管理");

  },
  mounted() {

  },
};
</script>
