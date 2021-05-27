<template>
  <div id="addDepartment" aria-hidden="true" aria-labelledby="addDepartmentLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="addDepartmentLabel" class="modal-title">Edit department</h5>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 201" id="alertSuccess" class="alert alert-success alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Success!</strong> Department created!
            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
          </div>
          <div v-if="statusCode !== 201 && statusCode !== 0" id="alertError"
               class="alert alert-warning alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Warning!</strong> Error occur: {{ errorMessage }}
            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
          </div>
        </div>
        <div v-if="loading" class="modal-body">
          <div class="spinner-border" role="status">
          </div>
          Loading...
        </div>
        <div v-if="!loading" class="modal-body" @input="statusCode = 0; changed = true;">
          <!-- name -->
          <div class="mb-3">
            <label class="form-label" for="departmentName">Name</label>
            <input id="departmentName" v-model="department['name']" class="form-control" name="firstName" type="text">
            <div class="form-text">Department name</div>
          </div>

          <!-- location -->
          <div class="mb-3">
            <label class="form-label" for="departmentLocation">Location</label>
            <select id="departmentLocation" v-model="department['locationID']" class="form-select"
                    name="location">
              <option v-for="location in locations" v-if="locations.length > 0" :value="location['@id']">
                {{ location['name'] }}
              </option>
            </select>
            <div class="form-text">Department location</div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
          <button v-if="!created" class="btn btn-primary" type="button" @click="create">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "addDepartment",
  data: () => ({
    department: {
      name: '',
      locationID: '',
    },
    locations: [],
    created: false,
    loading: true,
    statusCode: 0,
    errorMessage: ''
  }),
  created() {
    fetch('/api/locations').then(response => response.json()).then(data => {
      this.locations = data['hydra:member']
      this.department['locationID'] = data['hydra:member'][0]['@id'];
      this.loading = false;
    })
  },
  methods: {
    create() {
      fetch('/api/departments', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          name: this.department['name'],
          locationID: this.department['locationID'],
        })
      }).then(response => {
        this.statusCode = response.status;
        this.errorMessage = response.statusText;
        if (response.status === 201) {
          this.$emit('updateList');
          this.department = {
            name: '',
            locationID: '',
          }
        }
      })
    }
  }
}
</script>

<style scoped>

</style>