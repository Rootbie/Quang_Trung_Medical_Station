<template>
    <div id="manage-vaccines" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <div class="danh-sach">
            <b-row class="hang-1">
                <h1>Quản lý Vắc-xin</h1>
            </b-row>

            <b-row class="hang-2">
                <b-col class="cot-2-1">
                    <b-button variant="outline-primary" class="them-lich" to="/nexus/manage-vaccines/add">+ Vắc-xin
                    </b-button>
                </b-col>
                <b-col class="cot-2-2">
                    <b-row class="tim-kiem">
                        <b-form-input v-model="filter" type="search" placeholder="Tìm kiếm"></b-form-input>
                    </b-row>
                </b-col>
            </b-row>

            <b-row class="hang-3">
                <b-table hover head-variant="dark" id="vaccines-table" :items="items" :fields="fields" :filter="filter"
                    :per-page="perPage" :current-page="currentPage" @filtered="onFiltered">

                    <template #cell(actions)="row">
                        <b-button v-if="row.item.status == 1" variant="info">Chi tiết</b-button>

                        <template v-else>
                            <b-button variant="dark" :to="{name:'editVaccine', params: {vaccine_id : row.item.id} }">
                                Sửa
                            </b-button>
                            <b-button variant="danger" @click="showMsgBoxConfirm(row.item.id)">Xóa</b-button>
                        </template>

                    </template>

                </b-table>
            </b-row>

            <b-row class="hang-4">
                <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage"
                    aria-controls="vaccines-table">
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
                    { key: 'name', label: "Tên", tdClass: 'text-left' },
                    { key: 'diseases', label: "Loại bệnh" },
                    { key: 'lot_number', label: "Số lô" },
                    { key: 'number_of_doses', label: "Số mũi yêu cầu" },
                    { key: 'starting_time_of_doses', label: "Thời điểm tiêm" },
                    { key: 'interval_between_doses', label: "Khoảng cách giữa các mũi (tuần)" },
                    { key: 'price', label: "Giá" },
                    { key: 'actions', label: "Tác vụ" },
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
            getVaccine() {
                this.$axios.get('http://localhost:8000/vaccine')
                    .then(res => {
                        if (res.status === 200) {
                            this.items = res.data.map(vaccine => {
                                return {
                                    id: vaccine.id,
                                    name: vaccine.name,
                                    diseases: vaccine.diseases,
                                    lot_number: vaccine.lot_number,
                                    number_of_doses: vaccine.number_of_doses,
                                    starting_time_of_doses: vaccine.starting_time_of_doses,
                                    interval_between_doses: vaccine['interval_between_doses(week)'],
                                    price: vaccine.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' đ'
                                }
                            })
                        }
                    })
                    .catch(err => console.log(err))
            },
            removeVaccine(vaccine_id) {
                this.$axios.delete(`http://localhost:8000/delete-vaccine/${vaccine_id}`)
                    .then(res => {
                        if (res.status === 200) {
                            this.getVaccine()
                        }
                    })
                    .catch(err => console.log(err))
            },
            showMsgBoxConfirm(vaccine_id) {
                this.$bvModal.msgBoxConfirm('Bạn có chắc muốn xóa không?', {
                    title: 'Xóa vắc xin này khỏi danh sách',
                    size: 'sm',
                    buttonSize: 'md',
                    okVariant: 'danger',
                    okTitle: 'CÓ',
                    cancelTitle: 'KHÔNG',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                    .then(value => {
                        value === true ? this.removeVaccine(vaccine_id) : null
                    })
                    .catch(err => {
                        // An error occurred
                        console.log(err);
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
            this.getVaccine()
            this.listenStorage()
        }
    }
</script>

<style scoped>
    #manage-vaccines {
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

    .form-control {
        margin-right: 0;
        margin-left: 200px;
    }

    .custom-control-label {
        margin-left: 10px;
    }
</style>