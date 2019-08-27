<template>
<div class="msg_history">
  <div class="incoming_msg" v-for="message in messages" :key="message.id" v-if="message.user_id == user.id">
      <div class="incoming_msg_img"> <img src="image/avatar/avatar-default.png" alt="sunil"> </div>
      <div class="received_msg">
          <div class="received_withd_msg">
              <p>{{ message.message}}</p>
              <span class="time_date"> 11:01 AM | June 9</span>
          </div>
      </div>
  </div>
  <div class="outgoing_msg">
      <div class="sent_msg">
          <p>Test which is a new approach to have all
              solutions</p>
          <span class="time_date"> 11:01 AM | June 9</span>
      </div>
  </div>
</div>
</template>
<script>
import moment from 'moment'

export default {
  props: ["messages","user"],
  data() {
    return {
    message: null,
    }
  },
  updated() {
      this.scrollToEnd();
  },
  methods: {
    breakMessage(){
      var maxCharacterInLine = 20;
      var messages = this.$props.messages;
      console.log(messages);
      $.each(messages, function(key, value) {
      console.log(value.message);
    });
    },
    scrollToEnd: function() {    	
      var container = this.$el.querySelector(".incoming_msg");
      container.scrollTop = container.scrollHeight;
    },
    toDay: function() {
      Vue.filter('formatDate', function(value) {
        if (value) {
          return moment(String(value)).format('DD/MM/YYYY hh:mm')
        }
      })
      Vue.filter('formatHour', function(value) {
        if (value) {
          return moment(String(value)).format('hh:mm')
        }
      })
    }
 
  },
  beforeMount(){
    this.breakMessage();
    this.toDay();
 },
};
</script>