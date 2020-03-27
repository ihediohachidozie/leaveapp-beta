new Vue({
    el: "#home",

    data: {
        users: '',
        departments: '',
        pendingRequests: '',
        approvalRequests: ''

    },
    created() {
        this.getUsers();
        this.getDepartments();
        this.getApprovalReqs();
        this.getPendingReqs();
        
    },
    methods: {
        getUsers() {
            axios.get('/home.users')
                .then(({ data }) => this.users = data);
               // this.getTotalUsers;
        },
        getDepartments() {
            axios.get('/home.department')
                .then(({ data }) => this.departments = data);
        },
        getPendingReqs() {
            axios.get('/home.pendingReq')
                .then(({ data }) => this.pendingRequests = data);
        },
        getApprovalReqs() {
            axios.get('/home.approvalReq')
                .then(({ data }) => this.approvalRequests = data);
        },
        getTotalUsers() {
            return this.users;
        }
    },
    computed:{

    }
});