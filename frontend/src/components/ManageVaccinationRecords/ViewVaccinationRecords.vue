<template>
    <div id="view-vacc-records" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <div class="danh-sach">
            <b-row class="hang-1">
                <h1>Quản lý sổ tiêm chủng</h1>
            </b-row>

            <b-row class="hang-2">
                <b-col class="cot-2-1">
                    <b-button variant="outline-primary" class="them-lich"
                        :to="{ name : 'addSpecificAppointment', params : {patient_id : $route.params.patient_id} }">
                        + Lịch hẹn
                    </b-button>
                </b-col>
                
                <b-col class="cot-2-2">
                    <b-row class="tim-kiem">
                        <b-form-input v-model="filter" type="search" placeholder="Tìm kiếm"></b-form-input>
                    </b-row>
                </b-col>
            </b-row>

            <b-row class="hang-3">
                <b-table hover head-variant="dark" id="my-table" :items="items" :fields="fields" :filter="filter"
                    :per-page="perPage" :current-page="currentPage" @filtered="onFiltered">

                    <template #cell(status)="row">
                        <b-form-checkbox v-model="row.item.status" disabled value="1" unchecked-value="0" size="lg">
                        </b-form-checkbox>
                    </template>

                    <template #cell(actions)="row">
                        <b-button variant="info"
                            :to="{ name: 'editVaccinationRecords', params : { patient_id : row.item.patient_id, appointment_id : row.item.id } }">
                            Chi tiết
                        </b-button>

                    </template>

                </b-table>
            </b-row>

            <b-row class="hang-4">
                <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" aria-controls="my-table">
                </b-pagination>
            </b-row>

            <b-row class="hang-5">
                <b-button variant="secondary" to="/nexus/manage-vaccination-records">TRỞ VỀ</b-button>
            </b-row>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                visible: localStorage.getItem('visible'),

                items: [],

                fields: [
                    { key: 'id', label: "Mã lịch hẹn" },
                    { key: 'patient_id', label: "Mã bệnh nhân" },
                    { key: 'physician_id', label: "Mã bác sĩ phụ trách" },
                    { key: 'date', label: "Ngày hẹn" },
                    { key: 'formatted_total_price', label: "Chi phí" },
                    { key: 'status', label: "Trạng thái" },
                    { key: 'patient_group_id', label: "Nhóm bệnh nhân" },
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
            getAppointment() {
                this.$axios.get(`http://localhost:8000/vaccination-record/${this.$route.params.patient_id}`)
                    .then(res => {
                        if (res.status === 200) {
                            this.items = res.data.map(appointment => {

                                return {
                                    id: appointment.id,
                                    patient_id: appointment.patient_id,
                                    physician_id: appointment.physician_id,
                                    patient_group_id: appointment.patient_group_id,

                                    date: appointment.date,
                                    status: appointment.status,

                                    total_price: appointment.total_price,
                                    formatted_total_price: appointment.total_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' đ'
                                }
                            })
                        }
                    })
                    .catch(err => {
                        console.log(err.response.data.message)
                    })
            },
            updateStatusCheck(appointment_id) {
                //  @click.native="updateStatusCheck(row.item.id)" <-- pass an 'id' parameter instead of 'event' parameter

                const checkedAppointment = this.items.find(item => item.id === appointment_id);
                delete checkedAppointment['formatted_total_price'];

                this.$axios.put(`http://localhost:8000/update-appointment/${appointment_id}`, checkedAppointment)
                    .then(res => {
                        if (res.status === 200) {
                            console.log(res.data.message);
                        }
                    })
                    .catch(err => console.log(err.response.data.message));
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
            this.getAppointment()
            this.listenStorage()
        }
    }
</script>

<style scoped>
    #view-vacc-records {
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
        width: auto;
        position: relative;
    }


    .hang-1 {
        display: flex;
        justify-content: center;

    }

    .hang-2 {
        display: flex;
        justify-content: flex-start;
    }

    .cot-2-1 {
        position: relative;
        left: -15px;
    }

    .tim-kiem {
        display: flex;
        justify-content: flex-end;
        position: relative;
    }

    .form-control {
        width: 200px;
        margin-right: 90px;
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

    .form-control {
        margin-right: 0;
        margin-left: 200px;
    }

    .custom-control-label {
        margin-left: 10px;
    }
</style>