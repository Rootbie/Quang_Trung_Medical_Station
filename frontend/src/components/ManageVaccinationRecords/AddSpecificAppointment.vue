<template>
    <div class="d-flex w-100 justify-content-center overflow-auto">

        <div id="add-specific-appointment" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
            <b-container>
                <b-row class="hang hang-1">
                    <h1>Thêm lịch hẹn vào sổ tiêm chủng</h1>
                </b-row>

                <b-form @submit="onSubmit">
                    <b-row>
                        <b-col class="cot-1">
                            <b-row class="hang hang-2">
                                <b-col>
                                    <label class="text" for="name">Nhóm bệnh nhân</label>
                                </b-col>

                                <b-col>
                                    <b-form-radio-group v-model="appointment.patient_group_id" required>

                                        <b-form-radio v-for=" patient_group in formInfo.patient_groups "
                                            :key="patient_group.id" :value="patient_group.id" name="nhombenhnhan">
                                            {{ patient_group.name }}
                                        </b-form-radio>

                                    </b-form-radio-group>
                                </b-col>

                            </b-row>

                            <b-row class="hang hang-3">
                                <b-col>
                                    <label class="text" for="input-default">Loại Vaccine</label>
                                </b-col>

                                <b-col>
                                    <b-form-select class="select" multiple title="Giữ Ctrl / Command để chọn nhiều"
                                        v-model="appointment.vaccines" required>

                                        <b-form-select-option name="vaccine" v-for=" (vaccine, i) in formInfo.vaccines"
                                            :key="i" :value="{ vaccine_id : vaccine.id }">
                                            {{vaccine.name + " x " + vaccine.number_of_doses}}
                                        </b-form-select-option>

                                    </b-form-select>
                                </b-col>

                            </b-row>
                            <b-row class="hang hang-4">
                                <b-col>
                                    <label class="text" for="address">Giá tiền</label>
                                </b-col>
                                <b-col>
                                    <b-form-input class="select" type="text" disabled
                                        :value=" appointment.total_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') +' VNĐ' ">
                                    </b-form-input>
                                </b-col>
                            </b-row>

                            <b-row class="hang hang-5">
                                <b-col>
                                    <label class="text" for="input-default">Ngày hẹn</label>
                                </b-col>

                                <b-col>
                                    <b-form-input class="select" type="date" v-model="appointment.date"
                                        placeholder="Tháng/Ngày/Năm" required>
                                    </b-form-input>

                                </b-col>
                            </b-row>

                            <b-row class="hang hang-6">
                                <b-col>
                                    <label class="text" for="input-default">Bác sĩ phụ trách</label>
                                </b-col>

                                <b-col>
                                    <b-form-select class="select" v-model="appointment.physician_id" required>
                                        <b-form-select-option name="physician" v-for="physician in formInfo.physicians"
                                            :key="physician.id" :value="physician.id">
                                            {{ physician.name }}
                                        </b-form-select-option>

                                    </b-form-select>
                                </b-col>

                            </b-row>
                        </b-col>
                    </b-row>

                    <b-row class="hang hang-16">
                        <b-col>
                            <b-button type="submit" variant="primary">TẠO LỊCH HẸN</b-button>
                        </b-col>
                        <b-col>
                            <b-button type="reset" variant="danger"
                                :to="{ name: 'viewVaccinationRecords', params: { patient_id : patient_id } }">HỦY
                            </b-button>
                        </b-col>
                    </b-row>
                </b-form>

            </b-container>
        </div>

    </div>

</template>

<script>
    import translate from "translate"

    export default {
        data: function () {
            return {
                visible: localStorage.getItem('visible'),

                formInfo: {},

                patient_id: this.$route.params.patient_id,

                appointment: {
                    patient_group_id: "",
                    physician_id: "",

                    date: "",
                    status: 0,
                    total_price: 0,

                    vaccines: []
                }
            }
        },
        methods: {
            getFormInfo() {
                this.$axios.get('http://localhost:8000/create-appointment')
                    .then(res => {
                        this.formInfo = res.data
                    })
                    .catch(err => console.log(err))
            },
            onSubmit(event) {
                event.preventDefault()

                this.$axios.post(`http://localhost:8000/add-vaccination-record/${this.patient_id}`,
                    this.appointment
                ).then(res => {
                    if (res.status === 200) {
                        this.$router.push(`/nexus/manage-vaccination-records/view/${this.patient_id}`)
                    }
                }).catch(async (err) => {
                    console.log(await translate(err.response.data.message, "vi"))
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
            this.getFormInfo()
            this.listenStorage()
        }

    }
</script>

<style scoped>
    #add-specific-appointment {
        transition: margin-left 0.5s;
        max-width: 100%;
        position: relative;

        height: 100%;
        width: auto;
        display: flex;
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

        width: 100%;

    }

    .col {
        display: flex;
        flex-direction: column;
    }

    .cot-1 {
        border-radius: 20px;
        background-color: rgb(247, 229, 207);
    }

    .row {
        column-gap: 25px;
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
        padding-bottom: 40px;

    }

    .hang {
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .hang-16 {
        padding-top: 40px;
        padding-bottom: 20px;
    }

    .select {
        border: 2px solid #d4d3d2;
        border-radius: 5px;
        padding: 5px;
    }
</style>