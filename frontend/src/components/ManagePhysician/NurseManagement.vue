/* eslint-disable */
<template>
    <div id="manage-physician" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <div class="container mt-5">
            <div class="">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row justify-content-between">
                            <div class="col-xs-6">
                                <h2>Quản lí bác sĩ</h2>
                            </div>
                            <div class="col-xs-6">
                                <router-link to="/nexus/manage-physician/add" class="btn btn-success"
                                    data-toggle="modal">
                                    <i class="material-icons"> &#xE147; </i> <span>Thêm bác sĩ</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover" id="my-table">
                        <thead>
                            <tr>
                                <!-- dòng -->
                                <th>STT</th> <!-- Cột -->
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Ngày sinh</th>
                                <th>Giới tính</th>
                                <th>Địa chỉ</th>
                                <th>CCCD</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="(physician, index) in displayedPhysicians" :key="index">
                                <td>{{ physician.STT }}</td>
                                <td>{{ physician.name }}</td>
                                <td>{{ physician.phone_number }}</td>
                                <td>{{ physician.date_of_birth }}</td>

                                <td v-if=" physician.gender === 'm' ">Nam</td>
                                <td v-else>Nữ</td>

                                <td>{{ physician.address }}</td>
                                <td>{{ physician.ID_card_number }}</td>
                                <td>
                                    <router-link :to="{ name: 'editPhysician', params: {id: physician.id}}"
                                        class="edit">
                                        <i class="material-icons" data-toggle="tooltip" title="Cập nhật">&#xE254;</i>
                                    </router-link>

                                </td>
                                <td>
                                    <DeletePopup @returnConfirm="returnConfirm" :object="physician" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="clearfix">
                        <ul class="pagination">
                            <li class="page-item " v-if="page != 1" @click="page--" ><a href="#" class="page-link">Trang trước</a></li>
                            <li class="page-item " v-for=" (pageNumber, i) in pages.slice(page-1, page+5)" :key="i" @click="page = pageNumber" >
                                <a href="#" class="page-link"  > {{pageNumber}} </a>
                            </li>

                            <li class="page-item" @click="page++" v-if="page < pages.length" ><a href="#" class="page-link"   >Tiếp theo</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import DeletePopup from './DeletePhysician.vue'

    export default {
        components: { DeletePopup },

        data: function () {
            return {
                visible: localStorage.getItem('visible'),

                page: 1,
                perPage: 5,
                pages: [],

                physicians: [],
                physicianID: ''
            }
        },
        computed: {
            displayedPhysicians() {
                return this.paginate(this.physicians);
            }
        },
        watch: {
            physicians() {
                this.setPages();
            }
        }
        ,
        methods: {
            returnConfirm(confirmValue, physicianID) {
                this.physicianID = physicianID;
                confirmValue === true ? this.removePhysician() : null
            }
            ,
            getPhysicians() {
                this.$axios.get('http://localhost:8000/listUser').then(res => {
                    this.physicians = res.data

                    // Add STT attribute to each physician
                    for (let k = 0; k < this.physicians.length; k++){
                        this.physicians[k]['STT'] = k+1; 
                    }

                }).catch(err => console.log(err))
            }
            ,
            removePhysician() {
                this.$axios.delete(`http://localhost:8000/deleteUser/${this.physicianID}`)
                    .then(res => {
                        if (res.status === 200) {
                            this.$router.go()
                        }
                    })
                    .catch(err => { console.log(err) })
            }
            ,
            setPages() {
                let numberOfPages = Math.ceil(this.physicians.length / this.perPage);
                for (let index = 1; index <= numberOfPages; index++) {
                    this.pages.push(index);
                }
            }
            ,
            paginate(physicians) {
                let page = this.page;
                let perPage = this.perPage;
                let from = (page * perPage) - perPage;
                let to = (page * perPage);

                return physicians.slice(from, to);
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
        created() {
            this.getPhysicians();
            this.listenStorage();
        }
    }
</script>


<style scoped>
    #manage-physician {
        width: 100%;
        max-width: 100%;
        transition: margin-left 0.5s;

        position: relative;

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


    /* dòng đầu tiên */
    body {
        color: #566787;
        background-color: #ffffff;
        font-family: 'Varela Round', sans-serif;
        font-size: 17px;
    }

    .table-responsive {
        margin: auto;
    }

    .table-wrapper {
        min-width: 1000px;
        width: 100%;
        background: #fff;
        padding: 20px 25px 0px 25px;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table-title {
        padding-bottom: 15px;
        background: #435d7d;
        color: #fff;
        padding: 16px 30px;
        margin: -20px -25px 10px;
        border-radius: 3px 3px 0 0;
    }

    .table-title h2 {
        margin: 5px 0 0;
        font-size: 24px;
    }

    .table-title .btn {
        color: #fff;
        float: right;
        font-size: 13px;
        border: none;
        min-width: 50px;
        border-radius: 2px;
        border: none;
        outline: none !important;
        margin-left: 10px;
    }

    .table-title .btn i {
        float: left;
        font-size: 21px;
        margin-right: 5px;
    }

    .table-title .btn span {
        float: left;
        margin-top: 2px;
    }

    table.table tr th,
    table.table tr td {
        border-color: #e9e9e9;
        padding: 12px 15px;
        vertical-align: middle;
    }

    table.table tr th:first-child {
        width: 100px;
    }

    table.table tr th:last-child {
        width: 100px;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #fcfcfc;
    }

    table.table-striped.table-hover tbody tr:hover {
        background: #f5f5f5;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table td:last-child i {
        opacity: 0.9;
        font-size: 22px;
        margin: 0 5px;
    }

    table.table td a {
        font-weight: bold;
        color: #566787;
        display: inline-block;
        text-decoration: none;
        outline: none !important;
    }

    table.table td a:hover {
        color: #2196F3;
    }

    table.table td a.edit {
        color: #FFC107;
    }

    table.table td a.delete {
        color: #F44336;
    }

    table.table td i {
        font-size: 19px;
    }

    .pagination {
        float: right;
        margin: 0 0 5px;
    }

    .pagination li a {
        border: none;
        font-size: 13px;
        min-width: 30px;
        min-height: 30px;
        color: #999;
        margin: 0 2px;
        line-height: 30px;
        border-radius: 2px !important;
        text-align: center;
        padding: 0 6px;
        /* khoang cach giua cac so*/
    }

    .pagination li a:hover {
        color: #666;
    }

    .pagination li.active a,
    .pagination li.active a.page-link {
        background: #03A9F4;
    }

    .pagination li.active a:hover {
        background: #0397d6;
    }

    .pagination li.disabled i {
        color: #ccc;
    }

    .pagination li i {
        font-size: 16px;
    }

    .hint-text {
        float: left;
        margin-top: 10px;
        font-size: 13px;
    }
</style>