<template>
    <div id="edit-vacc-records" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <div class="danh-sach">
            <b-row class="hang-1">
                <h1>Cập nhật sổ tiêm chủng</h1>
            </b-row>

            <b-row class="hang-2">
                <b-form-input v-model="filter" type="search" placeholder="Tìm kiếm"></b-form-input>
            </b-row>

            <b-row class="hang-3">

                <b-table hover head-variant="dark" id="my-table" :items="items" :fields="fields" :per-page="perPage"
                    :current-page="currentPage" @filtered="onFiltered" :filter="filter">

                    <template #cell(first_dose)="row">
                        <b-form-datepicker start-weekday="1" locale="vi" show-decade-nav
                            :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                            v-model="row.item.first_dose" placeholder="Chọn ngày">
                        </b-form-datepicker>
                    </template>

                    <template #cell(second_dose)="row">
                        <b-form-datepicker start-weekday="1" locale="vi" show-decade-nav
                            :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                            v-model="row.item.second_dose" placeholder="Chọn ngày">
                        </b-form-datepicker>
                    </template>

                    <template #cell(third_dose)="row">
                        <b-form-datepicker start-weekday="1" locale="vi" show-decade-nav
                            :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                            v-model="row.item.third_dose" placeholder="Chọn ngày">
                        </b-form-datepicker>
                    </template>

                    <template #cell(additional_dose)="row">
                        <b-form-datepicker start-weekday="1" locale="vi" show-decade-nav
                            :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                            v-model="row.item.additional_dose" placeholder="Chọn ngày">
                        </b-form-datepicker>
                    </template>

                    <template #cell(booster_dose)="row">
                        <b-form-datepicker start-weekday="1" locale="vi" show-decade-nav
                            :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                            v-model="row.item.booster_dose" placeholder="Chọn ngày">
                        </b-form-datepicker>
                    </template>

                    <template #cell(actions)="row">
                        <b-button variant="primary" @click="handleSave(row.item)">LƯU</b-button>
                    </template>
                </b-table>

            </b-row>

            <b-row class="hang-4">
                <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" aria-controls="my-table">
                </b-pagination>
            </b-row>

            <b-row class="hang-5">
                <b-button variant="secondary"
                    :to="{ name : 'viewVaccinationRecords', params : { patient_id : patient_id } }">TRỞ VỀ
                </b-button>
            </b-row>

        </div>
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
                visible: localStorage.getItem('visible'),
                appointment_id: this.$route.params.appointment_id,
                patient_id: this.$route.params.patient_id,

                items: [],

                fields: [
                    { key: 'appointment_id', label: "Mã lịch hẹn" },
                    { key: 'name', label: "Tên thuốc" },
                    { key: 'lot_number', label: "Lô thuốc" },
                    { key: 'first_dose', label: "Mũi 1" },
                    { key: 'second_dose', label: "Mũi 2" },
                    { key: 'third_dose', label: "Mũi 3" },
                    { key: 'additional_dose', label: "Mũi nhắc lại" },
                    { key: "booster_dose", label: "Mũi bổ sung" },
                    { key: "actions", label: "Tác vụ" },
                ],

                perPage: 5,

                rows: 1,
                currentPage: 1,
                filter: null
            }
        },
        watch: {
            "items": function () {
                this.rows = this.items.length
            }
        },
        methods: {
            onFiltered(filteredItems) {
                this.rows = filteredItems.length
                this.currentPage = 1
            },
            getVaccinationRecords() {
                this.$axios.get(`http://localhost:8000/vaccination-record/${this.patient_id}`)
                    .then(res => {
                        if (res.status === 200) {
                            res.data.forEach(appointment => {

                                if (appointment.id === this.appointment_id) {

                                    appointment.vaccines.forEach(vaccine => {
                                        let vaxin = {
                                            vaccination_record_id: vaccine['pivot']['id'],
                                            appointment_id: vaccine['pivot']['appointment_id'],
                                            name: vaccine.name,
                                            lot_number: vaccine.lot_number,

                                            first_dose: vaccine['pivot']['first_dose'],
                                            second_dose: vaccine['pivot']['second_dose'],
                                            third_dose: vaccine['pivot']['third_dose'],
                                            additional_dose: vaccine['pivot']['additional_dose'],
                                            booster_dose: vaccine['pivot']['booster_dose']
                                        }

                                        this.items.push(vaxin)
                                    })
                                }
                            })
                        }

                    })
                    .catch(err => {
                        console.log(err.response.data.message)
                    })
            },
            handleSave(item) {
                this.$axios.put(`http://localhost:8000/update-vaccination-record/${item.vaccination_record_id}`,
                    item
                ).then(res => {
                    if (res.status === 200) {
                        this.items = [];
                        this.getVaccinationRecords();
                    }
                }).catch(err => {
                    console.log(err)
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
            this.getVaccinationRecords()
            this.listenStorage()
        }
    }
</script>

<style scoped>
    #edit-vacc-records {
        width: 100%;
        max-width: 100%;
        transition: margin-left 0.5s;

        position: relative;

        display: flex;
        justify-content: space-evenly;
        overflow: auto;
    }

    /* increase/decrease margin*/
    .add-margin {
        margin-left: 260px;
    }

    .rm-margin {
        margin-left: 0px;
    }

    .danh-sach {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        width: 1500px;
        position: relative;
    }


    .hang-1 {
        display: flex;
        justify-content: center;

    }

    .hang-2 {
        display: flex;
        justify-content: flex-end;
        margin-left: auto; /* <--- */
    }


    .nut {
        position: absolute;
    }

    .hang-3 {
        display: flex;
        justify-content: center;
    }

    .table {
        text-align: center;
        position: relative;
        top: 8px;
    }

    .hang-4 {
        display: flex;
        justify-content: center;
        position: absolute;
        bottom: 50px;
        left: 50%;
        right: 50%;
    }

    .hang-5 {
        display: flex;
        justify-content: center;
        position: absolute;
        bottom: 20px;
        left: 47%;
        right: 47%;
    }

    .btn-dark {
        margin-right: 3px;
    }

    .btn-info {
        margin-right: 16px;
        margin-left: 16px;
    }

    .custom-control-label {
        margin-left: 10px;
    }
</style>