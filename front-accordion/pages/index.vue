<template>
  <div class="container">
    <div class="row">
      <div class="container-fluid d-grid gap-3">
        <div class="d-grid gap-3">
          <h2 class="text-center p-2">Accordion app</h2>
          <div class="alert alert-success" role="alert" v-if="success">
            {{ success }}
          </div>
          <b-button class="btn btn-success p-2" v-b-modal="'create-user'">Create user</b-button>
        </div>
        <br />
        <div v-if="users.length > 0" v-for="user in users" class="user-accordion" role="tablist">
          <b-card no-body class="mb-1">
            <b-card-header header-tag="header" class="p-1" role="tab">
              <b-button block aria-expanded="false" class="collapsed" v-b-toggle="'accordion-' + user.id" variant="info"
                @click="showUser(user.id)">User {{ user.id }}</b-button>
            </b-card-header>
            <b-collapse :id="'accordion-' + user.id" :ref="'accordion-' + user.id" false accordion="user-accordion"
              role="tabpanel">
              <b-card-body>
                <b-card-text>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Name: {{ singleUser.name }}</li>
                    <li class="list-group-item">Email: {{ singleUser.email }}</li>
                    <li class="list-group-item">Created at: {{ singleUser.created_at }}</li>
                  </ul>
                </b-card-text>
                <div class="btn-group btn-group-flex" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-success mx-1 my-1" v-b-modal="'create-task'">Create task</button>
                  <button type="button" class="btn btn-primary mx-1 my-1" v-b-modal="'edit-user'">Edit user</button>
                  <button type="button" class="btn btn-secondary mx-1 my-1" @click="shareUser(user.id)">Share
                    user</button>
                  <button type="button" class="btn btn-danger mx-1 my-1" @click="deleteUser(user.id)">Delete
                    user</button>
                </div>
                <div v-if="tasks.length > 0" v-for="task in tasks" class="task-accordion" role="tablist">
                  <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                      <b-button block aria-expanded="false" class="collapsed"
                        v-b-toggle="'accordion-' + user.id + '-' + task.id" variant="info"
                        @click="showTask(user.id, task.id)">
                        Task {{ task.id }}</b-button>
                    </b-card-header>
                    <b-collapse :id="'accordion-' + user.id + '-' + task.id"
                      :ref="'accordion-' + user.id + '-' + task.id" false accordion="task-accordion" role="tabpanel">
                      <b-card-body>
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">Task: {{ singleTask.name }}</li>
                          <li class="list-group-item">Description: {{ singleTask.description }}</li>
                        </ul>
                        <div class="btn-group d-flex" role="group" aria-label="Basic example">
                          <button type="button" class="btn btn-primary mx-1 my-1" v-b-modal="'edit-task'">Edit
                            task</button>
                          <button type="button" class="btn btn-secondary mx-1 my-1"
                            @click="shareTask(user.id, task.id)">Share task</button>
                          <button type="button" class="btn btn-danger mx-1 my-1"
                            @click="deleteTask(user.id, task.id)">Delete task</button>
                        </div>
                      </b-card-body>
                    </b-collapse>
                  </b-card>
                </div>
              </b-card-body>
            </b-collapse>
          </b-card>
        </div>
        <div v-if="loading">
          <span class="text-center"><font-awesome-icon icon="fa-solid fa-sync fa-10x" spin /></span>
        </div>
        <div v-else-if="users.length === 0 && loading">
          <h3 class="text-center">No users</h3>
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
          <input type="email" class="form-control" v-model="newUser.email" id="new-user-email"
            placeholder="name@example.com">
          <p v-if="validationErrors" v-for="email in validationErrors.email" class="text-danger">{{ email }}</p>
        </div>
        <div class="mb-3">
          <label for="new-user-name" class="form-label">Name</label>
          <input type="text" class="form-control" v-model="newUser.name" id="new-user-name" placeholder="name">
          <p v-if="validationErrors" v-for="name in validationErrors.name" class="text-danger">{{ name }}</p>
        </div>
        <div class="mb-3">
          <label for="new-user-password" class="form-label">Password</label>
          <input type="password" class="form-control" v-model="newUser.password" id="new-user-password">
          <p v-if="validationErrors" v-for="password in validationErrors.password" class="text-danger">{{ password }}</p>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-danger" block @click="createUser()" :disabled="loadingForm">
        <span v-if="!loadingForm">Save user</span>
        <span v-else><font-awesome-icon icon="fa-solid fa-sync" spin /></span>
      </b-button>
    </b-modal>
    <b-modal ref="edit-user" id="edit-user" hide-footer title="Edit user">
      <div class="d-block text-center">
        <div class="mb-3">
          <label for="edit-user-email" class="form-label">Email address</label>
          <input type="email" class="form-control" v-model="singleUser.email" id="edit-user-email"
            placeholder="name@example.com">
          <p v-if="validationErrors" v-for="email in validationErrors.email" class="text-danger">{{ email }}</p>
        </div>
        <div class="mb-3">
          <label for="edit-user-name" class="form-label">Name</label>
          <input type="text" class="form-control" v-model="singleUser.name" id="edit-user-name" placeholder="name">
          <p v-if="validationErrors" v-for="name in validationErrors.name" class="text-danger">{{ name }}</p>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-danger" block @click="editUser(singleUser.id)" :disabled="loadingForm">
        <span v-if="!loadingForm">Edit user</span>
        <span v-else><font-awesome-icon icon="fa-solid fa-sync" spin /></span>
      </b-button>
    </b-modal>
    <b-modal ref="create-task" id="create-task" hide-footer title="Create task">
      <div class="d-block text-center">
        <div class="mb-3">
          <label for="new-user-email" class="form-label">Name</label>
          <input type="text" class="form-control" v-model="newTask.name" id="new-user-email" placeholder="name">
          <p v-if="validationErrors" v-for="name in validationErrors.name" class="text-danger">{{ name }}</p>
        </div>
        <div class="mb-3">
          <label for="new-user-name" class="form-label">Description</label>
          <input type="text" class="form-control" v-model="newTask.description" id="new-user-name" placeholder="Hello">
          <p v-if="validationErrors" v-for="description in validationErrors.description" class="text-danger">{{
            description}}</p>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-danger" block @click="createTask(singleUser.id)" :disabled="loadingForm">
        <span v-if="!loadingForm">Save task</span>
        <span v-else><font-awesome-icon icon="fa-solid fa-sync" spin /></span>
      </b-button>
    </b-modal>
    <b-modal ref="edit-task" id="edit-task" hide-footer title="Edit task">
      <div class="d-block text-center">
        <div class="mb-3">
          <label for="edit-task-name" class="form-label">Name</label>
          <input type="text" class="form-control" v-model="singleTask.name" id="edit-task-name" placeholder="name">
          <p v-if="validationErrors" v-for="name in validationErrors.name" class="text-danger">{{ name }}</p>
        </div>
        <div class="mb-3">
          <label for="edit-task-description" class="form-label">Description</label>
          <input type="text" class="form-control" v-model="singleTask.description" id="edit-task-description"
            placeholder="Hello">
          <p v-if="validationErrors" v-for="description in validationErrors.description" class="text-danger">{{
            description}}</p>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-danger" block @click="editTask(singleUser.id, singleTask.id)"
        :disabled="loadingForm">
        <span v-if="!loadingForm">Edit task</span>
        <span v-else><font-awesome-icon icon="fa-solid fa-sync" spin /></span>
      </b-button>
    </b-modal>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import Swal from 'sweetalert2';

