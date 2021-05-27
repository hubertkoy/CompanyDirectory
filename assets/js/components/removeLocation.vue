<template>
  <div id="removeLocation" aria-hidden="true" aria-labelledby="removeLocationLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="removeLocationLabel" class="modal-title">Remove location</h5>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 204" id="alertSuccess" class="alert alert-success alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Success!</strong> Location removed!
          </div>
          <div v-if="statusCode !== 204 && statusCode !== 0" id="alertError"
               class="alert alert-warning alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Warning!</strong> Error occur: {{ errorMessage }}
          </div>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <div v-if="loading">
              <div class="spinner-border" role="status">
              </div>
              Loading...
            </div>
            <p v-if="!removed || !loading">Are you sure you want delete this location?</p>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" @click="close" type="button">Close</button>
          <button v-if="!removed" class="btn btn-primary" type="button" @click="remove">Remove</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "removeLocation",
  data: () => ({
    location: '',
    errorMessage: '',
    statusCode: 0,
    loading: false,
    removed: false,
  }),
  props: {
    target: {
      type: String,
      required: true,
    },
  },
  methods: {
    remove() {
      this.loading = true;
      fetch(this.target).then(response => response.json()).then(data => {
        if(data['departments'].length !== 0) {
          this.loading = false;
          this.statusCode = 403;
          this.errorMessage = `Location ${data.name} contain assigned department and cannot be removed.`
        } else {
          fetch(this.target, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/merge-patch+json'
            },
          }).then(response => {
            this.statusCode = response.status;
            this.errorMessage = response.statusText;
            this.loading = false;
            if(response.status === 204) {
              this.$emit('updateList');
              this.removed = true;
            }
            console.log(response);
          })
        }
      });
    },
    close() {
      this.loading = false;
      this.statusCode = 0;
      this.errorMessage = '';
      this.removed = false;
    }
  }
}
</script>

<style scoped>

</style>