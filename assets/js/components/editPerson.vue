<template>
  <div id="staticBackdrop" aria-hidden="true" aria-labelledby="staticBackdropLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="staticBackdropLabel" class="modal-title">Personal details</h5>
          <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 200" id="alertSuccess" class="alert alert-success alert-dismissible mt-3 fade show" role="alert">
            <strong>Success!</strong> Personal details has been updated.
            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
          </div>
          <div v-if="statusCode !== 200" id="alertError" class="alert alert-warning alert-dismissible mt-3 fade show" role="alert">
            <strong>Warning!</strong> Error occur: {{ error }}
            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
          </div>
        </div>
        <div v-if="loading" class="modal-body">
          <div class="spinner-border" role="status">
          </div>
          Loading...
        </div>
        <div v-if="!loading" class="modal-body" @input="statusCode = 0;">
          <!-- first Name -->
          <div class="mb-3">
            <label class="form-label" for="personFirstName">First Name</label>
            <input id="personFirstName" v-model="p.firstName" class="form-control" name="firstName" type="text">
            <div class="form-text">Person first name</div>
          </div>

          <!-- last Name -->
          <div class="mb-3">
            <label class="form-label" for="personLastName">Last Name</label>
            <input id="personLastName" v-model="p.lastName" class="form-control" name="lastName" type="text">
            <div class="form-text">Person last name</div>
          </div>

          <!-- job title -->
          <div class="mb-3">
            <label class="form-label" for="personJobTitle">Job Title</label>
            <input id="personJobTitle" v-model="p.jobTitle" class="form-control" name="JobTitle" type="text">
            <div class="form-text">Person job title</div>
          </div>

          <!-- email -->
          <div class="mb-3">
            <label class="form-label" for="personEmail">Email</label>
            <input id="personEmail" v-model="p.email" class="form-control" name="email" type="email">
            <div class="form-text">Person email address</div>
          </div>

          <!-- department -->
          <div class="mb-3">
            <label class="form-label" for="personDepartment">Department</label>
            <select id="personDepartment" class="form-select" v-model="p['departmentID']['@id']" @input="getLocation">
              <option v-for="item in d" :value="item['@id']" :key="item['@id']" :location="item['locationID'].name">{{item.name}}</option>
            </select>
            <div class="form-text">Person department</div>
          </div>

          <!-- location -->
          <div class="mb-3">
            <label class="form-label" for="personLocation">Location</label>
            <input id="personLocation" class="form-control" name="location" type="text" v-model="location" disabled/>
            <div class="form-text">Person location (location depends of department (to change department location you
              have to edit it).
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
          <button class="btn btn-primary" type="button" @click="update">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "editPerson",
  data: () => ({
    p: {},
    d: [],
    loading: true,
    error: '',
    statusCode: 0,
    location: '',
  }),
  created() {
    fetch('/api/departments')
        .then(response => response.json())
        .then(data => this.d = data['hydra:member']);
  },
  props: {
    person: {
      type: String,
      required: true,
    },
  },
  watch: {
    person: {
      deep: true,
      handler: function (newVal) {
        this.loading = true;
        this.statusCode = 0;
        this.error = '';
        fetch(newVal)
            .then(response => response.json())
            .then(data => {
              this.p = data
              if(data['departmentID']) {
                this.location = data['departmentID']['locationID']['name'];
              } else {
                this.p.departmentID = {
                  '@id': '/api/departments/1'
                }
                this.location = 'London'
              }
            })
            .finally(() => this.loading = false)
      }
    },
  },
  methods: {
    update() {
      fetch(this.person, {
        method: 'patch',
        headers: {
          'Content-Type': 'application/merge-patch+json'
        },
        body: JSON.stringify({
          firstName: this.p.firstName,
          lastName: this.p.lastName,
          jobTitle: this.p.jobTitle,
          email: this.p.email,
          departmentID: this.p.departmentID['@id']
        })
      }).then(response => {
        if(response.status !== 200) {
          this.error = response.statusText;
          this.statusCode = response.status;
        } else if (response.status === 200) {
          this.statusCode = 200
          this.$emit('updatePersonnel');
        }
      })
    },
    getLocation(event) {
      const depID = event.target.value;
      for(let i = 0; i < this.d.length; i++){
        if(this.d[i]['@id'].includes(depID)) {
          this.location = this.d[i]['locationID'].name;
          return
        }
      }
    }
  }
}
</script>

<style scoped>

</style>