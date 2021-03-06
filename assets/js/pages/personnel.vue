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
        <button class="btn btn-secondary" data-bs-target="#addPerson" data-bs-toggle="modal"><i class="fa fa-plus"></i>
          Add person
        </button>
      </div>
      <!-- personnel main view start -->

      <div class="table-container" id="table-full" @scroll="getScrollPosition">
        <table v-if="windowWidth > 991" class="table">
          <thead>
          <tr>
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
          <tr v-for="person in personnel">
            <td>{{ person.firstName }}</td>
            <td>{{ person.lastName }}</td>
            <td>{{ person.jobTitle }}</td>
            <td>{{ person.email }}</td>
            <td>{{ person.department }}</td>
            <td>{{ person.location }}</td>
            <td>
              <button class="btn btn-primary me-1">
                <i :data-id="person['@id']" class="fa fa-edit me-1" data-bs-target="#staticBackdrop"
                   data-bs-toggle="modal"
                   @click="getPerson"></i>
              </button>
              <button class="btn btn-secondary">
                <i :data-id="person['@id']" class="fa fa-user-times" data-bs-target="#removePerson"
                   data-bs-toggle="modal"
                   @click="getPerson"></i>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
      <div v-if="personnel.length > 0 && windowWidth <= 991" class="table-container" id="table-mobile"
           @scroll="getScrollPosition">
        <table class="table">
          <thead>
          <tr>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="person in personnel" :key="person['@id']">
            <td>{{ person.firstName }}</td>
            <td>{{ person.lastName }}</td>
            <td>
              <div class="d-inline">
                <button class="btn btn-primary me-1">
                  <i class="fa fa-edit"
                     :data-id="person['@id']"
                     data-bs-target="#staticBackdrop"
                     data-bs-toggle="modal"
                     @click="getPerson"></i>
                </button>
                <button class="btn btn-secondary me-1">
                  <i class="fa fa-user-times"
                     :data-id="person['@id']"
                     data-bs-target="#removePerson"
                     data-bs-toggle="modal"
                     @click="getPerson"></i>
                </button>
              </div>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div>
      <button @click="goTop" ref="goTopBtn" id="goTopBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>
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
    getScrollPosition(el) {
      if (el.target.scrollTop > 200) {
        this.$refs.goTopBtn.style.display = 'block';
      } else {
        this.$refs.goTopBtn.style.display = 'none';
      }
    },
    goTop() {
      const target = window.innerWidth <= 991 ? 'table-mobile' : 'table-full'
      const element = document.getElementById(target);
      element.scrollTop = 0;
    },
    async getPersonnel() {
      this.loading = true
      await fetch('/api/personnels')
          .then(response => response.json())
          .then(data => {
            data = data['hydra:member'];
            for (let i = 0; i < data.length; i++) {
              if (data[i]['departmentID']) {
                data[i].department = data[i]['departmentID']['name'];
                data[i].location = data[i]['departmentID']['locationID']['name'];
                delete data[i]['departmentID'];
              }
            }
            this.personnel = data;
            this.loading = false;
            this.$emit('loaded');
          });
    },
    getPerson(event) {
      this.personModal = event.target.dataset.id;
      this.removed = false;
    },
    removePerson() {
      fetch(this.personModal, {
        method: 'DELETE',
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
#goTopBtn {
  display: none;
  position: fixed;
  bottom: 5vh;
  right: 30px;
  z-index: 99;
  font-size: 10px;
  border: none;
  outline: none;
  background-color: rgba(83, 83, 83, 0.70);
  color: white;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
}

#goTopBtn:hover {
  background-color: #555;
}
</style>