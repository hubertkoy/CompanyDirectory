<template>
  <div id="addLocation" aria-hidden="true" aria-labelledby="addLocationLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="addLocationLabel" class="modal-title">Add location</h5>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 201" id="alertSuccess" class="alert alert-success mt-3 fade show"
               role="alert">
            <strong>Success!</strong> Location created!
          </div>
          <div v-if="statusCode !== 201 && statusCode !== 0" id="alertError"
               class="alert alert-warning mt-3 fade show"
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
            <label class="form-label" for="locationName">Name</label>
            <input id="locationName" v-model="location['name']" class="form-control" name="locationName" type="text">
            <div class="form-text">Location name</div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button" @click="clear">Close</button>
          <button v-if="!created" class="btn btn-primary" type="button" @click="create">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "addLocation",
  data: () => ({
    loading: false,
    location: {
      name: ''
    },
    created: false,
    statusCode: 0,
    errorMessage: ''
  }),
  methods: {
    create() {
      if(this.location['name'] !== '') {
        fetch('/api/locations', {
          method: 'post',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            name: this.location['name']
          })
        }).then(response => {
          this.statusCode = response.status;
          this.errorMessage = response.statusText;
          if (response.status === 201) {
            this.created = true;
            this.$emit('updateList');
          }
        })
      } else {
        this.errorMessage = 'Location name cannot be empty';
        this.statusCode = 403;
      }
    },
    clear() {
      this.location['name'] = '';
      this.statusCode = 0;
      this.errorMessage = ''
      this.created = false;
    }
  }
}
</script>

<style scoped>

</style>