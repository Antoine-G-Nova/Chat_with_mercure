<template>
  <div
    class="message text-light my-4 mx-3"
    :class="{
      other: message.from.id == currentUser,
      me: message.from.id == connectedUser.id,
    }"
  >
    <span>{{ message.message }}</span>
  </div>
</template>
<script>
import moment from 'moment/moment'
export default {
  name: 'TheMessage',
  props: {
    message: {
      type: Object,
      default: {},
    },
    currentUser: {
      type: Number,
      default: {},
    },
    connectedUser: {
      type: Object,
      default: 0,
    },
  },
}
</script>

<style scoped>
.message {
  font-size: 1.3em;
}

.me {
  width: calc(100vw - 165px);
  display: inline-flex;
  justify-content: flex-end;
  text-align: justify;
}

.other {
  text-align: justify;
  width: calc(100vw - 165px);
  display: inline-flex;
  justify-content: flex-start;
}

.me span {
  background-color: #4aa745;
  border-radius: 10px;
  max-width: 75%;
  padding: 5px 10px;
}

.other span {
  background-color: #8c8c8c;
  border-radius: 10px;
  max-width: 75%;
  padding: 5px 10px;
}

@media screen and (min-width: 1500px) {
  .me,
  .other {
    width: calc(50vw - 165px);
  }
}

@media screen and (min-width: 800px) {
  .me,
  .other {
    width: calc(75vw - 165px);
  }
}
</style>
