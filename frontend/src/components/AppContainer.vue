<template>
    <div id="app-container">
        <NavBar />
        <router-view />
    </div>
</template>

<script>
    import NavBar from './NavBar.vue'
   
    export default {
        components: {
            NavBar
        }
    }
</script>   

<style>
    #app-container {
        display: flex; /* <-- chia website lam 2 phan : Nav va Main Content */
        flex-direction: column;

        position: fixed;
        top: 0;
        left: 0;

        width: 100%;
        height: 100%;
    }

</style>