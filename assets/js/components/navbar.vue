<template>
  <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Eighth navbar example">
    <div class="container">
      <a class="navbar-brand" href="#">Company Directory</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item" v-for="page in pages">
            <button class="btn nav-link" @click="loadPage(page[0])" :class="{active: currentPage === page[0].toLowerCase()}"><i :class="page[1]"></i> {{ page[0] }}</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "navbar",
  data: () => ({
    pages: [['Departments', 'fa fa-sitemap'], ['Locations', 'fa fa-globe'], ['Personnel', 'fa fa-address-card']],
  }),
  props: {
    currentPage: String,
  },
  methods: {
    loadPage(value) {
      this.$emit('showPage', value.toLowerCase());
    }
  },
}
</script>

<style scoped>

</style>