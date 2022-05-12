<template>
    <div class="d-flex w-100 justify-content-center overflow-auto">

        <div id="DoctorIn4" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
            <b-container>
                <b-row class="hang-1">
                    <h1>Thông tin bác sĩ</h1>
                </b-row>
                <b-row class="hang-2">
                    <b-col class="cot-1">
                        <b-avatar :src="profile.img_src" size="12rem" variant="info"></b-avatar>
                        <h4>{{profile.name}}</h4>
                    </b-col>
                    <!-- Form -->
                    <b-col class="cot-2">
                        <b-row>
                            <b-col>
                                <label class="text">Giới tính</label>
                            </b-col>
                            <b-col>
                                <b-form-group>
                                    <b-form-radio v-model="profile.gender" name="gioitinh" value="m"
                                        v-if=" profile.gender === 'm' ">Nam
                                    </b-form-radio>

                                    <b-form-radio v-model="profile.gender" name="gioitinh" value="f" v-else>Nữ
                                    </b-form-radio>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="datepicker-dateformat">Ngày sinh</label>
                            </b-col>
                            <b-col>

                                <b-form-datepicker id="datepicker-dateformat" :value="profile.date_of_birth"
                                    start-weekday="1"
                                    :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                                    placeholder="Ngày/Tháng/Năm" locale="vi" show-decade-nav disabled>
                                </b-form-datepicker>

                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="phone_number">Số điện thoại</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="tel" id="phone_number" :value="profile.phone_number" readonly>
                                </b-form-input>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="address">Địa chỉ</label>
                            </b-col>
                            <b-col>
                                <b-form-textarea type="text" id="address" rows="4" no-resize :value="profile.address"
                                    readonly>
                                </b-form-textarea>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="ID_card_number">CCCD</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="text" id="ID_card_number" :value="profile.ID_card_number" readonly>
                                </b-form-input>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
                <b-row class="hang-3">

                </b-row>
            </b-container>
        </div>

    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                visible: localStorage.getItem('visible'),
                profile: {}
            }
        },
        methods: {
            getProfile() {
                this.$axios.get('http://localhost:8000/profile')
                    .then(res => {
                        if (res.status === 200) {
                            this.profile = res.data
                        }
                    })
                    .catch(err => console.log(err.response.data.message))
            }
            ,
            listenStorage() {
                const localStorageSetHandler = (e) => {
                    // e.value set to String because the 1st value in local storage is string
                    (e.key === "visible") ? this.visible = String(e.value) : null
                };
                // Listen for changes with localStorage on the same window
                document.addEventListener("showSideBar", localStorageSetHandler, false);
            }
        },
        created() {
            this.getProfile();
            this.listenStorage();
        }
    }
</script>

<style scoped>
    #DoctorIn4 {
        max-width: 100%;
        transition: margin-left 0.5s;
        position: relative;

        width: auto;
        height: 100%;
        display: flex;
        flex-wrap: wrap;
    }

    /* increase/decrease margin*/
    .add-margin {
        margin-left: 170px;
    }

    .rm-margin {
        margin-left: 0px;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
    }

    .hang-1,
    .hang-3 {
        display: flex;
        justify-content: center;
    }

    .col {
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
    }

    .text {
        display: flex;
        justify-content: flex-start;
        margin-top: auto;
        margin-bottom: auto;
        font-weight: 500;
    }

    .form-group {
        margin-top: auto;
        margin-bottom: auto;
    }

    .form-control {
        width: 300px;

        /* update */
        background: none !important;
    }

    /* update */
    .form-control:focus {
        border: 1px solid #ced4da;
        box-shadow: none !important;
    }

    .custom-radio {
        width: 300px;
    }

    .hang-2 {
        display: flex;
        justify-content: center;
        height: 350px;
    }

    .cot-1 {
        display: flex;
        justify-content: flex-start;
        position: relative;
    }

    h4 {
        text-align: center;
    }

    .rounded-circle {
        margin-left: auto;
        margin-right: auto;
    }

    .form-control-file {
        width: 30px;
        height: 30px;
    }

    .fa-camera {
        font-size: 30px;
        position: absolute;
        margin-left: 140px;
        margin-top: 165px;
        color: rgba(0, 0, 0, 0.5);
    }

    .fa-camera:hover {
        color: black;
    }
</style>