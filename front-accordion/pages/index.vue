<template>
  <div class="container">
    <div class="row">
      <div class="container-fluid d-grid gap-3">
        <h2 class="text-center">Accordion app</h2>
        <b-button class="btn btn-success" v-b-modal="'create-user'">Create user</b-button>
        <div v-if="users.length > 0" v-for="user in users" class="accordion" role="tablist">
          <b-card no-body class="mb-1">
            <b-card-header header-tag="header" class="p-1" role="tab">
              <b-button block aria-expanded="false" class="collapsed" v-b-toggle="'accordion-' + user.id"
                variant="info" @click="showUser(user.id)">User {{ user.id }}</b-button>
            </b-card-header>
            <b-collapse :id="'accordion-' + user.id" false accordion="my-accordion" role="tabpanel">
              <b-card-body>
                <b-card-text>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Name: {{ singleUser.name }}</li>
                    <li class="list-group-item">Email: {{ singleUser.email }}</li>
                    <li class="list-group-item">Created at: {{ singleUser.created_at }}</li>
                  </ul>
                </b-card-text>
                <div class="btn-group" role="group" aria-label="Basic example">
  <button type="button" class="btn btn-primary">Left</button>
  <button type="button" class="btn btn-primary">Middle</button>
  <button type="button" class="btn btn-primary">Right</button>
</div>

                <div v-if="tasks.length > 0" v-for="task in tasks" class="accordion" role="tablist">
                  <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                      <b-button block aria-expanded="false" class="collapsed"
                        v-b-toggle="'accordion-' + user.id + '-' + task.id" variant="info">Task {{ task.id
                        }}</b-button>
                    </b-card-header>
                    <b-collapse :id="'accordion-' + user.id + '-' + task.id" false accordion="my-accordion"
                      role="tabpanel">
                      <b-card-body>
                        <b-card-text>I start opened because <code>visible</code> is <code>true</code></b-card-text>
                        <div class="btn-group" role="group" aria-label="Basic example">
  <button type="button" class="btn btn-primary">Left</button>
  <button type="button" class="btn btn-primary">Middle</button>
  <button type="button" class="btn btn-primary">Right</button>
</div>

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
                    <a @click="paginate(currentPage - 1)" class="page-link">Previous</a>
                  </li>
                  <li class="page-item" :class="{ active: 1 === currentPage }">
                    <a @click="paginate(1)" class="page-link" :class="{ 'not-clickable': currentPage === 1 }">1</a>
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
                  <li class="page-item" v-if="currentPage !== 1" :class="{ active: currentPage }">
                    <a @click="paginate(currentPage)" class="page-link" :class="{ 'not-clickable': currentPage }">{{
                      currentPage }}</a>
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
                  <li class="page-item" v-if="
                    currentPage !== totalPages && currentPage + 2 < totalPages
                  " :class="{ active: totalPages === currentPage }">
                    <a @click="paginate(totalPages)" class="page-link"
                      :class="{ 'not-clickable': totalPages === currentPage }">{{ totalPages }}</a>
                  </li>
                  <li class="page-item" :class="{ disabled: currentPage + 1 > totalPages }">
                    <a @click="paginate(currentPage + 1)" class="page-link">Next</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal ref="create-user" id="create-user" hide-footer title="Create user">
    <div class="d-block text-center">
      <div class="mb-3">
        <label for="new-user-email" class="form-label">Email address</label>
        <input type="email" class="form-control" v-model="newUser.email" id="new-user-email" placeholder="name@example.com">
      </div>
      <div class="mb-3">
        <label for="new-user-name" class="form-label">Name</label>
        <input type="text" class="form-control" v-model="newUser.name" id="new-user-name" placeholder="name">
      </div>
    </div>
    <b-button class="mt-3" variant="outline-danger" block @click="createUser">Save user</b-button>
  </b-modal>
  <b-modal ref="edit-user" id="edit-user" hide-footer title="Create user">
    <div class="d-block text-center">
      <div class="mb-3">
        <label for="edit-user-email" class="form-label">Email address</label>
        <input type="email" class="form-control" v-model="singleUser.email" id="edit-user-email" placeholder="name@example.com">
      </div>
      <div class="mb-3">
        <label for="edit-user-name" class="form-label">Name</label>
        <input type="text" class="form-control" v-model="singleUser.name" id="edit-user-name" placeholder="name">
      </div>
    </div>
    <b-button class="mt-3" variant="outline-danger" block @click="createUser">Save user</b-button>
  </b-modal>
  <b-modal ref="create-task" id="create-task" hide-footer title="Create user">
    <div class="d-block text-center">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Name</label>
        <input type="text" class="form-control" v-model="newTask.name" id="new-user-email" placeholder="name">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Description</label>
        <input type="text" class="form-control" v-model="newTask.description" id="new-user-name" placeholder="Hello">
      </div>
    </div>
    <b-button class="mt-3" variant="outline-danger" block @click="createUser">Save task</b-button>
  </b-modal>
  <b-modal ref="edit-task" id="edit-task" hide-footer title="Create user">
    <div class="d-block text-center">
      <div class="mb-3">
        <label for="edit-task-name" class="form-label">Name</label>
        <input type="text" class="form-control" v-model="singleTask.name" id="edit-task-name" placeholder="name">
      </div>
      <div class="mb-3">
        <label for="edit-task-description" class="form-label">Description</label>
        <input type="text" class="form-control" v-model="singleTask.description" id="edit-task-description" placeholder="Hello">
      </div>
    </div>
    <b-button class="mt-3" variant="outline-danger" block @click="createUser">Save task</b-button>
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
      singleUser: {},
      newUser: {},
      currentPage: 1,
      totalPages: 0,
      totalRecords: 0,
      tasks: [],
      singleTask: {},
      newTask: {},
      validationErrors: [],
      modalInstance: null,
      success: ''
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
    createUser() {
      axios
        .post(`https://accordion.dev/api/users`)
        .then((response) => {
            this.success = response.data.success;
        })
        .catch((error) => {
          if (error.response.status === 422) {
              this.validationErrors = error.response.data.errors;
            }
        })
    },
    showUser(id) {
      axios
        .get(`https://accordion.dev/api/users/${id}`)
        .then((response) => {
          this.singleUser = response.data.user;
          this.tasks = response.data.tasks;
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    editUser(id) {
      axios
        .put(`https://accordion.dev/api/users/${id}`)
        .then((response) => {
          this.success = response.data.success
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    deleteUser(id) {
      axios
        .delete(`https://accordion.dev/api/users/${id}`)
        .then((response) => {
          this.success = response.data.success
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    createTask(id) {
      axios
        .post(`https://accordion.dev/api/users/${id}/tasks`)
        .then((response) => {
            this.success = response.data.success;
        })
        .catch((error) => {
          if (error.response.status === 422) {
              this.validationErrors = error.response.data.errors;
            }
        })
    },
    showTask(userId, taskId) {
      axios
        .get(`https://accordion.dev/api/users/${userId}/tasks/${taskId}`)
        .then((response) => {
          this.singleTask = response.data.task;
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    editTask(userId, taskId) {
      axios
        .put(`https://accordion.dev/api/users/${userId}/tasks/${taskId}`)
        .then((response) => {
            this.success = response.data.success;
        })
        .catch((error) => {
          if (error.response.status === 422) {
              this.validationErrors = error.response.data.errors;
            }
        })
    },
    deleteTask(userId, taskId) {
      axios
        .delete(`https://accordion.dev/api/users/${userId}/tasks/${taskId}`)
        .then((response) => {
          this.success = response.data.success
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
  },
});
</script>
