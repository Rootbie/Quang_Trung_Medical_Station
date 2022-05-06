<template>
    <div id="edit-vacc-records" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        {{items}}
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
            getVaccinationRecords() {
                this.$axios.get(`http://localhost:8000/vaccination-record/${this.$route.params.patient_id}`)
                    .then(res => {
                        if (res.status === 200) {
                            res.data.forEach(appointment => {

                                appointment.vaccines.forEach(vaccine => {
                                    let vaxin = {
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

                            })
                        }
                    })
                    .catch(err => {
                        console.log(err.response.data.message)
                    })
            }
        },
        created() {
            this.getVaccinationRecords()
        }
    }
</script>

<style scoped>
    #edit-vacc-records {
        width: 100%;
        max-width: 100%;
        transition: margin-left 0.5s;

        position: relative;
    }

    /* increase/decrease margin*/
    .add-margin {
        margin-left: 320px;
    }

    .rm-margin {
        margin-left: 0px;
    }
</style>