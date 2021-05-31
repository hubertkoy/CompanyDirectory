<template>
  <div class="mt-3">
    <edit-department v-if="departments.length > 0" :target="target" @updateList="getDepartments"></edit-department>
    <add-department @updateList="getDepartments"></add-department>
    <remove-department :target="target" @updateList="getDepartments"></remove-department>
    <div v-if="!loading" class="text-end">
      <h2 class="text-decoration-underline text-start">Departments</h2>
      <button class="btn btn-secondary" data-bs-target="#addDepartment" data-bs-toggle="modal"><i
          class="fa fa-plus"></i> Add department
      </button>
    </div>
    <div v-if="loading">
      <div class="spinner-border" role="status">
      </div>
      Loading...
    </div>
    <div v-if="!loading" class="table-responsive">
      <table class="table">
        <thead>
        <tr>
          <th scope="col">Name</th>
          <th colspan="2" scope="col">Location</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="department in departments" :key="department['@id']">
          <td>{{ department.name }}</td>
          <td>{{ department.locationID.name }}</td>
          <td class="text-end">
            <i class="fa fa-edit me-1"
               data-bs-target="#editDepartment"
               data-bs-toggle="modal"
               @click="target = department['@id']"
               ></i>
            <i class="fa fa-remove me-1"
               data-bs-target="#removeDepartment"
               data-bs-toggle="modal"
               @click="target = department['@id']"
            ></i>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import EditDepartment from "../components/editDepartment";
import AddDepartment from "../components/addDepartment";
import RemoveDepartment from "../components/removeDepartment";

export default {
  name: "departments",
  components: {RemoveDepartment, AddDepartment, EditDepartment},
  data: () => ({
    departments: {},
    loading: true,
    target: '',
  }),
  async created() {
    this.getDepartments();
  },
  methods: {
    getDepartments() {
      this.loading = true;
      fetch('/api/departments')
          .then(response => response.json())
          .then(data => {
            this.departments = data['hydra:member'];
            this.loading = false;
          })
    }
  }
}

</script>

<style scoped>

</style>