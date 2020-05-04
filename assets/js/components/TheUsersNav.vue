<template>
  <div id="TheUsersNav" class="text-light">
    <div
      v-for="user in users"
      :key="user.id"
      @click="changeCurrentUser(user.id)"
      :class="{ current: user.id == currentUser }"
      class="mb-1 d-flex flex-column align-items-center justify-content-center bg-dark user-container"
    >
      <img src="/profile.jpg" alt="user" class="flex-shrink-0" />
      <span class="text-center">{{ user.username }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TheUsersNav',
  props: {
    users: {
      type: Object,
      default: {},
    },
    currentUser: {
      type: Number,
      default: 0,
    },
  },
  methods: {
    changeCurrentUser(user) {
      this.currentUser = user
      this.$emit('changeCurrentUser', user)
    },
  },
}
</script>

<style>
#TheUsersNav {
  width: 130px;
  margin-top: 10px;
}

.user-container {
  height: 80px;
  box-shadow: inset 0px -2px 19px 0px #000000cf;
  border-radius: 0 10px 10px 0;
  cursor: pointer;
  font-weight: bold;
}

.user-container img {
  height: 50px;
  border-radius: 50%;
}

.current {
  background-color: lightgrey !important;
  color: darkcyan;
  box-shadow: 5px 2px 10px 0px #80808082;
  transform: scale(1.2);
  transition: ease-in-out 0.3s;
  width: 130px;
}
</style>
