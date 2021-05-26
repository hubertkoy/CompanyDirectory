<template>
  <div class="mt-3">

    <!-- person edit -->
    <edit-person :person="personModal" @updatePersonnel="getPersonnel"></edit-person>
    <!-- end person edit -->

    <!-- person add -->
    <add-person @updatePersonnel="getPersonnel"></add-person>
    <!-- end person add -->

    <!-- person remove -->
    <div id="removePerson" class="modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Remove person</h5>
            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
          </div>
          <div class="modal-body">
            <div v-if="!removed" class="alert alert-warning">Are you sure you want remove this person?</div>
            <div v-if="removed" class="alert alert-success">Success! Person has been removed.</div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
            <button class="btn btn-primary" type="button" v-if="!removed" @click="removePerson">Remove</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end person remove -->

    <div v-if="personnel.length === 0 || loading">
      <div class="spinner-border" role="status">
      </div>
      Loading...
    </div>
    <div v-if="personnel.length > 0 && !loading">
      <div class="text-end">
      <h2 class="text-decoration-underline text-start">Personnel</h2>
      <button class="btn btn-secondary" data-bs-target="#addPerson" data-bs-toggle="modal"><i class="fa fa-plus"></i> Add person</button>
      </div>
      <!-- personnel main view start -->
      <table v-if="windowWidth > 991" class="table">
        <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Job Title</th>
          <th scope="col">Email</th>
          <th scope="col">Department</th>
          <th scope="col">Location</th>
          <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(person, index) in personnel">
          <td>{{ index + 1 }}</td>
          <td>{{ person.firstName }}</td>
          <td>{{ person.lastName }}</td>
          <td>{{ person.jobTitle }}</td>
          <td>{{ person.email }}</td>
          <td>{{ person.department }}</td>
          <td>{{ person.location }}</td>
          <td>
            <i :data-id="person['@id']" class="fa fa-edit me-1" data-bs-target="#staticBackdrop" data-bs-toggle="modal"
               @click="getPerson"></i>
            <i :data-id="person['@id']" class="fa fa-user-times" data-bs-target="#removePerson" data-bs-toggle="modal"
               @click="getPerson"></i>
          </td>
        </tr>
        </tbody>
      </table>
      <table v-if="personnel.length > 0 && windowWidth <= 991" class="table caption-top">
        <caption>
          <ul class="list-group list-group-horizontal border-bottom">
            <li class="list-group-item col-6 border-0">
              <i class="fa fa-tag legend"></i> Job Title
            </li>
            <li class="list-group-item col-6 border-0">
              <i class="fa fa-building legend"></i> Department
            </li>
          </ul>
          <ul class="list-group list-group-horizontal">
            <li class="list-group-item col-6 border-0">
              <i class="fa fa-at legend"></i> Email
            </li>
            <li class="list-group-item col-6 border-0">
              <i class="fa fa-globe legend"></i> Location
            </li>
          </ul>
        </caption>
        <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Details</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(person, index) in personnel" :key="person['@id']">
          <td>{{ index + 1 }}</td>
          <td>{{ person.firstName }}</td>
          <td>{{ person.lastName }}</td>
          <td>
            <i v-popover="person.jobTitle" class="fa fa-tag me-1" tabindex="0" title="Job Title"></i>
            <i v-popover="person.email" class="fa fa-at me-1" tabindex="0" title="Email"></i>
            <i v-popover="person.department" class="fa fa-building me-1" tabindex="0" title="Department"></i>
            <i v-popover="person.location" class="fa fa-globe me-1" tabindex="0" title="Location"></i>
            <i :data-id="person['@id']" class="fa fa-edit me-1" data-bs-target="#staticBackdrop" data-bs-toggle="modal"
               @click="getPerson"></i>
            <i :data-id="person['@id']" class="fa fa-user-times" data-bs-target="#removePerson" data-bs-toggle="modal"
               @click="getPerson"></i>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import {Popover} from "bootstrap";
import EditPerson from "../components/editPerson";
import AddPerson from "../components/addPerson";

export default {
  name: "personnel",
  components: {AddPerson, EditPerson},
  data: () => ({
    personnel: [],
    personModal: '',
    windowWidth: undefined,
    removed: false,
    loading: true,
  }),
  created() {
    this.getPersonnel();
    this.windowWidth = window.innerWidth;
    window.addEventListener('resize', () => {
      this.windowWidth = window.innerWidth
    });
  },
  directives: {
    popover: {
      beforeMount(el, binding) {
        new Popover(el, {content: binding.value === '' ? 'n/a' : binding.value, trigger: 'hover focus'})
      }
    },
  },
  methods: {
    async getPersonnel() {
      this.loading = true
      await fetch('/api/personnels')
          .then(response => response.json())
          .then(data => {
            data = data['hydra:member'];
            for (let i = 0; i < data.length; i++) {
              if(data[i]['departmentID']) {
                data[i].department = data[i]['departmentID']['name'];
                data[i].location = data[i]['departmentID']['locationID']['name'];
                delete data[i]['departmentID'];
              }
            }
            this.personnel = data;
            this.loading = false;
          });
    },
    getPerson(event) {
      this.personModal = event.target.dataset.id;
      this.removed = false;
    },
    removePerson() {
      fetch(this.personModal, {
        method: 'delete',
        headers: {
          'Content-Type': 'application/merge-patch+json'
        },
      }).then(response => {
        if (response.status === 204) {
          this.getPersonnel();
          this.removed = true;
        }
      })
    }
  },
}
</script>

<style lang="scss" scoped>
.legend {
  width: 22px;
}
</style>