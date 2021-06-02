<template>
  <div id="removeDepartment" aria-hidden="true" aria-labelledby="removeDepartmentLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="removeDepartmentLabel" class="modal-title">Remove department</h5>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 204" id="alertSuccess" class="alert alert-success alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Success!</strong> Department removed!
          </div>
          <div v-if="statusCode !== 204 && statusCode !== 0" id="alertError"
               class="alert alert-warning alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Warning!</strong><br>{{ errorMessage }}
          </div>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <div v-if="loading">
              <div class="spinner-border" role="status">
              </div>
              Loading...
            </div>
            <p v-if="!removed">Are you sure you want delete this department?</p>
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
  name: "removeDepartment",
  data: () => ({
    removed: false,
    statusCode: 0,
    errorMessage: '',
    loading: false,
  }),
  props: {
    target: {
      type: String,
      required: true
    },
  },
  watch: {
    target: {
      deep: true,
      handler: function (department) {
        this.loading = true;
        fetch(department).then(response => response.json()).then(data => {
          if(data['personnels'].length !== 0){
            this.statusCode = 403;
            this.errorMessage = 'This department has assigned personnel and cannot be removed.';
            this.removed = true;
            this.loading = false;
          } else {
            this.statusCode = 0;
            this.errorMessage = '';
            this.loading = false;
          }
        });
      }
    }
  },
  methods: {
    remove() {
      this.loading = true;
      fetch(this.target).then(response=>response.json()).then(data=>{
        if(data['personnels'].length !== 0){
          this.statusCode = 403;
          this.errorMessage = 'This department has assigned personnel and cannot be removed.';
          this.removed = true;
          this.loading = false;
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
              this.removed = true;
              this.$emit('updateList');
            }
          })
        }
      });
    },
    close() {
      this.statusCode = 0;
      this.errorMessage = '';
      this.removed = false;
    }
  }
}
</script>

<style scoped>

</style>