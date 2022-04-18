<template>
    <div id="overview" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <h2>This overview page are processing the data</h2>
        <b-img fluid src="https://i.pinimg.com/736x/35/a6/ce/35a6ce29e1085a87ba9fe22b723ecefa.jpg"></b-img>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                visible: localStorage.getItem('visible')
            }
        },
        methods: {
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
            this.listenStorage();
        }
    }
</script>

<style scoped>
    #overview {
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