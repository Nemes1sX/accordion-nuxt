<template>
  <div class="container">
  <div class="row">
  <div class="container-fluid d-grid gap-3">
    <div v-if="users.length > 0" v-for="user in users" class="accordion" role="tablist">
          <b-card no-body class="mb-1">
      <b-card-header header-tag="header" class="p-1" role="tab">
        <b-button block aria-expanded="false" class="collapsed" v-b-toggle="'accordion-' + user.id " variant="info">User {{ user.id }}</b-button>
      </b-card-header>
      <b-collapse :id="'accordion-' + user.id" false  accordion="my-accordion" role="tabpanel">
        <b-card-body>
          <b-card-text>I start opened because <code>visible</code> is <code>true</code></b-card-text>
          <div v-if="tasks.length > 0" v-for="task in tasks" class="accordion" role="tablist">
          <b-card no-body class="mb-1">
      <b-card-header header-tag="header" class="p-1" role="tab">
        <b-button block aria-expanded="false" class="collapsed" v-b-toggle="'accordion-' + user.id + '-' + task.id " variant="info">Task {{ task.id }}</b-button>
      </b-card-header>
      <b-collapse :id="'accordion-' + user.id + '-' + task.id" false  accordion="my-accordion" role="tabpanel">
        <b-card-body>
          <b-card-text>I start opened because <code>visible</code> is <code>true</code></b-card-text>  
        </b-card-body>
      </b-collapse>
    </b-card>
  </div> 
        </b-card-body>
      </b-collapse>
    </b-card>
  </div>
    <div class="d-flex justify-content-between" v-if="totalRecords >= 1">
      <div>
        <div class="p-2">
          <p>Total users: {{ totalRecords }}</p>
        </div>
      </div>
      <div>
        <div class="p-2">
          <nav v-if="totalPages >= 1" aria-label="Page navigation example">
            <ul class="pagination">
              <li class="page-item" :class="{ disabled: currentPage - 1 < 1 }">
                <a @click="paginate(currentPage - 1)" class="page-link"
                  >Previous</a
                >
              </li>
              <li class="page-item" :class="{ active: 1 === currentPage }">
                <a
                  @click="paginate(1)"
                  class="page-link"
                  :class="{ 'not-clickable': currentPage === 1 }"
                  >1</a
                >
              </li>
              <li v-if="currentPage - 2 > 1" class="page-item">
                <a href="#" class="page-link">...</a>
              </li>
              <li class="page-item" v-if="currentPage - 2 > 1">
                <a @click="paginate(currentPage - 2)" class="page-link">{{
                  currentPage - 2
                }}</a>
              </li>
              <li class="page-item" v-if="currentPage - 1 > 1">
                <a @click="paginate(currentPage - 1)" class="page-link">{{
                  currentPage - 1
                }}</a>
              </li>
              <li
                class="page-item"
                v-if="currentPage !== 1"
                :class="{ active: currentPage }"
              >
                <a
                  @click="paginate(currentPage)"
                  class="page-link"
                  :class="{ 'not-clickable': currentPage }"
                  >{{ currentPage }}</a
                >
              </li>
              <li class="page-item" v-if="currentPage + 1 <= totalPages">
                <a @click="paginate(currentPage + 1)" class="page-link">{{
                  currentPage + 1
                }}</a>
              </li>
              <li class="page-item" v-if="currentPage + 2 <= totalPages">
                <a @click="paginate(currentPage + 2)" class="page-link">{{
                  currentPage + 2
                }}</a>
              </li>
              <li class="page-item" v-if="currentPage + 2 < totalPages">
                <a href="#" class="page-link">...</a>
              </li>
              <li
                class="page-item"
                v-if="
                  currentPage !== totalPages && currentPage + 2 < totalPages
                "
                :class="{ active: totalPages === currentPage }"
              >
                <a
                  @click="paginate(totalPages)"
                  class="page-link"
                  :class="{ 'not-clickable': totalPages === currentPage }"
                  >{{ totalPages }}</a
                >
              </li>
              <li
                class="page-item"
                :class="{ disabled: currentPage + 1 > totalPages }"
              >
                <a @click="paginate(currentPage + 1)" class="page-link">Next</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</template>

<!--<script lang="ts">-->
<script>
import Vue from "vue";
import axios from "axios";
import { BModal } from 'bootstrap-vue'


export default Vue.extend({
  name: "IndexPage",
  data() {
    return {
      disableEditUser: false,
      loading: true,
      users: [],
      user: "",
      currentPage: 1,
      totalPages: 0,
      totalRecords: 0,
      tasks: [],
      userValidationErrors: [],
      modalInstance: null
    };
  },
  created() {
    this.getUsers(1);
  },
  components: {
    BModal
  },
  methods: {
    getUsers(page) {
      this.loading = true;
      axios
        .get(`https://accordion.dev/api/users?page=${page}`)
        .then((response) => {
          this.users = response.data.users;
          this.totalPages = response.data.totalPages;
          this.totalRecords = response.data.totalRecords;
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    paginate(page) {
      this.currentPage = page;
      this.getUsers(this.currentPage);
    },
    showModal(modalId) {
      const modalElement = document.getElementById(modalId);
      this.modalInstance = new Modal(modalElement);
      this.modalInstance.show();
    },
    showUser(id) {
     axios
     .get(`https://accordion.dev/api/users/${id}`)
     .then((response) => {
      this.user = response.data.user;
      this.tasks = response.data.tasks;
     })
     .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    editUser(id, page) {
      this.$refs.myModal.show();
      axios
        .get(`https://accordion.dev/api/users/${id}?page=${page}`)
        .then((response) => {
          this.user = response.data.user;
          this.totalTaskPages = response.data.totalPages;
          this.totalTaskRecords = response.data.totalRecords;
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    }
  },
});
</script>
