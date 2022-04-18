<template>
    <div id="manage-vaccines" :class="[ (visible === 'true') ? 'add-margin' : 'rm-margin']">
        <h1>This is manage vaccines page</h1>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                visible: localStorage.getItem('visible')
            }
        },
        created() {
            const localStorageSetHandler = (e) => {
                // e.value set to String because the 1st value in local storage is string
                (e.key === "visible") ? this.visible = String(e.value) : null
            };
            // Listen for changes with localStorage on the same window
            document.addEventListener("showSideBar", localStorageSetHandler, false);
        }
    }
</script>

<style scoped>
    #manage-vaccines {
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