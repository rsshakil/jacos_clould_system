<template>
    <div class="row" v-can="['slr_view']">
                <div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">{{myLang.corrected_receive_data}}</h4>
                </div>
                <div class="col-12 text-center">

      <label>
        <!--<input type="file" id="file" ref="file" v-on:change="onChangeFileUpload()"/>-->
      </label>
                </div>
                <div class="col-12">
                    <div class="">
                        <table class="table table-striped table-bordered data_table">
                            <thead>
                                <tr>
                                    <th colspan="100%" style="border: none;">
                                        <div class="input-group mb-1" style="margin-left: 10px;max-width: 250px; float: left;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-primary" type="button">{{myLang.buyer_selection}}</button>
                                            </div>
                                            <select class="form-control" v-model="selected_byr">
                                              <option :value="0">{{myLang.select_buyer}}</option>
                                              <option v-for="(option, index) in byr_buyer_lists"
                    :key="index" :value="option.cmn_company_id"
                    :selected="selectedOption(option)">
                    {{ option.company_name }}
            </option>
                                            </select>
                                        </div>
                                        <!--<div class="active-pink-3 active-pink-4 mb-1" style="margin-left: 10px;max-width: 100%; float: left;">
                                            <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                                        </div>-->
                                    </th>
                                </tr>
                                <tr>
                                    <th style="cursor: pointer">No</th>
                                    <th style="cursor: pointer">{{myLang.buyer_name}}</th>
                                    <th style="cursor: pointer">{{myLang.receive_date}}</th>
                                    <th style="cursor: pointer">{{myLang.download_date}}</th>
                                    <th style="cursor: pointer">{{myLang.received_data}}</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in order_corrected_receive_lists" :key="value.byr_corrected_receive_id">
                                    <td>{{index+1}}</td>
                                     <td>{{value.company_name}}</td>
                                    <td>{{value.receive_date}}</td>
                                    <td>{{value.download_date}}</td>
                                    <td><button class="btn btn-primary">{{myLang.received_data}}</button></td>

                                </tr>
                                <tr v-if="order_corrected_receive_lists && order_corrected_receive_lists.length==0">
                <td colspan="5">データがありません</td>
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
        'order_corrected_receive_lists':{},
        'byr_buyer_lists':{},
        'file':'',
        'selected_byr':'0',
    };
  },
  beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
  methods: {
    //get Table data
    get_all_order(){
        axios.get(this.BASE_URL +"api/corrected_receive_list/"+Globals.user_info_id).then(({data}) => {
            this.order_corrected_receive_lists = data.data.corrected_list;
            this.byr_buyer_lists = data.data.byr_buyer_list;
        });
    },
    check_byr_order_api(){
       let formData = new FormData();
    formData.append("up_file", this.file);
        axios({
    method: 'POST',
    url: this.BASE_URL + "api/job_exec/1",
    data: formData,
    headers: {'Content-Type': 'multipart/form-data' }
    })
    .then(({data})=> {
       Fire.$emit('LoadByrorder');
    })
    .catch(function (response) {
    });
    },
    onChangeFileUpload(){
        this.file = this.$refs.file.files[0];
        this.check_byr_order_api();
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
      this.get_all_order();
      Fire.$on("LoadByrorder", () => {
      this.get_all_order();
    });
  },
  mounted() {
  }
};
</script>