export default Vue.extend({
  name: "IndexPage",
  data() {
    return {
      disableEditUser: false,
      loading: true,
      loadingForm: false,
      users: [],
      singleUser: {},
      newUser: {},
      currentPage: 1,
      pagesPerRecord: 10,
      totalPages: 0,
      totalRecords: 0,
      tasks: [],
      singleTask: {},
      newTask: {},
      validationErrors: [],
      success: ''
    };
  },
  created() {
    if (this.$route.query.user && this.$route.query.task) {
      let sharedUser = this.$route.query.user;
      let sharedTask = this.$route.query.task;
      const page = Math.ceil(sharedUser / this.pagesPerRecord);
      this.currentPage = page;
      this.getUsers(page);
      this.openSharedTask(sharedUser, sharedTask);
    }
    else if (this.$route.query.user) {
      let sharedUser = this.$route.query.user;
      const page = Math.ceil(sharedUser / this.pagesPerRecord);
      this.currentPage = page;
      this.getUsers(page);
      this.openSharedUser(sharedUser);
    } else {
      this.getUsers(1);
    }
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
      if (this.$route.query.task && this.$route.query.user) {
        this.closeTaskAccordion(this.$route.query.user, this.$route.query.task);
        this.closeUserAccordion(this.$route.query.user);
        this.$router.replace({ 'query': null });
      } else if (this.$route.query.user) {
        this.closeUserAccordion(this.$route.query.user);
        this.$router.replace({ 'query': null });
      }
      this.currentPage = page;
      this.getUsers(this.currentPage);
    },
    createUser() {
      this.loadingForm = true;
      this.validationErrors = [];
      axios
        .post(`${process.env.apiUrl}/users`, this.newUser)
        .then((response) => {
          this.newUser = {};
          this.success = response.data.success;
          this.$refs['create-user'].hide();
          this.loadingForm = false;
          setTimeout(() => this.success = '', 60000);
          this.getUsers(this.currentPage);
        })
        .catch((error) => {
          this.loadingForm = false;
          if (error.response.status === 422) {
            this.validationErrors = error.response.data.errors;
          }
        })
    },
    showUser(id) {
      axios
        .get(`${process.env.apiUrl}/users/${id}`)
        .then((response) => {
          this.singleUser = response.data.user;
          this.tasks = response.data.tasks;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    editUser(id) {
      this.loadingForm = true;
      this.validationErrors = [];
      axios
        .put(`${process.env.apiUrl}/users/${id}`, this.singleUser)
        .then((response) => {
          this.success = response.data.success
          this.$refs['edit-user'].hide();
          setTimeout(() => this.success = '', 60000);
          this.loadingForm = false;
        })
        .catch((error) => {
          this.loadingForm = false;
          console.log(error);
        });
    },
    async deleteUser(id) {
      const confirmed = await Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to delete this user, you delete all tasks as well?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel'
      });
      if (confirmed.isConfirmed) {
        this.loadingForm = true;
        await axios
          .delete(`${process.env.apiUrl}/users/${id}`)
          .then((response) => {
            this.success = response.data.success;
            this.getUsers(1);
            this.loadingForm = false;
            this.singleUser = {};
          })
          .catch((error) => {
            this.loadingForm = false;
            console.log(error);
          });
      }
    },
    shareUser(userId) {
      const baseUrl = process.env.baseUrl;
      var tempInput = document.createElement('input');
      document.body.append(tempInput);
      tempInput.value = `${baseUrl}/?user=${userId}`;
      tempInput.select();
      document.execCommand('copy');
    },
    openSharedUser(userId) {
      setTimeout(() => {
        for (let i = 1; i < this.totalPages; i++) {
          if (document.getElementById(`accordion-${userId}`)) {
            this.$refs[`accordion-${userId}`][0].toggle();
            break;
          }
        }
      }, 1000);
      this.showUser(userId);
    },
    createTask(id) {
      this.loadingForm = true;
      this.validationErrors = [];
      axios
        .post(`${process.env.apiUrl}/users/${id}/tasks`, this.newTask)
        .then((response) => {
          this.success = response.data.success;
          this.newTask = {};
          this.$refs['create-task'].hide();
          setTimeout(() => this.success = '', 60000);
          this.loadingForm = false;
          this.showUser(id);
        })
        .catch((error) => {
          this.loadingForm = false;
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
          console.log(error);
        });
    },
    editTask(userId, taskId) {
      this.loadingForm = true;
      this.validationErrors = [];
      axios
        .put(`${process.env.apiUrl}/users/${userId}/tasks/${taskId}`, this.singleTask)
        .then((response) => {
          this.success = response.data.success;
          this.$refs['edit-task'].hide();
          this.loadingForm = false;
          setTimeout(() => this.success = '', 60000);
        })
        .catch((error) => {
          this.loadingForm = false;
          if (error.response.status === 422) {
            this.validationErrors = error.response.data.errors;
          }
        })
    },
    async deleteTask(userId, taskId) {
      const confirmed = await Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to delete the task',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel'
      });
      if (confirmed.isConfirmed) {
        this.loadingForm = true;
        await axios
          .delete(`${process.env.apiUrl}/users/${userId}/tasks/${taskId}`)
          .then((response) => {
            this.success = response.data.success
            this.singleTask = {};
            this.showUser(userId);
            this.loadingForm = false;
          })
          .catch((error) => {
            this.loadingForm = false;
            console.log(error);
          });
      }
    },
    shareTask(userId, taskId) {
      const baseUrl = process.env.baseUrl;
      var tempInput = document.createElement('input');
      document.body.append(tempInput);
      tempInput.value = `${baseUrl}/?user=${userId}&task=${taskId}`;
      tempInput.select();
      document.execCommand('copy');
    },
    openSharedTask(userId, taskId) {
      this.openSharedUser(userId);
      setTimeout(() => {
        this.$refs[`accordion-${userId}-${taskId}`][0].toggle();
        this.showTask(userId, taskId);
      }, 1000);
    },
    closeUserAccordion(userId) {
      this.$refs[`accordion-${userId}`][0].toggle();
    },
    closeTaskAccordion(userId, taskId) {
      this.$refs[`accordion-${userId}-${taskId}`][0].toggle();
    }
  },
});
</script>
