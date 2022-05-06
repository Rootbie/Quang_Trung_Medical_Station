<template>
    <div id="edit-vaccine" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <div class="container">

            <div class="">
                <h1 class="card-title mt-3 text-center" style="color: rgb(48, 20, 10);">Sửa thông tin Vaccine</h1>

                <article class="card-body" id="card1" style="max-width: 400px;background-color: rgb(247, 229, 207);">
                    <b-form @submit="onSubmit">

                        <b-form-group id="input-group-1" label="Lô Thuốc:" label-for="input-1">
                            <b-form-input id="input-1" v-model="vaccine.lot_number" type="text" required></b-form-input>
                        </b-form-group>

                        <b-form-group id="input-group-2" label="Tên vaccine:" label-for="input-2">
                            <b-form-input id="input-2" v-model="vaccine.name" required></b-form-input>
                        </b-form-group>

                        <b-form-group id="input-group-3" label="Bệnh:" label-for="input-3">
                            <b-form-input id="input-3" v-model="vaccine.diseases" required></b-form-input>
                        </b-form-group>

                        <b-form-group id="input-group-4" label="Số mũi tiêm:" label-for="input-4">
                            <b-form-input type="number" min="0" id="input-4" v-model="vaccine.number_of_doses" required>
                            </b-form-input>
                        </b-form-group>

                        <b-form-group id="input-group-5" label="Thời điểm tiêm:" label-for="input-5">
                            <b-form-input id="input-5" v-model="vaccine.starting_time_of_doses">
                            </b-form-input>
                        </b-form-group>

                        <label for="fname">Khoảng cách giữa các mũi tiêm:</label>
                        <div class="form-group input-group">
                            <div class="input-group-prepend"></div>

                            <b-form-spinbutton id="sb-inline" style="width: 140px;"
                                v-model="vaccine['interval_between_doses(week)']" inline>
                            </b-form-spinbutton>
                            <!-- <input class="form-control currency" type="text" style="width: 140px;"> -->
                            <span class="form-control" style="border-left: 1px solid #ced4da;">Tuần</span>
                        </div> <!-- form-group// -->

                        <label for="fname">Giá tiền:</label>
                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                            </div>
                            <input class="form-control currency" type="text" style="width: 140px;"
                                v-model="vaccine.price">
                            <span class="form-control" style="border-left: 1px solid #ced4da;">VND</span>
                        </div> <!-- form-group// -->

                        <article class="card-body" style="max-width: 150px;" id="card3">
                            <div class="form-group">
                                <b-button type="submit" class="btn btn-primary btn-block"
                                    style="background-color: #007bff;border-color: #007bff;"> Lưu </b-button>
                            </div> <!-- form-group// -->
                        </article>

                        <article class="card-body" style="max-width: 150px;" id="card4">
                            <div class="form-group">
                                <b-button class="btn btn-primary btn-block"
                                    style="background-color:red;border-color: red;" to="/nexus/manage-vaccines"> Hủy
                                </b-button>
                            </div> <!-- form-group// -->
                        </article>

                    </b-form>
                </article>

            </div>
        </div>
    </div>
</template>

<script>
    import translate from "translate"

    export default {
        data() {
            return {
                visible: localStorage.getItem('visible'),
                vaccine: {}
            }
        },
        methods: {
            getVaccineById() {
                this.$axios.get(`http://localhost:8000/vaccine/${this.$route.params.vaccine_id}`)
                    .then(res => {
                        this.vaccine = res.data
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            onSubmit(event) {
                event.preventDefault()
                this.$axios.put(`http://localhost:8000/update-vaccine/${this.$route.params.vaccine_id}`, this.vaccine)
                    .then(res => {
                        if (res.status === 200) {
                            console.log(res.data.message)
                            this.$router.push('/nexus/manage-vaccines')
                        }
                    })
                    .catch(async (err) => {
                        const message = await translate(err.response.data.message, "vi")
                        console.log(message)
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
            this.getVaccineById()
            this.listenStorage()
        }

    }
</script>

<style scoped>
    #edit-vaccine {
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

    #card1 {
        margin-left: 340px;
        border-radius: 20px;

    }

    #card4 {
        margin-top: -94px;
        margin-left: 200px;
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