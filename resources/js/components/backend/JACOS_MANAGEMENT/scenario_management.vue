<template>
  <div>
    <div class="row">
                <div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">{{myLang.scenario_heading}}</h4>
                </div>
                <div class="col-12">
                    <div class="">
                      <table class="table table-striped table-bordered data_table">
                            <thead>
                                <tr>
                                    <th colspan="100%" style="border: none;">
                                       <button class="btn pull-right text-right btn-primary" style="float:right">{{myLang.add_new}}</button>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="cursor: pointer">No</th>
                                    <th style="cursor: pointer">{{myLang.scenario_name}}</th>
                                    <th style="cursor: pointer">{{myLang.buyer_name}}</th>
                                    <th style="cursor: pointer">{{myLang.wholesaler_name}}</th>
                                    <th style="cursor: pointer">{{myLang.scenario_description}}</th>
                                    <th style="cursor: pointer">{{myLang.scenario_file_path}}</th>
                                    <th style="cursor: pointer">{{myLang.details}}</th>
                                </tr>

                            </thead>
                            <tbody>

                                <tr v-for="(value,index) in scenario_lists" :key="value.id">
                                    <td>{{index+1}}</td>
                                    <td>{{value.name}}</td>
                                    <td>{{value.super_code}}</td>
                                    <td></td>
                                    <td>{{value.description}}</td>
                                    <td>{{value.file_path}}</td>
                                    <td><button class="btn btn-info">{{myLang.details}}</button></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
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
        'scenario_lists':{},
        'byr_buyer_id':'',
    };
  },
  methods: {
      get_all_scenarios(){
        axios.get(this.BASE_URL +"api/get_scenario_list").then(({data}) => {
            this.scenario_lists = data.scenario_list;

        });
    },
  },

  created() {
      this.get_all_scenarios();

  },
  mounted() {

  }
};
</script>
