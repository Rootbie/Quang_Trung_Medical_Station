<template>
    <div>
        <div class="enterOTP" v-if="isShow">
            <h1>Nhập mã của bạn</h1>
            <hr>

            <div class="code-input">
                <label for="otpCode"><strong>Mã của bạn là</strong></label>
                <input type="text" id="otpCode" maxlength="6" @keypress="handleKey" v-model="code">
            </div>

            <div class="message">{{message_code}}</div>

            <div>
                <p @click="resetCode">Bạn chưa nhận được mã?</p>
            </div>

            <div class="btn">
                <button type="button" @click="resetCode">Trở về</button>
                <button type="button" @click="submitCode">Tiếp tục</button>
            </div>

        </div>

        <ResetPassword v-else v-bind:token="token" />
    </div>
</template>

<script>
    import firebase from '../../Firebase/FirebaseInit.js';
    import ResetPassword from './ResetPassword.vue'

    export default {
        props: ['codeResult'],
        components: { ResetPassword },
        data: function () {
            return {
                isShow: true,
                code: '',
                token: '',
                message_code: ''
            }
        },
        methods: {
            submitCode() {
                this.code = this.code.trim();

                if (this.code !== '') {
                    this.codeResult.confirm(this.code).then(() => {
                        // User signed in successfully.
                        this.getProfile();
                        this.isShow = false;

                    }).catch((error) => {
                        // User couldn't sign in (bad verification code?)
                        this.message_code = error.message;
                    });
                }
            },
            async getProfile() {
                const user = firebase.auth().currentUser;

                if (user !== null) {
                    const idToken = user.getIdToken();

                    await idToken.then((token) => {
                        this.token = token;
                    })
                } else {
                    console.log('User not found')
                }
            },
            logout() {
                firebase.auth().signOut().then().catch((error) => {
                    console.log(error.message);
                });
            },
            resetCode() {
                this.$router.go();
                this.logout();
            },
            handleKey(e){
                if (!/[0-9]/.test(e.key)){
                    e.preventDefault();
                }
            }
        }
    }
</script>

<style scoped>
    /*container*/
    .enterOTP {
        margin: auto;
        width: 700px;
        font-family: 'Segoe UI', sans-serif;
        font-size: 25px;

        position: relative;
        top: 100px;
        text-align: center;
    }

    /* need new code */
    p {
        color: blue;
        cursor: pointer;
    }

    p:hover {
        text-decoration: underline;
    }

    /* input for code*/
    .code-input {
        position: relative;
        width: 60%;
        display: inline-block;
        margin: 50px 0 0 0;
    }

    input {
        margin: 2rem auto;
        border: none;
        padding: 0;
        
        /* font: font-size + font-family ; */
        font: 5ch 'Segoe UI', monospace;
        
        width: 9ch;
        background: repeating-linear-gradient(90deg, dimgrey 0, dimgrey 1ch, transparent 0, transparent 1.5ch) 0 100%/ 9ch 2px no-repeat;
        letter-spacing: 0.5ch;
    }
    
    input:focus {
        outline: none;
        color: #333;
    }
    
    /*error message*/
    .message {
        color: #EE4B2B;
        padding-bottom: 18px;
        pointer-events: none;
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