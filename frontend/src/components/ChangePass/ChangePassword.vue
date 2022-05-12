<template>
    <div id="change-password" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <div class="content">
            <h1>Thay đổi mật khẩu</h1>

            <div class="input-focus-effect">
                <input :type="[isShow ? 'text' : 'password']" placeholder=" " v-model="old_password">
                <label>Mật khẩu cũ</label>
            </div>

            <div class="input-focus-effect">
                <input :type="[isShow ? 'text' : 'password']" placeholder=" " v-model="password">
                <label>Mật khẩu mới</label>
            </div>

            <div class="input-focus-effect">
                <input :type="[isShow ? 'text' : 'password']" placeholder=" " v-model="confirm_pass">
                <label>Nhập lại mật khẩu</label>
            </div>

            <div class="message"> {{message_pass}} </div>

            <div class="showPass">
                <label for="checkbox" class="checkbox-item">
                    <input type="checkbox" id="checkbox" @click="isShow=!isShow">Hiện mật khẩu
                    <i class="fas fa-check"></i>
                </label>
            </div>


            <div class="btn">
                <button type="button" @click="$router.go(-1)">Hủy</button>
                <button type="button" @click="changePass">Đổi mật khẩu</button>
            </div>
        </div>
    </div>

</template>

<script>
    import translate from 'translate'

    export default {
        data: function () {
            return {
                isShow: false,
                visible: localStorage.getItem('visible'),

                old_password: '',
                password: '',
                confirm_pass: '',

                message_pass: ''
            }
        },
        methods: {
            changePass() {
                this.password = this.password.trim();
                this.confirm_pass = this.confirm_pass.trim();

                if (this.password.length < 8) {
                    return this.message_pass = 'Sử dụng 8 ký tự trở lên cho mật khẩu của bạn'
                }
                else if (this.confirm_pass !== this.password) {
                    return this.message_pass = 'Các mật khẩu đã nhập không khớp. Hãy thử lại.'
                }
                else {
                    this.$axios.post(`http://localhost:8000/change-password`, {
                        'old_password': this.old_password,
                        'password': this.password,
                        'password_confirmation': this.confirm_pass
                    })
                        .then(res => {
                            if (res.status === 200) {
                                window.alert('Thay đổi mật khẩu thành công. Mời bạn đăng nhập lại')
                                this.$router.push('/')
                            }
                        })
                        .catch(async (err) => {
                            this.message_pass = await translate(err.response.data.message, "vi");
                        })
                }
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
            this.listenStorage()
        }
    }
</script>

<style scoped>
    /*container*/
    #change-password {
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

    .content {
        margin: auto;
        width: 700px;
        font-family: 'Segoe UI', sans-serif;
        font-size: 25px;

        position: relative;
        text-align: center;
    }

    /*input for password */
    .input-focus-effect {
        position: relative;
        display: flex;
        flex-direction: column-reverse;

        justify-content: center;
        align-items: center;
        margin: 30px 0 0 0;
    }

    .input-focus-effect input {
        border: 1px solid #DDDDDD;

        padding: 2.5rem 1rem 1rem;
        display: block;
        width: 60%;

        border-radius: 8px;
        transition: border 0.25s linear;
        background-color: transparent;
        outline: none;

        font-size: 30px;
        color: #333;
    }

    .input-focus-effect input:focus {
        box-shadow: 0 0 5px rgba(81, 203, 238, 1);
        border: 1px solid rgba(81, 203, 238, 1);
    }

    .input-focus-effect input:focus+label,
    .input-focus-effect input:not(:placeholder-shown)+label {
        transform: translateY(-5px);
        top: 1rem;
    }

    .input-focus-effect label {
        position: absolute;
        top: 50%;
        left: 22.5%;
        /* use % */

        transform: translateY(-50%);
        pointer-events: none;
        color: #5f6368;
        transition: 0.25s linear;
        font-size: 1.2rem;
    }

    /*error message*/
    .message {
        display: flex;
        width: 60%;

        display: flex;
        justify-content: center;
        align-items: center;
        margin: auto;

        padding: 5px 0 10px 0;
        color: #EE4B2B;
        text-align: left;
        font-size: 1.1rem;
        pointer-events: none;
    }

    /* checkbox to show password*/
    .showPass {
        display: flex;
        position: relative;
        font-size: 1.2rem;

        margin: 5px 0 0 0;
    }

    .showPass .checkbox-item {
        display: block;
        position: absolute;
        left: 20%;

        cursor: pointer;
        overflow: hidden;
    }

    .showPass .checkbox-item #checkbox {
        display: none;
    }

    .showPass .checkbox-item::before {
        content: "";
        width: 20px;
        height: 20px;

        display: block;
        border: 1px solid #202124;
        border-radius: 2px;

        margin-right: 10px;
        float: left;

        position: relative;
        top: 4px;
    }

    .showPass .checkbox-item i {
        position: absolute;
        left: 1px;
        top: 27px;

        font-size: 1.1rem;
        color: #202124;

        transition: all 0.15s linear;
        -webkit-transition: all 0.15s linear;
        -moz-transition: all 0.15s linear;
        -ms-transition: all 0.15s linear;
        -o-transition: all 0.15s linear;
    }

    .showPass .checkbox-item #checkbox:checked~i {
        left: 1px;
        top: 5px;
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