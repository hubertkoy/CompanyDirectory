<template>
  <navbar
      :currentPage="currentPage"
      @showPage="loadPage"
  />
  <div class="container">
    <locations v-if="currentPage === 'locations'"/>
    <departments v-if="currentPage === 'departments'"/>
    <personnel v-if="currentPage === 'personnel'"/>
  </div>
</template>

<script>

import Navbar from "../components/navbar";
import Departments from "./departments";
import Locations from './locations';
import Personnel from "./personnel";

export default {
  name: "index",
  data: () => ({
    currentPage: 'personnel',
  }),
  components: {
    Personnel,
    Locations,
    Departments,
    Navbar
  },
  mounted() {
  },
  methods: {
    loadPage(value) {
      this.currentPage = value;
    }
  }
}
</script>

<style scoped>

</style>