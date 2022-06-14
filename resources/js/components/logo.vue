<template>
    <div class="brand-link">
      <img src="storage/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{value}} Panel</span>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
