<template>
    <div class="h-100 w-100">
        <!-- // loading spinner -->
        <div class="d-flex justify-content-center " v-if="loading">
            <p>Loading... <b-spinner label="Loading..."></b-spinner>
            </p>
        </div>

        <div class="main-content" v-else>

            <b-sidebar id="sidebar" no-header sidebar-class="border-right" :no-close-on-route-change="visible"
                aria-controls="id" v-model="visible">

                <template>
                    <!-- class="p-4" là lớp thiết lập padding p{sides}-{size}  -->
                    <!-- sides thay bằng t|l|r|b|x|y tương ứng với top, left, right, bottom, left-right, top-bottom. Nếu sides để trống nghĩa là thiết lập cho cả bốn phía -->
                    <!-- sizes thay bằng các số 0 1 2 3 4 5 hoặc auto để thiết lập kích thước (0, 0.25, 0.5, 1, 1.5, 3) rem -->
                    <div class="p-4">
                        <b-nav pills vertical>
                            <b-nav-item :active="tab == 1" @click="tab = 1" to="/nexus/overview">
                                Tổng quan
                            </b-nav-item>

                            <b-nav-item :active="tab == 2" @click="tab = 2" to="/nexus/manage-appointment">
                                Đăng ký tiêm
                            </b-nav-item>

                            <b-nav-item :active="tab == 3" @click="tab = 3" to="/nexus/manage-vaccination-records">
                                Sổ tiêm chủng
                            </b-nav-item>

                            <b-nav-item :active="tab == 4" @click="tab = 4" to="/nexus/manage-vaccines">
                                Quản lý Vắc-xin
                            </b-nav-item>

                            <b-nav-item :active="tab == 5" @click="tab = 5" to="/nexus/view-bill">
                                Phiếu thanh toán dịch vụ
                            </b-nav-item>

                            <b-nav-item v-if="isAdmin" :active="tab == 6" @click="tab = 6" to="/nexus/manage-physician">
                                Quản lý thông tin bác sĩ
                            </b-nav-item>
                        </b-nav>
                    </div>
                </template>

            </b-sidebar>

            <!-- Các chức năng chính sẽ nằm ở trong router view đây -->
            <router-view :key="$router.path" />

        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                tab: 1,
                visible: true,

                loading: false,
                isAdmin: false
            }
        },
        watch: {
            visible: function () {
                // Rewrite original setItem function
                const originalSetItem = localStorage.setItem;

                localStorage.setItem = function (key, value) {
                    const event = new Event('showSideBar');

                    event.key = key;
                    event.value = value;

                    document.dispatchEvent(event);

                    originalSetItem.apply(this, arguments);
                };

                //store in local storage
                localStorage.setItem('visible', this.visible)
            },
            tab: function () {
                //store in local storage
                localStorage.setItem('tabIndex', this.tab)
            }
        },
        methods: {
            accessRightTab() {
                // Check if page gets reloaded or refreshed
                const pageAccessedByReload = ((window.performance.navigation && window.performance.navigation.type === 1));

                // if true, get the current tab. Otherwise, get tab had set in local storage by each route
                // Value in local storage is always STRING
                if (pageAccessedByReload) {
                    this.tab = localStorage.getItem('tabIndex');
                    (localStorage.getItem('visible') === 'true') ? this.visible = true : this.visible = false;

                } else {
                    localStorage.setItem('visible', this.visible);
                    // get tab set by route
                    this.tab = localStorage.getItem('tabIndex');
                }
            },
            checkAdmin() {
                const axios = require('axios');
                axios.get('http://localhost:8000/profile')
                    .then(res => {
                        if (res.status === 200 && res.data.phone_number === '0999999999') {
                            this.isAdmin = true
                        }
                    })
                    .catch(err => { console.log(err.response.data.message) })
            }
        },
        created() {
            this.accessRightTab();
            this.checkAdmin();
        },
        beforeRouteEnter(to, from, next) {

            switch (true) {
                case (to.path === '/nexus/overview'):
                    localStorage.setItem('tabIndex', 1)
                    next()

                    break;

                case (to.meta.tab === 2):
                    localStorage.setItem('tabIndex', 2)
                    next()

                    break;

                case (to.meta.tab === 3):
                    localStorage.setItem('tabIndex', 3)
                    next()

                    break;

                case (to.meta.tab === 4):
                    localStorage.setItem('tabIndex', 4)
                    next()

                    break;

                case (to.meta.tab === 5):
                    localStorage.setItem('tabIndex', 5)
                    next()
                    break;

                case (to.meta.requiresAuth):
                    localStorage.setItem('tabIndex', 6)

                    next((vm) => {
                        vm.loading = true

                        // less than 1 sec , admin can not be true 
                        setTimeout(() => {
                            if (vm.isAdmin) {
                                vm.loading = false
                                next()
                            }
                            else {
                                vm.$router.push('*')
                            }
                        }, 1800)

                    })
                    break;

                default:
                    next()
                    break;
            }
        }
    }
</script>

<style>
    /* Do not use scoped here*/
    .main-content {
        display: flex;
        position: relative;

        height: 100%;
        width: 100%;
    }

    /* change fixed to absolute position so sidebar is under nav*/
    .b-sidebar-outer {
        position: absolute !important;
        height: 100% !important;
        width: 260px !important;

        z-index: 0 !important;
        /* background-color: red !important; */
    }

    .b-sidebar-outer #sidebar.b-sidebar {
        position: absolute !important;
        height: 100% !important;
        width: fit-content !important;
        /* background-color: red !important; */
    }

    /* increase/decrease margin*/
    .add-margin {
        margin-left: 320px;
    }

    .rm-margin {
        margin-left: 0px;
    }
</style>