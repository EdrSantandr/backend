<style scoped>
.logo {
  width: 250px;
  height: 250px;
}

</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h3>Not found page</h3>
                <object type="image/svg+xml" data="img/logogami_white.svg" class="logo">
                    Kiwi Logo <!-- fallback image in CSS -->
                </object>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
