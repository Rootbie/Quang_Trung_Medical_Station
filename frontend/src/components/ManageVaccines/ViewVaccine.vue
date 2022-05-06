<template>
    <div>
        <div class="container">
            <div class="card">
                <h1 class="card-title mt-3 text-center" style="color: rgb(48, 20, 10);">Sửa thông tin Vaccine</h1>
                <article class="card-body" id="card1" style="max-width: 400px;background-color: rgb(247, 229, 207);">
      <b-form @submit="onSubmit" @reset="onReset" v-if="show">
        <b-form-group
          id="input-group-1"
          label="Lô Thuốc:"
          label-for="input-1"
        >
          <b-form-input
            id="input-1"
            v-model="form.text"
            type="text"
            required
          ></b-form-input>
        </b-form-group>

        <b-form-group id="input-group-2" label="Tên vaccine:" label-for="input-2">
          <b-form-input
            id="input-2"
            v-model="form.text"
            required
          ></b-form-input>
        </b-form-group>
  <b-form-group id="input-group-2" label="Bệnh:" label-for="input-2">
          <b-form-input
            id="input-2"
            v-model="form.text"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-3" label="Số mũi tiêm:" label-for="input-3">
            <b-form-input
            id="input-2"
            v-model="form.number"
            required
          ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-3" label="Chọn ngày Tiêm:" label-for="input-3">
            <b-form-datepicker locale="vi" id="datetimepicker2" v-model="value" class="mb-2"></b-form-datepicker>
          </b-form-group>

          <label for="fname">Khoảng cách giữa các mũi tiêm:</label>
                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                            </div>
                            <b-form-spinbutton id="sb-inline" style="width: 140px;" v-model="value" inline ></b-form-spinbutton>
                            <!-- <input class="form-control currency" type="text" style="width: 140px;"> -->
                            <span class="form-control" style="border-left: 1px solid #ced4da;">Ngày</span>
                        </div> <!-- form-group// -->

        <label for="fname">Giá tiền:</label>
                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                            </div>
                            <input class="form-control currency" type="text" style="width: 140px;">
                            <span class="form-control" style="border-left: 1px solid #ced4da;">VND</span>
                        </div> <!-- form-group// -->
                        <article class="card-body" style="max-width: 150px;" id="card3" >
                            <div class="form-group">
                                <b-button type="submit" class="btn btn-primary btn-block" style="background-color: #007bff;border-color: #007bff;"> Lưu </b-button>
                            </div> <!-- form-group// -->
                        
                    </article>
                    <article class="card-body" style="max-width: 150px;" id="card4" >
                        <div class="form-group">
                            <b-button type="submit" class="btn btn-primary btn-block" style="background-color:red;border-color: red;"> Hủy</b-button>
                        </div> <!-- form-group// -->
                    
                </article>
        <!-- <b-button type="submit" variant="primary">Submit</b-button>
        <b-button type="reset" variant="danger">Reset</b-button> -->
      </b-form>
    </article>
    </div>
        </div>
    </div>
  </template>

  <script>
    export default {
      data() {
        return {
          form: {
            email: '',
            name: '',
            food: null,
            checked: []
          },
          foods: [{ text: 'Select One', value: null }, 'Carrots', 'Beans', 'Tomatoes', 'Corn'],
          show: true
        }
      },
      methods: {
        onSubmit(event) {
          event.preventDefault()
          alert(JSON.stringify(this.form))
        },
        onReset(event) {
          event.preventDefault()
          // Reset our form values
          this.form.email = ''
          this.form.name = ''
          this.form.food = null
          this.form.checked = []
          // Trick to reset/clear native browser form validation state
          this.show = false
          this.$nextTick(() => {
            this.show = true
          })
        }
      }
    }
  </script>

<style scoped>
    #card1{
        margin-left: 340px;
        border-radius: 20px;

    }
 #card4{
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
        margin: -405px 0px 0px 40px;
        height: 100%;
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
