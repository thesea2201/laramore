<template>
<div>
<div class="msg_history">
  <div v-for="message in messages" :key="message.id">
    <div class="incoming_msg" v-if="message.user_id != user.id">
        <div class="incoming_msg_img"> <img src="image/avatar/avatar-default.png" alt="sunil"> </div>
        <div class="received_msg">
            <div class="received_withd_msg">
                <p>{{ message}}</p>
                <span class="time_date"> 11:01 AM | June 9</span>
            </div>
        </div>
    </div>
    <div class="outgoing_msg" v-else>
        <div class="sent_msg">
            <p>{{ message.message }}</p>
            <span class="time_date"> 11:01 AM | June 9</span>
        </div>
    </div>
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
      $.each(messages, function(key, value) {
    });
    },
    scrollToEnd: function() {    	
      var container = this.$el.querySelector(".msg_history");
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