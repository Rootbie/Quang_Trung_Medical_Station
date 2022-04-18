<template>
    <div>
        <div class="verify" v-if="isShow">
            <h1>Xác nhận số điện thoại</h1>
            <hr>
            <div class="phone-input">
                <input type="text" @keypress="handleKey" placeholder="Nhập số điện thoại" v-model="phone_number">
            </div>

            <div class="message">{{message_phone}}</div>

            <div id="recaptcha-container"></div>

            <div class="btn">
                <button type="button" @click="$router.push('/')">Trở về</button>
                <button type="button" @click="handleVerify">Tiếp tục</button>
            </div>
        </div>

        <EnterOTP v-else v-bind:codeResult="codeResult" />
    </div>
</template>

<script>
    import firebase from '../../Firebase/FirebaseInit.js'
    import EnterOTP from './EnterOTP.vue'

    export default {
        components: {
            EnterOTP
        },
        data: function () {
            return {
                isShow: true,
                phone_number: '',
                message_phone: '',
                codeResult: ''
            }
        },
        methods: {
            handleVerify() {
                this.phone_number = this.phone_number.trim();

                const vnf_regex = /((09|03|07|08|05)+([0-9]{8})\b)/g;
                if (this.phone_number !== '') {
                    if (vnf_regex.test(this.phone_number) === false) {
                        this.message_phone = 'Số điện thoại của bạn không đúng định dạng!'
                    } else {
                        // Send this phone to Laravel server
                        this.$axios.post('http://localhost:8000/verify-phone', {
                            'phone_number': this.phone_number
                        }).then(res => {
                            if (res.data.message !== 'User valid') {
                                // Server return invalid user 
                                this.message_phone = 'Không tìm thấy số điện thoại này'
                            } else {
                                // Send this phone to Firebase server
                                this.phoneAuth();
                            }
                        }).catch(err => {
                            console.log(err);
                        });

                    }
                }
            },
            setUpRecaptcha() {
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
                window.recaptchaVerifier.render();
            },
            phoneAuth() {
                const appVerifier = window.recaptchaVerifier;
                const standardPhone = this.phone_number.replace('0', '+84');

                firebase.auth().signInWithPhoneNumber(standardPhone, appVerifier)
                    .then((confirmationResult) => {
                        // SMS sent. Prompt user to type the code from the message, 
                        // then sign the user in with confirmationResult.confirm(code).
                        window.confirmationResult = confirmationResult;
                        this.codeResult = window.confirmationResult;
                        // Render next page
                        this.isShow = false;

                        window.alert('Đã gửi thành công mã OTP đến điện thoại của bạn');
                    }).catch((error) => {
                        // Error; SMS not sent
                        this.message_phone = error.message;
                    });
            },
            handleKey(e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            }
        },
        mounted() {
            this.setUpRecaptcha()
        }
    }
</script>

<style scoped>
    /* container */
    .verify {
        margin: auto;
        width: 700px;
        font-family: 'Segoe UI', sans-serif;
        font-size: 25px;

        position: relative;
        top: 100px;
        text-align: center;
    }

    /* input for phone no*/
    .phone-input {
        position: relative;
        width: 60%;
        display: inline-block;
        margin: 50px 0 0 0;
    }

    input {
        position: relative;
        font-size: 30px;
        text-align: center;
        color: #333;

        width: 100%;
        padding: 7px 15px;
        border: 1px solid #DDDDDD;

        outline: none;
        letter-spacing: 1px;

        transition: all 0.30s ease-in-out;
        -webkit-transition: all 0.30s ease-in-out;
        -moz-transition: all 0.30s ease-in-out;
        -ms-transition: all 0.30s ease-in-out;
        -o-transition: all 0.30s ease-in-out;
    }

    input:focus {
        padding: 7px 15px;

        box-shadow: 0 0 5px rgba(81, 203, 238, 1);
        border: 1px solid rgba(81, 203, 238, 1);
    }

    /*error message*/
    .message {
        display: flex;
        width: 60%;

        justify-content: center;
        align-items: center;
        margin: auto;
        
        padding: 5px 0 10px 0;
        color: #EE4B2B;
        text-align: left;
        font-size: 1.1rem;
        pointer-events: none;
        
    }

    /*reCaptcha*/
    #recaptcha-container {
        display: flex;
        align-items: center;
        justify-content: center;

        margin: 50px 0 0 0;
    }

    /* button */
    .btn button {
        width: 140px;
        height: 50px;
        margin: 50px 5px;

        border: 1px solid;
        border-radius: 5px;
        background: #555;

        font-size: 18px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        outline: none;
    }

    .btn button:hover {
        border-color: #555;
        transition: 0.5s;
    }
</style>