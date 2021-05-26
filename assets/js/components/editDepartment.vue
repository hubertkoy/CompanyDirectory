<template>
  <div id="editDepartment" aria-hidden="true" aria-labelledby="editDepartmentLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="editDepartmentLabel" class="modal-title">Edit department</h5>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 200" id="alertSuccess" class="alert alert-success alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Success!</strong> Department updated!
          </div>
          <div v-if="statusCode !== 200" id="alertError" class="alert alert-warning alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Warning!</strong> Error occur: {{ errorMessage }}
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
            <select id="departmentLocation" v-model="department['locationID']['@id']" class="form-select"
                    name="location">
              <option v-for="location in locations" :value="location['@id']">{{ location['name'] }}</option>
            </select>
            <div class="form-text">Department location</div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button" @click="refresh">Close</button>
          <button v-if="changed" class="btn btn-primary" type="button" @click="update">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "editDepartment",
  data: () => ({
    loading: true,
    department: {
      name: '',
      locationID: '',
    },
    locations: [],
    statusCode: 0,
    errorMessage: '',
    changed: false,
  }),
  props: {
    target: {
      type: String,
      required: true,
    },
  },
  watch: {
    target: {
      deep: true,
      handler: function (department) {
        this.loading = true;
        fetch(department).then(response => response.json()).then(data => {
          this.department = data;
          this.loading = false;
        });
      }
    }
  },
  methods: {
    update() {
      this.loading = true;
      fetch(this.department['@id'], {
        method: 'patch',
        headers: {
          'Content-Type': 'application/merge-patch+json'
        },
        body: JSON.stringify({
          name: this.department['name'],
          locationID: this.department['locationID']['@id']
        })
      }).then(response => {
        this.statusCode = response.status;
        if (response.status === 200) {
          this.$emit('updateList');
        } else {
          this.errorMessage = response.statusText;
        }
      }).finally(() => {
        this.loading = false;
        this.changed = false;
      })
    },
    refresh() {
      fetch(this.target)
          .then(response => response.json())
          .then(data => {
            this.department = data;
            this.statusCode = 0;
          });
    }
  },
  mounted() {
    fetch('/api/locations').then(response => response.json()).then(data => this.locations = data['hydra:member'])
  }
}
</script>

<style scoped>

</style>