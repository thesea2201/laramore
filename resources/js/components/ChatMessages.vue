<template>
  <div>
    <div class="msg_history">
      <div v-for="message in messages" :key="message.id">
        <div class="incoming_msg" v-if="message.user_id != user.id">
            <div class="incoming_msg_img"> <img class="avatar" src="image/avatar/avatar-default.png" alt="sunil"> </div>
            <!-- <div class="sender_name">kaito kid </div> -->
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
  <div class="type_msg">
    <div class="input_msg_write">
      <input type="text" class="write_msg" name="newMessage" placeholder="Type a message" v-model="newMessage" @keyup.enter="sendMessage"/>
      <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true" @click="sendMessage"></i></button>
    </div>
  </div>
</div>
</template>
<script>
import moment from 'moment'

export default {
  // props: ["messages","user"],
  data() {
    return {
      message: '',
      user: [],
      messages: [],
      newMessage: '',
      today: '',
    }
  },
  created() {
    this.fetchMessages();
    Echo.private('laramore')
      .listen('MessageSent', (e) => {
        this.messages.push({
          message: e.message.message,
        });
      });;
  },
  
  updated() {
      this.scrollToEnd();
  },
  methods: {
    fetchMessages: function() {
      axios.get('/messages').then(response => {
        this.messages = response.data.messages;
        this.user = response.data.user;
      });
    },
    sendMessage: function(event) {
      event.preventDefault();
      var currentMsg = {
        message: this.newMessage,
        user_id: this.user.id,
        created_at: new Date()
      };
      this.messages.push(currentMsg);

      axios.post('/messages', currentMsg).then(response => {
      });

      this.newMessage = '';
    },
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