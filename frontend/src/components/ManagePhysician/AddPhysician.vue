<template>
    <div class="d-flex w-100 justify-content-center overflow-auto">

        <div id="add-physician" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
            <b-container>
                <b-row class="hang-1">
                    <h1>Thêm bác sĩ</h1>
                </b-row>

                <b-row class="hang-2">
                    <b-col class="cot-1">
                        <!-- Update -->
                        <b-row class="position-relative">
                            <b-avatar class="ava"
                                src="https://i1-vnexpress.vnecdn.net/2021/03/02/103650164-731814290963011-1374-5806-7233-1614677857.jpg?w=1020&h=0&q=100&dpr=1&fit=crop&s=OjCwh86rBpWbN7417wYmVw"
                                size="12rem">
                            </b-avatar>

                            <b-form-file plain class="fas fa-camera"></b-form-file>
                        </b-row>

                        <b-row class="mt-3">
                            <!-- <b-col> <label class="text" for="name"></label></b-col> -->
                            <b-form-input id="name" placeholder="Họ và tên" type="text" v-model="physician.name">
                            </b-form-input>

                        </b-row>

                    </b-col>
                    <!-- Form -->
                    <b-col class="cot-2">
                        <b-row class="d-block">
                            <b-alert v-model="showDismissibleAlert" variant="danger" dismissible>
                                {{message}}
                            </b-alert>
                        </b-row>

                        <b-row>
                            <b-col>
                                <label class="text">Giới tính</label>
                            </b-col>
                            <b-col>
                                <b-form-group>
                                    <b-form-radio v-model="physician.gender" name="gioitinh" value="m">Nam
                                    </b-form-radio>
                                    <b-form-radio v-model="physician.gender" name="gioitinh" value="f">Nữ</b-form-radio>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="dob">Ngày sinh</label>
                            </b-col>
                            <b-col>

                                <b-form-datepicker id="dob" v-model="physician.date_of_birth" start-weekday="1"
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
                                <b-form-input type="tel" id="phone_number" v-model="physician.phone_number">
                                </b-form-input>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="address">Địa chỉ</label>
                            </b-col>
                            <b-col>
                                <b-form-textarea type="text" id="address" rows="4" no-resize
                                    v-model="physician.address">
                                </b-form-textarea>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="CCCD">CCCD</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="text" id="CCCD" v-model="physician.ID_card_number"></b-form-input>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="password">Mật khẩu</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="text" id="password" v-model="physician.password"></b-form-input>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <label class="text" for="confirm_pass">Nhập lại mật khẩu</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="text" id="confirm_pass" v-model="physician.password_confirmation">
                                </b-form-input>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>

                <b-row class="hang-3">
                    <b-button type="submit" variant="primary" @click="handleAdd">THÊM</b-button>
                    <b-button type="reset" variant="danger" to="/nexus/manage-physician">HỦY</b-button>
                </b-row>
            </b-container>
        </div>
    </div>

</template>

<script>
    import translate from "translate"

    export default {
        data: function () {
            const now = new Date()
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())

            return {
                physician: {
                    name: '',
                    phone_number: '',
                    password: '',
                    password_confirmation: '',
                    date_of_birth: '',
                    gender: '',
                    address: '',
                    ID_card_number: ''
                },
                max : today,
                message: '',
                showDismissibleAlert: false,
                visible: localStorage.getItem('visible')
            }
        },
        methods: {
            validation() {
                const vnf_regex = /((09|03|07|08|05)+([0-9]{8})\b)/g;

                if (this.physician.name === '') {
                    this.message = 'Nhập họ và tên'
                    return true;
                }
                else if (this.physician.gender === '') {
                    this.message = 'Chọn giới tính';
                    return true;
                }
                else if (this.physician.date_of_birth === '') {
                    this.message = 'Chọn ngày sinh';
                    return true;
                }
                else if (vnf_regex.test(this.physician.phone_number) === false) {
                    this.message = 'Số điện thoại của bạn không đúng định dạng'
                    return true;
                }
                else if (this.physician.address === '') {
                    this.message = 'Nhập địa chỉ';
                    return true;
                }
                else if (/^\d+$/.test(this.physician.ID_card_number) === false) {
                    this.message = 'Căn cước cần nhập ký tự số'
                    return true;
                }
                else if (this.physician.password.length < 8) {
                    this.message = 'Sử dụng 8 ký tự trở lên cho mật khẩu của bạn';
                    return true;
                }
                else if (this.physician.password_confirmation !== this.physician.password) {
                    this.message = 'Các mật khẩu đã nhập không khớp. Hãy thử lại.'
                    return true;
                }

                return false;
            },
            handleAdd() {
                this.physician.name = this.physician.name.trim();
                this.physician.phone_number = this.physician.phone_number.trim();
                this.physician.address = this.physician.address.trim();
                this.physician.ID_card_number = this.physician.ID_card_number.trim();

                if (this.validation()) {
                    this.showDismissibleAlert = true
                }
                else {
                    this.$axios.post('http://localhost:8000/addUser', this.physician)
                        .then(res => {
                            if (res.status === 200) {
                                this.$router.push('/nexus/manage-physician')
                            }
                        })
                        .catch(async (err) => {
                            this.message = await translate(err.response.data.message[0], "vi");
                            this.showDismissibleAlert = true;
                        })
                }

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
            this.listenStorage();
        }
    }
</script>

<style scoped>
    #add-physician {
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
        width: 1000px;
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
        /* update */
        height: 400px;
    }

    .cot-1 {
        display: flex;
        justify-content: flex-start;
        /* update */
        align-items: center;
    }

    /* h4 {
        position: relative;
        text-align: center;
        bottom: 20px;
        margin: 0;
    } */

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

    .fas:hover {
        cursor: pointer;
    }

    .fa-camera {
        font-size: 30px;
        position: absolute;
        color: rgba(0, 0, 0, 0.5);

        /* update*/
        left: 42%;
        bottom: 0;
    }

    .fa-camera:hover {
        color: black;
    }

    input#name {
        font-size: 1.5rem;
        font-weight: 500;
        text-align: center;

        color: #212529;
        margin-bottom: 0.5rem;
    }

    input#name::placeholder {
        font-weight: 400;
    }
</style>