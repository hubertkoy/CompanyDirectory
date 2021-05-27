<template>
  <div id="editLocation" aria-hidden="true" aria-labelledby="editLocationLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="editLocationLabel" class="modal-title">Edit location</h5>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 200" id="alertSuccess" class="alert alert-success mt-3 fade show"
               role="alert">
            <strong>Success!</strong> Location created!
          </div>
          <div v-if="statusCode !== 200 && statusCode !== 0" id="alertError"
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
        <div v-if="!loading" class="modal-body">
          <form @input="updated = false">
          <!-- name -->
          <div class="mb-3">
            <label class="form-label" for="locationName">Name</label>
            <input id="locationName" v-model="location['name']" class="form-control" name="locationName" type="text">
            <div class="form-text">Location name</div>
          </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button" @click="refresh">Close</button>
          <button v-if="!updated" class="btn btn-primary" type="button" @click="update">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "editLocation",
  data: () => ({
    location: {},
    updated: false,
    statusCode: 0,
    errorMessage: '',
    loading: false,
  }),
  props: {
    target: {
      type: String,
      required: true,
    }
  },
  watch: {
    target: {
      deep: true,
      handler: function (location) {
        this.loading = true;
        fetch(location).then(response => response.json()).then(data => {
          this.location = data;
          this.loading = false;
        })
      }
    }
  },
  methods: {
    update() {
      fetch(this.target, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/merge-patch+json'
        },
        body: JSON.stringify({
          name: this.location.name,
        })
      }).then(response => {
        this.statusCode = response.status;
        this.errorMessage = response.statusText;
        if(response.status === 200) {
          this.updated = true;
          this.$emit('updateList');
        }
      });
    },
    refresh() {
      fetch(this.target).then(response=>response.json()).then(data=> {
        this.location = data;
        this.updated = false;
      })
    },
  },
}
</script>

<style scoped>

</style>