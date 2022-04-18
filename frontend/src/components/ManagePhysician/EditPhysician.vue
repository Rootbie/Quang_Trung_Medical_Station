<template>
    <div class="d-flex w-100 justify-content-center overflow-auto">

        <div id="edit-physician" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
            <b-container>
                <b-row class="hang-1">
                    <h1>Cập nhật thông tin bác sĩ</h1>
                </b-row>
                <b-row class="hang-2">

                    <b-col class="cot-1">
                        <!-- Update -->
                        <b-row class="position-relative">
                            <b-avatar
                                src="https://i1-vnexpress.vnecdn.net/2021/03/02/103650164-731814290963011-1374-5806-7233-1614677857.jpg?w=1020&h=0&q=100&dpr=1&fit=crop&s=OjCwh86rBpWbN7417wYmVw"
                                size="12rem">
                            </b-avatar>
                            <b-form-file plain class="fas fa-camera"></b-form-file>
                        </b-row>

                        <b-row class="mt-3 flex-grow-0 position-relative">
                            <b-form-input id="name" type="text" v-model="name" class="px-5" ></b-form-input>
                            <!-- Combo : label and icon -->
                            <label for="name"> <i class="fas fa-pen"></i></label> 

                        </b-row>
                    </b-col>

                    <!-- Form -->
                    <b-col class="cot-2">
                        <b-row>
                            <b-col>
                                <label class="text">Giới tính</label>
                            </b-col>
                            <b-col>
                                <b-form-group>
                                    <b-form-radio v-model="gender" name="gioitinh" value="m">Nam</b-form-radio>
                                    <b-form-radio v-model="gender" name="gioitinh" value="f">Nữ</b-form-radio>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="dob">Ngày sinh</label>
                            </b-col>
                            <b-col>
                                <!-- <b-form-input type="date" v-model="dob"></b-form-input> -->
                                <b-form-datepicker id="dob" v-model="dob" start-weekday="1"
                                    :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                                    placeholder="Ngày/Tháng/Năm" locale="vi" :max="max" show-decade-nav>
                                </b-form-datepicker>

                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="phone_number">Số điện thoại</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="tel" id="phone_number" v-model="phone_number"></b-form-input>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="address">Địa chỉ</label>
                            </b-col>
                            <b-col>
                                <b-form-textarea type="text" id="address" rows="4" no-resize v-model="address">
                                </b-form-textarea>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="ID_card_number">CCCD</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="text" id="ID_card_number" v-model="ID_card">
                                </b-form-input>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
                <b-row class="hang-3">
                    <b-button type="submit" variant="primary" @click="handleUpdate">LƯU</b-button>
                    <b-button type="reset" variant="danger" to="/nexus/manage-physician">HỦY</b-button>
                </b-row>
            </b-container>
        </div>

    </div>

</template>

<script>
    export default {
        data: function () {
            const now = new Date()
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())

            return {
                visible: localStorage.getItem('visible'),

                name: '',
                phone_number: '',
                dob: '',
                gender: '',
                address: '',
                ID_card: '',

                max : today
            }
        },
        methods: {
            getPhysicianById() {
                this.$axios.get(`http://localhost:8000/user/${this.$route.params.id}`)
                    .then(res => {
                        this.name = res.data.name;
                        this.phone_number = res.data.phone_number;
                        this.dob = res.data.date_of_birth;
                        this.gender = res.data.gender;
                        this.address = res.data.address;
                        this.ID_card = res.data.ID_card_number;
                    })
                    .catch(err => console.log(err))
            },
            handleUpdate() {
                const data = {
                    'name': this.name,
                    'phone_number': this.phone_number,
                    'date_of_birth': this.dob,
                    'gender': this.gender,
                    'address': this.address,
                    'ID_card_number': this.ID_card
                }

                this.$axios.put(`http://localhost:8000/updateUser/${this.$route.params.id}`, data)
                    .then(res => {
                        if (res.status === 200) {
                            console.log(res.data.message);  
                            this.$router.push('/nexus/manage-physician')
                        }
                    })
                    .catch(err => { console.log(err.response.data.message) })
            },
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
            this.getPhysicianById();
            this.listenStorage();
        }
    }
</script>

<style scoped>
    #edit-physician {
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
        justify-content: space-around;
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

        /* update */
        align-items: center;
    }


    .rounded-circle {
        margin-left: auto;
        margin-right: auto;
    }

    .diachi {
        height: 76px;
    }

    .form-control-file {
        width: 30px;
        height: 30px;
    }


    .fa-camera {
        font-size: 30px;
        position: absolute;
        color: rgba(0, 0, 0, 0.5);

        /* update*/
        left: 42%;
        bottom: 0;
    }

    .fas:hover {
        color: black;
        cursor: pointer;
    }

    /* update */
    .fa-pen {
        position: absolute;
        margin: 0;
        top: 25%;
        right: 5%;

        color: rgba(0, 0, 0, 0.5);
        font-size: 20px;
    }

    input#name {
        font-size: 1.5rem;
        font-weight: 500;
        line-height: 1.2;
        text-align: center;

        color: #212529;
        margin-bottom: 0.5rem;
    }
</style>