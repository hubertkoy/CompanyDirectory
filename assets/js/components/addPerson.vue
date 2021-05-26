<template>
  <div id="addPerson" aria-hidden="true" aria-labelledby="staticBackdropLabel" class="modal"
       data-bs-backdrop="static"
       data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="staticBackdropLabel" class="modal-title">Personal details</h5>
          <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
        </div>
        <div v-if="statusCode" class="mx-3">
          <div v-if="statusCode === 201" id="alertSuccess" class="alert alert-success alert-dismissible mt-3 fade show"
               role="alert">
            <strong>Success!</strong> Person created.
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
        <div v-if="!loading" class="modal-body">
          <form @input="errorMessage=''; statusCode=0;">
            <!-- first Name -->
            <div class="mb-3">
              <label class="form-label" for="personFirstName">First Name</label>
              <input id="personFirstName" v-model="person.firstName" class="form-control" name="firstName" type="text">
              <div class="form-text">Person first name</div>
            </div>

            <!-- last Name -->
            <div class="mb-3">
              <label class="form-label" for="personLastName">Last Name</label>
              <input id="personLastName" v-model="person.lastName" class="form-control" name="lastName" type="text">
              <div class="form-text">Person last name</div>
            </div>

            <!-- job title -->
            <div class="mb-3">
              <label class="form-label" for="personJobTitle">Job Title</label>
              <input id="personJobTitle" v-model="person.jobTitle" class="form-control" name="JobTitle" type="text">
              <div class="form-text">Person job title</div>
            </div>

            <!-- email -->
            <div class="mb-3">
              <label class="form-label" for="personEmail">Email</label>
              <input id="personEmail" v-model="person.email" class="form-control" name="email" type="email">
              <div class="form-text">Person email address</div>
            </div>

            <!-- department -->
            <div class="mb-3">
              <label class="form-label" for="personDepartment">Department</label>
              <select id="personDepartment" v-model="person['departmentID']" class="form-select" @input="getLocation">
                <option v-for="item in departments" :key="item['@id']" :location="item['locationID'].name"
                        :value="item">{{ item.name }}
                </option>
              </select>
              <div class="form-text">Person department</div>
            </div>

            <!-- location -->
            <div v-if="person.departmentID" class="mb-3">
              <label class="form-label" for="personLocation">Location</label>
              <input id="personLocation" v-model="person.departmentID.locationID.name" class="form-control" disabled
                     name="location" type="text"/>
              <div class="form-text">Person location (location depends of department (to change department location you
                have to edit it).
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
          <button class="btn btn-primary" type="submit" @click="create">Create</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "addPerson",
  data: () => ({
    departments: [],
    person: {
      firstName: '',
      lastName: '',
      jobTitle: '',
      email: '',
      departmentID: '',
    },
    loading: false,
    statusCode: 0,
    errorMessage: '',
  }),
  created() {
    fetch('/api/departments').then(response => response.json()).then(data => this.departments = data['hydra:member']);
  },
  methods: {
    create() {
      fetch('/api/personnels', {
        method: 'post',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          firstName: this.person.firstName,
          lastName: this.person.lastName,
          jobTitle: this.person.jobTitle,
          email: this.person.email,
          departmentID: this.person.departmentID['@id'],
        })
      }).then(response => {
        this.statusCode = response.status
        if (response.status === 201) {
          this.person = {
            firstName: '',
            lastName: '',
            jobTitle: '',
            email: '',
            departmentID: ''
          };
          this.$emit('updatePersonnel');
        } else if (response.status !== 201) {
          this.errorMessage = response.statusText
        }
      })
    }
  }

}
</script>

<style scoped>

</style>