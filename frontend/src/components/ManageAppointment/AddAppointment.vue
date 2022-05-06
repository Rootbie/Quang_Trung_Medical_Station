<template>
    <div id="add-appointment" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"
            id="bootstrap-css">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


        <div class="container">
            <div class="">
                <h1 class="card-title mt-3 text-center" style="color: rgb(48, 20, 10);">Thêm Lịch Hẹn</h1>

                <article class="card-body" id="card1" style="max-width: 400px;background-color: rgb(247, 229, 207);">
                    <p class="card-title mt-3 text-left"><strong>1.Thông tin cá nhân</strong></p>

                    <form @submit.prevent>
                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size: 25px;"></i>
                                </span>
                            </div>
                            <input v-model="appointment.name" class="form-control" placeholder="Họ và tên" type="text"
                                required>
                        </div> <!-- form-group// -->

                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar" style="font-size: 20px;"></i>
                                </span>
                            </div>
                            <input v-model="appointment.date_of_birth" class="form-control" id="datepicker" type="date"
                                placeholder="Tháng/Ngày/Năm" required>
                        </div> <!-- form-group// -->

                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fas fa-home"> </i> </span>
                            </div>
                            <input v-model="appointment.address" class="form-control" placeholder="Địa chỉ"
                                type="address" required>
                        </div> <!-- form-group// -->

                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-phone" style="font-size: 24px;"> </i>
                                </span>
                            </div>
                            <select class="custom-select" style="max-width: 120px;">
                                <option selected="">+84</option>
                            </select>
                            <input v-model="appointment.phone_number" class="form-control" placeholder="Số điện thoại"
                                type="text" required>
                        </div> <!-- form-group// -->


                        <label for="fname">Giới tính:</label>
                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-intersex" style="font-size: 23px;"> </i>
                                </span>
                            </div>
                            <select class="form-control" v-model="appointment.gender">
                                <option disabled value="">Chọn giới tính</option>
                                <option value="m">Nam</option>
                                <option value="f">Nữ</option>
                            </select>
                        </div> <!-- form-group end.// -->

                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-child" style="font-size: 25px;"> </i>
                                </span>
                            </div>
                            <input class="form-control" placeholder="Chiều cao (cm)" type="text"
                                style="max-width: 140px;" v-model="appointment.height">

                            <input class="form-control" placeholder="Cân nặng (kg)" type="text"
                                v-model="appointment.weight">
                        </div> <!-- form-group// -->

                        <label for="fname">Nhóm bệnh nhân:</label>
                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-money" style="font-size: 18px;"> </i>
                                </span>
                            </div>
                            <select class="form-control" v-model="appointment.patient_group_id" required>
                                <option disabled value="">Chọn 1 nhóm</option>

                                <option v-for=" patient_group in formInfo.patient_groups " :key="patient_group.id"
                                    :value="patient_group.id">{{ patient_group.name }}</option>
                            </select>
                        </div> <!-- form-group end.// -->

                    </form>
                </article>

                <article class="card-body" id="card2" style="max-width: 400px;background-color: rgb(247, 229, 207);">
                    <p class="card-title mt-3 text-left"><strong>2.Chi tiết lịch hẹn</strong></p>
                    <label for="fname">Lí do:</label>

                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-comments"> </i> </span>
                        </div>
                        <select class="form-control">
                            <option selected="">Tiêm vaccine</option>
                            <option>Tư vấn sức khỏe</option>
                            <option>Khai báo y tế</option>
                            <option>Uống Vitamin</option>
                            <option>Khám nha khoa</option>
                            <option>Khác</option>
                        </select>
                    </div> <!-- form-group end.// -->

                    <label for="fname">Loại vaccine:</label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-syringe" style="font-size: 15,5px;"> </i>
                            </span>
                        </div>

                        <select class="form-control" v-model="appointment.vaccines" multiple>
                            <option v-for=" (vaccine, i) in formInfo.vaccines" :key="i"
                                :value="{ vaccine_id : vaccine.id }">
                                {{vaccine.name + " x " + vaccine.number_of_doses}}
                            </option>
                        </select>

                        <!-- <input class="form-control currency" type="text" style="width: 140px;"> -->
                    </div> <!-- form-group// -->

                    <label for="fname">Giá tiền:</label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-money" style="font-size: 16,5px;"> </i>
                            </span>
                        </div>
                        <input class="form-control currency" readonly
                            :value=" appointment.total_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') "
                            type="text" style="width: 140px;">

                        <span class="form-control" style="border-left: 1px solid #ced4da;">VNĐ</span>
                    </div> <!-- form-group// -->

                    <label for="fname">Ngày hẹn:</label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-calendar" style="font-size: 19,5px;"></i>
                            </span>
                        </div>
                        <input v-model="appointment.date" class="form-control" type="date" placeholder="Tháng/Ngày/Năm"
                            required>
                    </div> <!-- form-group// -->

                    <label for="fname">Bác sĩ chỉ định:</label>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user-md" style="font-size: 21px;"> </i>
                            </span>
                        </div>

                        <select class="form-control" v-model="appointment.physician_id">
                            <option disabled value="">Chọn bác sĩ</option>
                            <option v-for="physician in formInfo.physicians" :key="physician.id" :value="physician.id">
                                {{ physician.name }}
                            </option>

                        </select>
                    </div> <!-- form-group end.// -->

                </article>

                <article class="card-body" style="max-width: 200px;" id="card3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" @click="handleAdd">Tạo lịch hẹn
                        </button>
                    </div> <!-- form-group// -->

                </article>

                <article class="card-body" style="max-width: 200px;" id="card4">
                    <div class="form-group">
                        <b-button type="submit" class="btn btn-primary btn-block"
                            style="background-color:red;border: none;" to="/nexus/manage-appointment">Hủy
                        </b-button>
                    </div> <!-- form-group// -->

                </article>
            </div> <!-- card.// -->

        </div>
        <!--container end.//-->
    </div>
