<template>
  <div>
    <b-row>
      <b-col v-if="single_blog.length != '0'">
        <h4 class="my-3 blog_titles">
          <i class="fas custom_blog_square fa-square-full"></i> ジャコス連絡事項
        </h4>
        <div class="blogs_content" v-html="single_blog.blog_content"></div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      home_text: Globals.welcome_text,
      BASE_URL: Globals.base_url,
      activeVal: "home",
      blog_lists: {},
      single_blog: {},
      user_blog: {},
    };
  },
  methods: {
    get_all_blogs() {
      axios
        .get(this.BASE_URL + "api/get_all_published_blog_list")
        .then(({ data }) => {
          this.blog_lists = data.blog_list;
        });
    },
    get_signle_top_blog() {
      axios.get(this.BASE_URL + "api/get_signle_top_blog").then(({ data }) => {
        this.single_blog = data.blog_list;
      });
    },
    get_user_top_blog() {
      axios.get(this.BASE_URL + "api/get_user_top_blog").then(({ data }) => {
        this.user_blog = data.blog_list;
      });
    },
  },
  created() {
    this.get_all_blogs();
    this.get_signle_top_blog();
    this.get_user_top_blog();
    Fire.$on("AfterCreateblog", () => {
      this.get_all_blogs();
    });
    Fire.$emit('byr_menu_show');
  },
  mounted() {},
};
</script>
