<template>
    <div>

        <b-navbar toggleable="lg" type="dark" variant="info">

            <!-- thuộc tính v-b.toogle.sidebar để trượt slide thẻ <b-sidebar với id="sidebar">   -->
            <!-- thuộc tính v-b.toogle.sidebar-1 để trượt slide thẻ <b-sidebar với id="sidebar-1">   -->
            <b-navbar-brand href="#" v-b-toggle.sidebar>
                <i class="fas fa-stethoscope"></i>
                <span class="name">Trạm Y Tế Phường Quang Trung</span>
            </b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <!-- <b-nav-item href="#">ĐĂNG KÝ TIÊM</b-nav-item>
                    <b-nav-item href="#">SỔ TIÊM CHỦNG</b-nav-item>
                    <b-nav-item href="#">VẮC-XIN</b-nav-item>
                    <b-nav-item href="#">PHIẾU THANH TOÁN</b-nav-item>
                    <b-nav-item href="#">QUẢN LÝ BÁC SĨ</b-nav-item> -->
                    <!-- <b-nav-item href="#">Bs. Phương Uyên</b-nav-item> -->


                    <b-nav-item-dropdown right>
                        <!-- Using 'button-content' slot -->
                        <template #button-content>
                            {{profile.name}}
                        </template>
                        <b-dropdown-item to="/nexus/profile"> <i class="fas fa-user-circle"></i><span>Trang cá nhân</span>
                        </b-dropdown-item>
                        <b-dropdown-item href="#"> <i class="fas fa-key"></i>Đổi mật khẩu</b-dropdown-item>

                        <b-dropdown-item href="#" @click="logout"> 
                            <i class="fas fa-sign-out-alt"></i>Đăng xuất
                        </b-dropdown-item>

                    </b-nav-item-dropdown>

                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                profile: {}
            }
        },
        methods: {
            getProfile() {
                this.$axios.get('http://localhost:8000/profile')
                    .then(res => {
                        if (res.status === 200){
                            this.profile = res.data
                        }
                    })
                    .catch(err => console.log(err.response.data.message))
            },
            logout() {
                this.$axios.post('http://localhost:8000/logout')
                    .then(res => {
                        if (res.status === 200){
                            this.$router.push('/')
                        } 
                    })
                    .catch(err => console.log(err.response.data.message))
            }
        },
        mounted() {
            this.getProfile()
        }
    }
</script>

<style scoped>
    /* change z-index > 1030 to move navbar to the front of the content
    https://getbootstrap.com/docs/5.0/layout/z-index/ */

    /* .navbar {
        z-index: 1111;
    } */

    /* .nav-item.nav-item.nav-item a {
        color: red;
    } */

    a.navbar-brand {
        font-size: 20px;
        display: flex;
        align-items: center;
    }


    .name {
        position: relative;
        top: -2px;
        left: -5px;
    }

    .fa {
        padding-right: 10px;
    }

    .fas {
        padding-right: 10px;
    }

    .fa-stethoscope {
        font-size: 40px;
        color: white;
    }

    i.fas.fa-stethoscope {
        position: relative;
        left: -5px;
    }

    .fa-calendar-check {
        font-size: 130px;
        position: relative;
        left: 118px;
        color: #17a2b8;
    }

    .fa-book {
        font-size: 130px;
        position: relative;
        left: 119px;
        color: #17a2b8;

    }

    .fa-syringe {
        font-size: 130px;
        position: relative;
        left: 115px;
        color: #17a2b8;

    }

    .fa-receipt {
        font-size: 130px;
        position: relative;
        left: 128px;
        color: #17a2b8;

    }

    .fa-address-book {
        font-size: 130px;
        position: relative;
        left: 118px;
        color: #17a2b8;

    }

    .dang-ky-tiem {
        position: relative;
        bottom: -20px;
    }

    .so-tiem-chung {
        position: relative;
        bottom: -20px;
    }

    .Vac-xin {
        position: relative;
        bottom: -20px;
    }

    .phieu-thanh-toan {
        position: relative;
        left: 210px;
        bottom: -40px;
    }

    .quan-ly-bac-si {
        position: relative;
        left: 211px;
        bottom: -40px;
    }

    h4.card-title {
        font-size: 30px;
        position: relative;
        color: #17a2b8;
        text-align: center;
    }
</style>