<template>
  <div>
    <div class="msg_history">
      <div v-for="(message, index) in messages" :key="message.id">
        <div :id="separatedDateId(message.id)" class="separated_date" :data-pre_msg_id="preMessageId(index)" :data-next_msg_id="nextMessageId(index)" v-show="separateDate(message.id ,message.created_at) != false"></div>  
        <!-- <div v-if="separateDate(message.created_at) != false">{{ message.created_at }}</div> -->
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
      separatedDate: [],
      temp: {
        old_unique_date: null
      }
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
      let now       = new Date();
      let nowTime   = now.getTime();
      let nowDay    = now.getDate();
      let nowMonth  = now.getMonth() + 1;
      let nowYear   = now.getFullYear();
      let nowHour   = now.getHours();
      let nowMinute = now.getMinutes();
      let date    = new Date(String(createdAt));
      let time    = date.getTime();
      let day     = date.getDate();
      let month   = date.getMonth() + 1;
      let year    = date.getFullYear();
      let hour    = date.getHours();
      let minute  = date.getMinutes();

      if(date){
        if(day == nowDay && month == nowMonth && year == nowYear ){
          if(nowTime - time < 60 * 1000){
            return 'Just now';
          }
          return hour + ':' + minute;
        }else if(day != nowDay){
          return day + '/' + month + '/' + year + ' ' + hour + ':' + minute;
        }
      }
    },
    separateDate: function(id, createdAt){
      let date    = new Date(String(createdAt));
      let day     = date.getDate();
      let month   = date.getMonth() + 1;
      let year    = date.getFullYear();
      let hour    = date.getHours();
      var old_date = this.temp.old_unique_date;
      var new_date = new Date(date).getDate();
      if(old_date != new_date){
        this.temp.old_unique_date = new_date;
        return day + '/' + month + '/' + year;
      }else{
        return false;
      }
    },
    nextMessageId(index){
      let nextIndex = index +1;
      if(this.messages[nextIndex]){
        return this.messages[nextIndex].id;
      }
      return '';
    },

    preMessageId(index){
      let preIndex = index > 0 ? index -1 : null;
      if(preIndex !== null && this.messages[preIndex]){
        return this.messages[preIndex].id;
      }
      return '';
    },
    separatedDateId: function(id){
      return 'separateId-' + id;
    }
 
  },
  beforeMount(){
    this.formatDay();
 },
};
</script>