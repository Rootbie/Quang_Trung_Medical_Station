import Vue from 'vue'
import Router from 'vue-router'

// A. Parent component
import AppContainer from './components/AppContainer.vue'
import MainContent from './components/MainContent.vue'

// B. Login
import LoginView from './components/LoginView.vue'
import VerifyPhone from './components/ForgotPass/VerifyPhone.vue'

// C. Function
// 1. Overview page
import OverviewPage from './components/OverviewPage.vue'

// 2. Manage appointment
import ManageAppointment from './components/ManageAppointment/ManageAppointment.vue'
import ViewAppointment from './components/ManageAppointment/ViewAppointment.vue'
import AddAppointment from './components/ManageAppointment/AddAppointment.vue'
import EditAppointment from './components/ManageAppointment/EditAppointment.vue'

// 3. Manage vacc records
import ManageVaccinationRecords from './components/ManageVaccinationRecords/ManageVaccinationRecords.vue'

// 4. Manage vacc
import ManageVaccines from './components/ManageVaccines/ManageVaccines.vue'
import EditVac from './components/ManageVaccines/EditVac.vue'

// 5. View billing info
import ViewBill from './components/ViewBill/ViewBill.vue'

// 6. Manage physician (Admin)
import PhysicianManagement from './components/ManagePhysician/NurseManagement.vue'
import AddPhysician from './components/ManagePhysician/AddPhysician.vue'
import EditPhysician from './components/ManagePhysician/EditPhysician.vue'

// 7. View profile
import Profile from './components/ManagePhysician/Profile.vue'

// D. Error
import NotFound from './components/Error/NotFound404.vue'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        { path: '/', component: LoginView },
        { path: '/forgot-password', component: VerifyPhone },

        {
            path: '/nexus', component: AppContainer, children: [

                {
                    path: '', component: MainContent, children: [

                        // 1.
                        { path: 'overview', component: OverviewPage },

                        // 2.
                        { path: 'manage-appointment', component: ManageAppointment, meta: { tab: 2 } },
                        { path: 'manage-appointment/view/:appointment_id', name: 'viewAppointment', component: ViewAppointment, meta: { tab: 2 } },
                        { path: 'manage-appointment/add', component: AddAppointment, meta: { tab: 2 } },
                        { path: 'manage-appointment/edit/:appointment_id', name: 'editAppointment', component: EditAppointment, meta: { tab: 2 } },

                        // 3.
                        { path: 'manage-vaccination-records', component: ManageVaccinationRecords, meta: { tab: 3 } },

                        // 4.
                        { path: 'manage-vaccines', component: ManageVaccines, meta: { tab: 4 } },
                        { path: 'manage-vaccines/edit/:idVac', component: EditVac, meta: { tab: 4 } },

                        // 5.
                        { path: 'view-bill', component: ViewBill, meta: { tab: 5 } },

                        // 6.
                        { path: 'manage-physician', component: PhysicianManagement, meta: { requiresAuth: true } },
                        { path: 'manage-physician/add', component: AddPhysician, meta: { requiresAuth: true } },
                        { path: 'manage-physician/edit/:id', name: 'editPhysician', component: EditPhysician, meta: { requiresAuth: true } },

                        // 7.
                        { path: 'profile', component: Profile },
                    ]
                }
            ]
        },
        { path: '*', component: NotFound }
    ]

});

// global navigation guard
router.beforeEach((to, from, next) => {
    const axios = require('axios');

    axios.get('http://localhost:8000/profile')
        .then(res => {
            // Authenticated
            if (res.status === 200) {
                if ('/nexus/'.includes(to.path)) {
                    localStorage.setItem('tabIndex', 1)
                    router.push('/nexus/overview')
                }
                else {
                    // Move on to the next hook.
                    // If no hooks are left, the navigation is confirmed.
                    next()
                }
            }
        })
        .catch(err => {
            // Unauthenticated
            if (err.response.status === 401) {
                if (to.path === '/') {
                    next()
                }
                else if (to.path === '/forgot-password') {
                    next()

                } else {
                    router.replace('/')
                }
            }

        })

})


export default router;