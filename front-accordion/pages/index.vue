<template>
  <div class="row">
  <div class="table-responsive container-fluid d-grid gap-3">
    <table class="table">
      <thead>
        <tr>
          <th scope="col"># ID</th>
          <th scope="col">Full name</th>
          <th scope="col">E-mail</th>
          <th scope="col">Registered at</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="users.length > 0" v-for="user in users">
          <th scope="row">{{ user.id }}</th>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.created_at }}</td>
          <td>
            <b-button class="btn btn-warning" @click="editUser(user.id, taskCurrentPage)"> Edit </b-button>
          </td>
        </tr>
     <tr v-if="loading">
        <td colspan="5" class="text-center">
          <font-awesome-icon :icon="['fas', 'sync']" size="2x" spin />
        </td>
      </tr>

      <tr v-else-if="!loading && users.length === 0">
        <td colspan="5" class="text-center">No users</td>
      </tr>
      </tbody>
    </table>
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
    <!-- Using value -->
    <b-button v-b-modal="'my-modal'">Show Modal</b-button>

<!-- The modal -->
<b-modal id="myModal" ref="myModal" title="My modal">

  <div class="accordion" role="tablist">
    <div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Name</label>
  <input type="email" class="form-control" id="email" placeholder="name" v-model="user.name">
</div>
<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Email address</label>
  <input type="text" class="form-control" id="name" placeholder="name@example.com" v-model="user.email">
</div>
    <b-card no-body class="mb-1">
      <b-card-header header-tag="header" class="p-1" role="tab">
        <b-button block v-b-toggle.accordion-1 variant="info">Accordion 1</b-button>
      </b-card-header>
      <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
        <b-card-body>
          <b-card-text>I start opened because <code>visible</code> is <code>true</code></b-card-text>
          <b-card-text>{{ text }}</b-card-text>
        </b-card-body>
      </b-collapse>
    </b-card>

    <b-card no-body class="mb-1">
      <b-card-header header-tag="header" class="p-1" role="tab">
        <b-button block v-b-toggle.accordion-2 variant="info">Accordion 2</b-button>
      </b-card-header>
      <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
        <b-card-body>
          <b-card-text>{{ text }}</b-card-text>
        </b-card-body>
      </b-collapse>
    </b-card>

    <b-card no-body class="mb-1">
      <b-card-header header-tag="header" class="p-1" role="tab">
        <b-button block v-b-toggle.accordion-3 variant="info">Accordion 3</b-button>
      </b-card-header>
      <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
        <b-card-body>
          <b-card-text>{{ text }}</b-card-text>
        </b-card-body>
      </b-collapse>
    </b-card>
  </div>
</b-modal>
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
      taskCurrentPage: 1,
      totalTaskPages: 0,
      totalTaskRecords: 0,
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
