export default {
    data() {
        return {
            role_permissions: {},
            role_permissions_id: {},
            all_permissions: [],
            // permissions: [],
            form: new Form({
                role_name: "",
                permissions: [],
                role_description: "",
                role_update_id: "",
                submit_button: "Save",
            })
        };
    },
    methods: {
        //get Table data
        loadTableData() {
            axios
                .get("api/role")
                .then(({ data }) => {
                    this.role_permissions = data.role_permissions;
                    this.all_permissions = data.role_permissions[0].all_permissions;
                    // this.role_update_info = data.role_update_info;
                })
                .catch(() => {
                    console.log("Error...");
                });
        },

        //Insert role
        SubmitRole() {
            this.form
                .post("api/role")
                .then(({ data }) => {
                    Swal.fire({
                        icon: data.class_name,
                        title: data.title,
                        text: data.message
                    })
                    if (data.class_name == "success") {
                        this.loadTableData();
                        this.form.clear();
                        this.form.reset();
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: '内部エラー',
                        text: 'システム内でのエラーが発生しました。',
                        footer: 'システム担当へご連絡ください。'
                    })
                });
        },
        editRole(item) {
            this.form.clear();
            this.form.reset();
            this.form.role_name = item.role_name;
            this.form.role_description = item.role_description;
            this.form.permissions = item.permissions;
            this.form.submit_button = "Update";
            this.form.role_update_id = item.role_id;
            //  window.scrollTo(0,0);
            $("html, body").animate({ scrollTop: 0 }, "slow");
        },
        //Delete role
        deleteRole(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //Send Request to server
                    this.form.delete('api/role/' + id)
                        .then((response) => {
                            Swal.fire({
                                icon: response.data.class_name,
                                title: response.data.title,
                                text: response.data.message
                            })
                            this.loadTableData();

                        }).catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: '内部エラー',
                                text: 'システム内でのエラーが発生しました。',
                                footer: 'システム担当へご連絡ください。'
                            })
                        })
                }

            })
        }
        //Pagination
        // getResults(page = 1) {
        //   axios.get("api/role?page=" + page).then(response => {
        //     this.tabledata = response.data;
        //   });
        // }
    },

    created() {
        //LoadTableData
        this.loadTableData();
    },
    mounted() {
        console.log("Role page loaded");
    }
};
// export default {
//     mounted() {
//         console.log('Role Component mounted.')
//     }
// }