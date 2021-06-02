<template>
  <div class="mt-3">
    <add-location @updateList="getLocations"></add-location>
    <edit-location :target="target" @updateList="getLocations"></edit-location>
    <remove-location :target="target" @updateList="getLocations"></remove-location>
    <div v-if="!loading" class="mb-3 text-end">
      <h2 class="text-decoration-underline text-start">Locations</h2>
      <button class="btn btn-secondary" data-bs-target="#addLocation" data-bs-toggle="modal"><i class="fa fa-plus"></i>
        Add location
      </button>
    </div>
    <div v-if="loading">
      <div class="spinner-border" role="status">
      </div>
      Loading...
    </div>
    <div v-show="locations.length > 0" class="table-container">
      <table class="table">
        <thead>
        <tr>
          <th colspan="2">Name</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="location in locations">
          <td>{{ location.name }}</td>
          <td class="text-end">
            <button class="btn btn-primary me-1" data-bs-target="#editLocation" data-bs-toggle="modal"
                    @click="target = location['@id']"><i class="fa fa-edit"></i></button>
            <button class="btn btn-secondary me-1" data-bs-target="#removeLocation" data-bs-toggle="modal"
                    @click="target = location['@id']"><i class="fa fa-remove"></i></button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import AddLocation from "../components/addLocation";
import EditLocation from "../components/editLocation";
import RemoveLocation from "../components/removeLocation";

export default {
  name: "locations",
  components: {RemoveLocation, EditLocation, AddLocation},
  data: () => ({
    locations: {},
    loading: true,
    target: '',
  }),
  created() {
    this.getLocations();
  },
  methods: {
    getLocations() {
      this.loading = true;
      fetch('/api/locations').then(response => response.json()).then(data => {
        this.locations = data['hydra:member'];
        this.loading = false;
      })
    },
  }
}
</script>

<style scoped>

</style>