</template>

<script>
    import translate from "translate"

    export default {
        data: function () {
            return {
                visible: localStorage.getItem('visible'),

                formInfo: {},

                appointment: {
                    name: "",
                    phone_number: "",
                    date_of_birth: "",
                    gender: "",
                    address: "",
                    height: "",
                    weight: "",

                    patient_group_id: "",

                    date: "",
                    status: 0,

                    physician_id: "",
                    total_price: 0,

                    vaccines: []
                }
            }

        },

        methods: {

            getFormInfo() {
                this.$axios.get('http://localhost:8000/create-appointment')
                    .then(res => {
                        this.formInfo = res.data;
                    })
                    .catch(err => console.log(err))
            }
            ,
            handleAdd() {
                this.$axios.post('http://localhost:8000/add-appointment',
                    this.appointment
                ).then(res => {
                    if (res.status === 200) {
                        this.$router.push('/nexus/manage-appointment')
                    }
                }).catch(async (err) => {
                    console.log(await translate(err.response.data.message, "vi"))
                })
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
        },
        created() {
            this.getFormInfo();
            this.listenStorage();
        }

    }
</script>

<style scoped>
    #add-appointment {
        max-width: 100%;
        transition: margin-left 0.5s;
        position: relative;

        width: 100%;
        height: 100%;

        display: flex;
        overflow: auto;
    }

    /* increase/decrease margin*/
    .add-margin {
        margin-left: 170px;
    }

    .rm-margin {
        margin-left: 0px;
    }


    #card2 {
        margin-left: 570px;
        margin-top: -536px;
        padding-bottom: 34px;
        border-radius: 20px;
    }

    #card1 {
        margin-left: 140px;
        border-radius: 20px;

    }

    #card3 {
        margin-top: 6px;
        margin-left: 360px;
    }

    #card4 {
        margin-top: -94px;
        margin-left: 550px;
    }

    input[type="date"]::before {
        color: #616161;
        content: attr(placeholder);
    }

    input[type="date"] {
        color: #ffffff;
    }

    input[type="date"]:focus,
    input[type="date"]:valid {
        color: #666666;
    }

    input[type="date"]:focus::before,
    input[type="date"]:valid::before {
        content: "" !important;
    }

    .card {
        width: 100%;
        margin: auto;
        /* height: 100%; */
        /* background-image: url('../assets/background.jpg'); */
        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;

    }

    .divider-text {
        position: relative;
        text-align: center;
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .divider-text span {
        padding: 7px;
        font-size: 12px;
        position: relative;
        z-index: 2;
    }

    .divider-text:after {
        content: "";
        position: absolute;
        width: 100%;
        border-bottom: 1px solid #ddd;
        top: 55%;
        left: 0;
        z-index: 1;
    }
</style>