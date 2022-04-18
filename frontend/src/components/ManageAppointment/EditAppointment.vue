<template>
    <div class="d-flex w-100 justify-content-center overflow-auto">

        <div id="edit-appointment" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
            <b-container>

                <b-row class="hang hang-1">
                    <h1>Sửa thông tin lịch hẹn</h1>
                </b-row>
                <b-row class="hang hang-2">
                    <h4>1. Thông tin cá nhân</h4>
                </b-row>

                <b-row class="hang hang-3">
                    <b-col>
                        <label class="text" for="name">Họ và tên</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="text" id="name" v-model="patient.name">
                        </b-form-input>
                    </b-col>
                </b-row>

                <b-row class="hang hang-4">
                    <b-col>
                        <label class="text" for="dob">Ngày sinh</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="date" v-model="patient.date_of_birth"></b-form-input>
                    </b-col>
                </b-row>

                <b-row class="hang hang-5">
                    <b-col>
                        <label class="text" for="address">Địa chỉ</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="text" id="address" v-model="patient.address">
                        </b-form-input>
                    </b-col>
                </b-row>

                <b-row class="hang hang-6">
                    <b-col>
                        <label class="text" for="input-default">Số điện thoại</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="tel" id="input-default" v-model="patient.phone_number">
                        </b-form-input>
                    </b-col>
                </b-row>
                <b-row class="hang hang-7">
                    <b-col>
                        <label class="text" for="weight">Cân nặng (kg)</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="text" id="weight" v-model="patient['weight(kg)']">
                        </b-form-input>
                    </b-col>
                </b-row>
                <b-row class="hang hang-8">
                    <b-col>
                        <label class="text" for="height">Chiều cao (cm)</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="text" id="height" v-model="patient['height(cm)']">
                        </b-form-input>
                    </b-col>
                </b-row>
                <b-row class="hang hang-9">
                    <b-col>
                        <label class="text">Giới tính</label>
                    </b-col>
                    <b-col>
                        <b-form-group>
                            <b-form-radio v-model="patient.gender" name="gioitinh" value="m">Nam</b-form-radio>
                            <b-form-radio v-model="patient.gender" name="gioitinh" value="f">Nữ</b-form-radio>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row class="hang hang-10">
                    <b-col>
                        <label class="text">Nhóm bệnh nhân</label>
                    </b-col>
                    <b-col>
                        <b-form-radio-group v-model="appointment.patient_group_id">
                            <b-form-radio v-for=" patient_group in formInfo.patient_groups " :key="patient_group.id"
                                :value="patient_group.id" name="nhombenhnhan">
                                {{ patient_group.name }}
                            </b-form-radio>

                        </b-form-radio-group>
                    </b-col>
                </b-row>
                <b-row class="hang hang-11">
                    <h4>2. Chi tiết lịch hẹn</h4>
                </b-row>
                <b-row class="hang hang-12">
                    <b-col>
                        <label class="text">Loại Vaccine</label>
                    </b-col>
                    <b-col>
                        <b-form-select class="select" multiple title="Giữ Ctrl / Command để chọn nhiều"
                            v-model="appointment.vaccines">

                            <b-form-select-option name="vaccine" v-for=" (vaccine, i) in formInfo.vaccines" :key="i"
                                :value="{ vaccine_id : vaccine.id }">
                                {{vaccine.name + " x " + vaccine.number_of_doses}}
                            </b-form-select-option>

                        </b-form-select>
                    </b-col>

                </b-row>

                <b-row class="hang hang-13">
                    <b-col>
                        <label class="text">Giá</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="text" disabled
                            :value=" appointment.total_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') +' đ' ">
                        </b-form-input>
                    </b-col>
                </b-row>

                <b-row class="hang hang-14">
                    <b-col>
                        <label class="text">Ngày hẹn</label>
                    </b-col>
                    <b-col>
                        <b-form-input class="select" type="date" v-model="appointment.date"
                            placeholder="Tháng/Ngày/Năm"></b-form-input>
                    </b-col>
                </b-row>

                <b-row class="hang hang-15">
                    <b-col>
                        <label class="text">Bác sĩ phụ trách</label>
                    </b-col>
                    <b-col>
                        <b-form-select class="select" v-model="appointment.physician_id">

                            <b-form-select-option name="physician" v-for="physician in formInfo.physicians"
                                :key="physician.id" :value="physician.id">
                                {{ physician.name }}
                            </b-form-select-option>

                        </b-form-select>
                    </b-col>

                </b-row>

                <b-row class="hang hang-16">
                    <b-col>
                        <b-button type="submit" variant="primary" @click="handleUpdate">CẬP NHẬT</b-button>
                    </b-col>
                    <b-col>
                        <b-button type="reset" variant="danger" to="/nexus/manage-appointment">HỦY</b-button>
                    </b-col>
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

                formInfo: {},

                patient: {},

                appointment_id: this.$route.params.appointment_id,

                appointment: {
                    patient_id: "",
                    patient_group_id: "",

                    date: "",
                    status: 0,

                    physician_id: "",
                    total_price: 0,

                    vaccines: []
                }
            }
        },
        watch: {
            "appointment.patient_id": function getPatient() {
                this.$axios.get(`http://localhost:8000/patient/${this.appointment.patient_id}`)
                    .then(res => {
                        this.patient = res.data;
                    }).catch(err => console.log(err.response.data))
            }
        },
        methods: {
            getAppointment() {
                this.$axios.get(`http://localhost:8000/appointment/${this.appointment_id}`)
                    .then(res => {

                        // Appointment information
                        this.appointment.patient_group_id = res.data.patient_group_id;
                        this.appointment.physician_id = res.data.physician_id;
                        this.appointment.patient_id = res.data.patient_id;

                        this.appointment.date = res.data.date;
                        this.appointment.status = res.data.status;
                        this.appointment.total_price = res.data.total_price;

                        res.data.vaccines.forEach(
                            (element) => {
                                let object = {}
                                object['vaccine_id'] = element.id
                                this.appointment.vaccines.push(object);
                            }
                        );

                    }).catch(err => console.log(err))
            }
            ,
            getFormInfo() {
                this.$axios.get('http://localhost:8000/create-appointment')
                    .then(res => {
                        this.formInfo = res.data;
                    }).catch(err => console.log(err))
            }
            ,
            handleUpdate() {

                this.$axios.put(`http://localhost:8000/update-patient/${this.appointment.patient_id}`, this.patient)
                    .then(res => {
                        if (res.status === 200) {
                            console.log(res.data.message);
                        }
                    })
                    .catch(err => console.log(err.response.data.message));

                this.$axios.put(`http://localhost:8000/update-appointment/${this.appointment_id}`, this.appointment)
                    .then(res => {
                        if (res.status === 200) {
                            console.log(res.data.message);
                            this.$router.push('/nexus/manage-appointment')
                        }
                    })
                    .catch(err => console.log(err.response.data.message));

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
        mounted() {
            this.$watch(
                (vm) => (vm.appointment.patient_group_id, vm.appointment.vaccines),
                () => {

                    if (this.formInfo.vaccines !== undefined && this.formInfo.patient_groups !== undefined) {

                        let subTotal_price = 0;

                        // watch the vaccines changes
                        let vaccineSelected = this.formInfo.vaccines.filter(
                            (element) => {
                                for (const vaccine of this.appointment.vaccines) {
                                    if (element.id === vaccine.vaccine_id) {
                                        return element;
                                    }
                                }
                            });

                        if (this.appointment.patient_group_id !== '') {

                            // watch the patient group changes
                            let patient_group_selected = this.formInfo.patient_groups.find(element => {
                                if (element.id === this.appointment.patient_group_id) {
                                    return element
                                }
                            });

                            // Cal the sum of price
                            vaccineSelected.forEach((para) => {
                                subTotal_price = subTotal_price + para.number_of_doses * parseFloat(para.price);
                            });

                            this.appointment.total_price = subTotal_price - subTotal_price * patient_group_selected['discount_percentage'];
                        }
                    }

                },
                {
                    immediate: true,
                    deep: true,
                }
            );
        }
        ,
        created() {
            this.getAppointment();
            this.getFormInfo();
            this.listenStorage();
        }
    }   
</script>

<style scoped>
    #edit-appointment {
        max-width: 100%;
        transition: margin-left 0.5s;
        position: relative;

        width: auto;
        height: 100%;
        display: flex;
        flex-wrap: wrap;
        font-family: Arial;
        background-color: #c1e4f7;
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
        background-color: #c1e4f7;
        width: 100%;

    }

    .col {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .row {
        justify-content: space-evenly;
    }

    .text {
        display: flex;
        justify-content: flex-start;
        margin-top: auto;
        margin-bottom: auto;
        font-weight: bold;
    }

    .form-group {
        margin-top: auto;
        margin-bottom: auto;
        text-align: left;
    }

    .h1 {
        font-weight: bold;

    }

    .hang {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .hang-16 {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .select {
        border: 2px solid #d4d3d2;
        border-radius: 5px;
        padding: 5px;
    }
</style>