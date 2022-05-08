<template>
    <div id="manage-vacc-records" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <div class="danh-sach">
            <b-row class="hang-1">
                <h1>Quản lý sổ tiêm chủng</h1>
            </b-row>

            <b-row class="hang-2">

                <b-col class="cot-2-2">
                    <b-row class="tim-kiem">
                        <b-form-input v-model="filter" type="search" placeholder="Tìm kiếm"></b-form-input>
                    </b-row>
                </b-col>
            </b-row>

            <b-row class="hang-3">
                <b-table hover head-variant="dark" id="my-table" :items="items" :fields="fields" :filter="filter"
                    :per-page="perPage" :current-page="currentPage" @filtered="onFiltered">

                    <template #cell(actions)="row">
                        <b-button variant="info"
                            :to="{ name: 'viewVaccinationRecords', params: { patient_id : row.item.id } }">CHI TIẾT
                        </b-button>
                    </template>

                </b-table>
            </b-row>

            <b-row class="hang-4">
                <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" aria-controls="my-table">
                </b-pagination>
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
                    { key: 'STT', label: 'STT' },
                    { key: 'name', label: 'Họ và tên' },
                    { key: 'date_of_birth', label: 'Ngày sinh' },
                    { key: 'gender', label: 'Giới tính' },
                    { key: 'address', label: 'Địa chỉ' },
                    { key: 'phone_number', label: 'Số điện thoại' },
                    { key: 'actions', label: 'Tác vụ' }
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
            getPatient() {
                this.$axios.get(`http://localhost:8000/patient`)
                    .then(res => {
                        this.items = res.data.map((patient, index) => {
                            return {
                                id: patient.id,
                                STT: index + 1,
                                name: patient.name,
                                date_of_birth: patient.date_of_birth,
                                gender: patient.gender,
                                address: patient.address,
                                phone_number: patient.phone_number
                            }
                        })
                    }).catch(err => {
                        console.log(err.response.data.message)
                    })
            },
            listenStorage() {
                const localStorageSetHandler = (e) => {
                    // e.value set to String because the 1st value in local storage is string
                    (e.key === "visible") ? this.visible = String(e.value) : null
                }
                // Listen for changes with localStorage on the same window
                document.addEventListener("showSideBar", localStorageSetHandler, false)
            }
        },
        created() {
            this.getPatient();
            this.listenStorage();
        }
    }
</script>

<style scoped>
    #manage-vacc-records {
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

    .cot-2-2 {
        display: flex;
        justify-content: flex-end;
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
        bottom: 0;
        left: 50%;
        right: 50%;
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