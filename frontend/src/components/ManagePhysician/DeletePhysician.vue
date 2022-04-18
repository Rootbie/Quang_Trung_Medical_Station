<template>

    <i style=" color: #F44336; cursor: pointer;" class="material-icons" data-toggle="tooltip" @click="showMsgBoxConfirm"
        title="Xóa">&#xE872;</i>

</template>

<script>
    export default {
        props: ['object'],
        methods: {
            showMsgBoxConfirm() {
                this.$bvModal.msgBoxConfirm('Bạn có chắc muốn xóa không?', {
                    title: 'Xóa bác sĩ khỏi danh sách',
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
                        // this.object.id
                        this.$emit(`returnConfirm`, value, this.object.id);
                    })
                    .catch(err => {
                        // An error occurred
                        console.log(err);
                    })
            }
        }
    }
</script>