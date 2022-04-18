<template>
  <div class="modal">
    <div class="overlay"></div>
    <form @submit.prevent>
      <!--   con = Container  for items in the form-->
      <div class="con">
        <!--     Start  header Content  -->
        <header class="head-form">
          <h2>Đăng nhập</h2>
          <!--     A welcome message or an explanation of the login form -->
        </header>
        <!--     End  header Content  -->
        <br />
        <div class="field-set">
          <!--   user name Input-->
          <input class="form-input" id="txt-input" type="text" placeholder="Số điện thoại" v-model="phone_number" />

          <div class="message">
            <i class="fas fa-exclamation-circle" v-if="message.phone"></i>
            {{message.phone}}
          </div>

          <br />

          <!--   Password Input-->
          <div class="pass-div">
            <input class="form-input" :type="[isShow ? 'text' : 'password']" placeholder="Mật khẩu" id="pwd"
              v-model="password" />
            <!--      Show/hide password  -->

            <span class="eye">
              <i class="fas fa-eye" v-if="isShow" @click="isShow=!isShow"></i>
              <i class="fas fa-eye-slash" v-else @click="isShow=!isShow"></i>
            </span>

            <div class="message">
              <i class="fas fa-exclamation-circle" v-if="message.pass"></i>
              {{message.pass}}
            </div>
          </div>
          <br />
          <br />
        </div>

        <!--   other buttons -->
        <div class="other">
          <!--      Sig-in button-->
          <button class="btn submits sign-in" @click="handleLogin">Đăng nhập</button>
          <!--     Forgot Password button -->
          <button class="btn submits frgt-pass">
            <router-link to="/forgot-password">Quên mật khẩu?</router-link>
            <!--         Sign Up font icon -->
          </button>
          <!--      End Other the Division -->
        </div>

        <!--   End Conrainer  -->
      </div>
      <!-- End Form -->
    </form>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        isShow: false,
        phone_number: '',
        password: '',
        message: {
          phone: '',
          pass: ''
        }
      }
    },
    methods: {
      handleLogin() {
        this.phone_number = this.phone_number.trim();

        if (this.phone_number === '') {
          this.message.phone = 'Nhập số điện thoại'
        }

        if (this.password === '') {
          this.message.pass = 'Nhập mật khẩu'
        }

        if (this.phone_number !== '' && this.password !== '') {
          const dataReq = {
            'phone_number': this.phone_number,
            'password': this.password
          }

          this.$axios.post('http://localhost:8000/login', dataReq)
            .then((res) => {
              if (res.status === 200) {
                this.$router.push('/nexus/overview')
              }

            }).catch((err) => {
              err.response.data.message === 'User not found !!'
                ? this.message.phone = 'Không tìm thấy số điện thoại này'
                : this.message.pass = 'Mật khẩu chưa chính xác'
            })
        }
      }
    }
  };
</script>

<style scoped>
  /* Fonts Form Google Font ::- https://fonts.google.com/  -:: */
  @import url("https://fonts.googleapis.com/css?family=Abel|Abril+Fatface|Alegreya|Arima+Madurai|Dancing+Script|Dosis|Merriweather|Oleo+Script|Overlock|PT+Serif|Pacifico|Playball|Playfair+Display|Share|Unica+One|Vibur");
  /* End Fonts */

  /* Global rules */
  * {
    padding: 0;
    margin: auto;
    max-width: 100%;
  }

  .modal {
    position: fixed;
    display: flex;

    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
  }

  /* Start body rules */
  .overlay {
    background-image: url(https://t4.ftcdn.net/jpg/04/24/30/93/360_F_424309320_UkOxg2z3sq7yXwGnWCO6xBXkRI4byhnI.jpg);
    background-size: 100%;
    background-attachment: fixed;
    background-repeat: no-repeat;

    position: absolute;
    width: 100%;
    height: 100%;

    font-family: "Vibur", cursive;
    /*   the main font */
    font-family: "Abel", sans-serif;
    opacity: 0.95;
    /* background-image: linear-gradient(to top, #d9afd9 0%, #97d9e1 100%); */
    border: 1px black;
  }

  /* |||||||||||||||||||||||||||||||||||||||||||||*/
  /* //////////////////////////////////////////// */

  /* End body rules */

  /* Start form  attributes */
  form {
    width: 450px;
    height: auto;
    border-radius: 5px;

    box-shadow: 0 9px 50px hsla(20, 67%, 75%, 0.31);
    padding: 2%;
    background-image: linear-gradient(0deg, #8fbbe4, #f8f8fa 100%);
    z-index: 1;
  }

  /* form Container */
  form .con {
    display: -webkit-flex;
    display: flex;

    -webkit-justify-content: space-around;
    justify-content: space-around;

    -webkit-flex-wrap: wrap;
    flex-wrap: wrap;

    margin: 0 auto;
  }

  /* the header form form */
  header {
    margin: 2% auto 10% auto;
    text-align: center;
  }

  /* Login title form form */
  header h2 {
    font-size: 250%;
    font-family: "Playfair Display", serif;
    color: #3e403f;
  }

  /*  A welcome message or an explanation of the login form */
  header p {
    letter-spacing: 0.05em;
  }

  /* //////////////////////////////////////////// */
  /* //////////////////////////////////////////// */

  /* inputs form  */
  input[class="form-input"] {
    width: 260px;
    height: 47.3px;
    margin-top: 2%;
    padding: 0px 36px 1px 18px;
    font-size: 16px;
    font-family: "Abel", sans-serif;
    color: #5e6472;
    outline: none;
    border: none;
    border-radius: 5px;
    transition: 0.2s linear;
  }

  input[id="txt-input"] {
    width: 260px;
  }


  /* focus  */
  input:focus {
    transform: translateX(-2px);
    border-radius: 5px;
  }

  /* Show/hide password Font Icon */
  .pass-div {
    position: relative;
  }

  .eye {
    position: absolute;
    right: 8px;
    top: 21px;
    color: #adadad;
    cursor: pointer;
  }

  .eye:hover {
    color: #2691d9;
  }

  .message {
    color: #EE4B2B;
    padding: 6px 0 0 5px;
  }

  /* //////////////////////////////////////////// */
  /* //////////////////////////////////////////// */

  /* input[type="text"] {min-width: 250px;} */
  /* buttons  */
  button {
    display: inline-block;
    color: #252537;

    width: 270px;
    height: 50px;

    padding: 0 20px;
    background: rgb(223, 219, 218);
    border-radius: 5px;

    outline: none;
    border: none;

    cursor: pointer;
    text-align: center;
    transition: all 0.2s linear;

    margin: 7% auto;
    letter-spacing: 0.05em;
  }

  /* Submits */
  .submits {
    width: 48%;
    display: inline-block;
    float: left;
    margin-left: 2%;
  }

  /*       Forgot Password button FAF3DD  */
  a {
    text-decoration: none;
  }

  .frgt-pass {
    background: transparent;
  }

  /*     Sign Up button  */
  .sign-in {
    background: rgb(223, 219, 218);
  }

  /* buttons hover */
  button:hover {
    transform: translatey(3px);
    box-shadow: none;
  }

  /* buttons hover Animation */
  button:hover {
    animation: ani9 0.4s ease-in-out infinite alternate;
  }

  @keyframes ani9 {
    0% {
      transform: translateY(3px);
    }

    100% {
      transform: translateY(5px);
    }
  }
</style>