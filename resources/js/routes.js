import VueRouter from 'vue-router';
import Home from './views/Home';
import Leave from './views/Leave';
import Department from './views/Department';
import Category from './views/Category';
import PublicHoliday from './views/PublicHoliday';
import Users from './views/Users';
import Profile from './views/Profile';
import ApprovalRequest from './views/ApprovalRequest';
import UserLeaveSummary from './views/UserLeaveSummary';
import AllUserLeaveSummary from './views/AllUserLeaveSummary';
import ApprovedLeave from './views/ApprovedLeave';
import ApprovalStaffSummary from './views/ApprovalStaffSummary';
import HomeUserApprovalRequests from './views/HomeUserApprovalRequests';












let routes = [
    { path: '/home', component: Home },
    { path: '/departments',  component: Department },
    { path: '/categories',  component: Category },
    { path: '/PublicHolidays',  component: PublicHoliday },
    { path: '/all-users',  component: Users },
    { path: '/storage/user-profile',  component: Profile },
    { path: '/user-leave',  component: Leave },
    { path: '/approval-requests',  component: ApprovalRequest },
    { path: '/user-leave-summary', component: UserLeaveSummary },
    { path: '/all-user-leave-summary', component: AllUserLeaveSummary },
    { path: '/approved-leave',  component: ApprovedLeave },
    { path: '/approval-staff-summary', component: ApprovalStaffSummary },
    { path: '/home-user-approval', component: HomeUserApprovalRequests },


];

export default new VueRouter({

    routes,
    mode: 'history'

});