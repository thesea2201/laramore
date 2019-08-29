<template>
<div>
<div class="msg_history">
  <div v-for="message in messages" :key="message.id">
    <div class="incoming_msg" v-if="message.user_id != user.id">
        <div class="incoming_msg_img"> <img src="image/avatar/avatar-default.png" alt="sunil"> </div>
        <div class="received_msg">
            <div class="received_withd_msg">
                <p>{{ message.message }}</p>
                <span class="time_date">{{ checkTime(message.created_at)}}</span>
            </div>
        </div>
    </div>
    <div class="outgoing_msg" v-else>
        <div class="sent_msg">
            <p>{{ message.message }}</p>
            <span class="time_date">{{ checkTime(message.created_at)}}</span>
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
    today: '',
    }
  },
  updated() {
      this.scrollToEnd();
  },
  methods: {
    scrollToEnd: function() {    	
      var container = this.$el.querySelector(".msg_history");
      container.scrollTop = container.scrollHeight;
    },
    formatDay: function() {
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

    },
    checkTime: function(createdAt) {
      var now = new Date();
      var date = new Date(String(createdAt));
      var day = date.getDate();
      var month = date.getMonth();
      var year = date.getFullYear();

      if(date){
        if(day == now.getDate() && month == now.getMonth() && year == now.getFullYear() ){
          return date.getHours() + ':' + date.getMinutes();
        }
      }
    }
 
  },
  beforeMount(){
    this.formatDay();
 },
};
</script>