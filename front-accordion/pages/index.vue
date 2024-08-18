<template>
  <div class="table-responsive container-fluid d-grid gap-3">
<table class="table">
  <thead>
  <tr>
    <th scope="col"># ID</th>
    <th scope="col">Full name</th>
    <th scope="col">E-mail</th>
    <th scope="col">Registered at</th>
    <th scope="col">Email verified at</th>
    <th scope="col">Actions</th>
  </tr>
  </thead>
  <tbody>
  <tr v-if="users.length > 0" v-for="user in users">
    <th scope="row">{{user.id}}</th>
    <td>{{user.name}}</td>
    <td>{{user.email}}</td>
    <td>{{user.created_at}}</td>
    <td>
      <a class="btn btn-warning">
        Edit
      </a>
    </td>
  </tr>
  <tr v-else-if="loading">
    <td colspan="5" class="text-center"><font-awesome-icon icon="fa-solid fa-sync fa-10x" spin/></td>
  </tr>
  <tr v-else>
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
            <li class="page-item" :class="{disabled: currentPage - 1 < 1}">
              <a @click="paginate(currentPage - 1)" class="page-link">Previous</a>
            </li>
            <li class="page-item" :class="{active: 1 === currentPage}">
              <a @click="paginate(1)" class="page-link" :class="{'not-clickable': currentPage === 1}">1</a>
            </li>
            <li v-if="currentPage - 2 > 1" class="page-item">
              <a href="#" class="page-link">...</a>
            </li>
            <li class="page-item" v-if="currentPage - 2 > 1">
              <a @click="paginate(currentPage - 2)" class="page-link">{{ currentPage - 2}}</a>
            </li>
            <li class="page-item" v-if="currentPage - 1 > 1">
              <a @click="paginate(currentPage - 1)" class="page-link">{{ currentPage - 1}}</a>
            </li>
            <li class="page-item" v-if="currentPage !== 1"  :class="{active: currentPage}">
              <a @click="paginate(currentPage)" class="page-link" :class="{'not-clickable': currentPage}">{{currentPage }}</a>
            </li>
            <li class="page-item" v-if="currentPage + 1 <= totalPages">
              <a @click="paginate(currentPage + 1)" class="page-link">{{ currentPage + 1}}</a>
            </li>
            <li class="page-item" v-if="currentPage + 2 <= totalPages">
              <a @click="paginate(currentPage + 2)" class="page-link">{{ currentPage + 2}}</a>
            </li>
            <li class="page-item" v-if="currentPage + 2 < totalPages">
              <a href="#" class="page-link">...</a>
            </li>
            <li class="page-item" v-if="currentPage !== totalPages && currentPage + 2 < totalPages" :class="{active: totalPages === currentPage}">
              <a @click="paginate(totalPages)" class="page-link" :class="{'not-clickable': totalPages === currentPage}">{{totalPages }}</a>
            </li>
            <li class="page-item" :class="{disabled: currentPage + 1 > totalPages}">
              <a @click="paginate(currentPage + 1)" class="page-link">Next</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</div>
</template>

<script lang="ts">
import Vue from 'vue';
import axios from 'axios';
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'




export default Vue.extend({
  name: 'IndexPage',
  data() {
    return {
      loading: true,
      users: [],
      currentPage: 1,
      totalPages: 0,
      totalRecords: 0,
    }
  },
  created() {
    this.getUsers(1);
  },
  methods: {
    getUsers(page: number) {
      this.loading = true;
      axios.get(`https://accordion.dev/api/users?page=${page}`).then(response => {
        this.users = response.data.users;
        this.totalPages = response.data.totalPages;
        this.totalRecords = response.data.totalRecords;
        this.loading = false;
      })
          .catch(error => {
            this.loading = false;
            console.log(error);
          });
    },
    paginate(page: number) {
      this.currentPage = page;
      this.getUsers(this.currentPage);
    }
  }
})
</script>
