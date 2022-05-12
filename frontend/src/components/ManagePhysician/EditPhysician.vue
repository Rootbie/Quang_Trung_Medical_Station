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
                            <b-avatar class="ava" v-if="img_src.length !== 0" :src="preview_url" size="12rem"
                                variant="info">
                            </b-avatar>
                            <b-avatar class="ava" v-else :src="physician.img_src" size="12rem" variant="info">
                            </b-avatar>

                            <b-form-file plain class="fas fa-camera" v-model="img_src" accept=".jpg, .jpeg, .png">
                            </b-form-file>
                        </b-row>

                        <b-row class="mt-3 flex-grow-0 position-relative">
                            <b-form-input id="name" type="text" v-model="physician.name" class="px-5"></b-form-input>
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
                                <!-- <b-form-input type="date" v-model="dob"></b-form-input> -->
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
                                <label class="text" for="ID_card_number">CCCD</label>
                            </b-col>
                            <b-col>
                                <b-form-input type="text" id="ID_card_number" v-model="physician.ID_card_number">
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

                preview_url: '',
                img_src: [],

                physician: {},

                max: today
            }
        },
        watch: {
            "img_src": function () {
                this.preview_url = URL.createObjectURL(this.img_src)
            }
        },
        methods: {
            getPhysicianById() {
                this.$axios.get(`http://localhost:8000/user/${this.$route.params.id}`)
                    .then(res => {
                        this.physician = res.data
                    })
                    .catch(err => console.log(err))
            },
            handleUpdate() {
                const formData = new FormData()

                formData.append('name', this.physician.name)
                formData.append('phone_number', this.physician.phone_number)
                formData.append('password', this.physician.password)
                formData.append('password_confirmation', this.physician.password_confirmation)
                formData.append('date_of_birth', this.physician.date_of_birth)
                formData.append('gender', this.physician.gender)
                formData.append('address', this.physician.address)
                formData.append('ID_card_number', this.physician.ID_card_number)
                formData.append('img_src', this.img_src)

                this.$axios.post(`http://localhost:8000/updateUser/${this.$route.params.id}`, formData,
                    {
                        headers: { "Content-Type": "multipart/form-data" }
                    })
                    .then(res => {
                        if (res.status === 200) {

                            this.$router.push('/nexus/manage-physician')
                        }
                    })
                    .catch(err => {
                        console.log(err.response.data.message)
                    })
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