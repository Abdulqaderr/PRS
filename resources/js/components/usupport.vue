<template>
  <div>
    <div class="blackScreen" v-if="showLoadingScreen">
      <div class="panel">
        <img src="/images/loader.gif" alt="" class="loadingIMG">
        <p class="loadingTXT">Please Wait</p>
      </div>
    </div>
  
  
    <span >
      
      <div class="chatContainer">
        <div class="messagesUContainer" id="messagesContainer">
          <div class="messageDirectionLeft" v-bind:class="[mess['sender'] == 1 ? 'messageDirectionRight' :'messageDirectionLeft ' ]" v-for="(mess, index) in Messages"
                    :key="index">
            <div class="messageContainer">
              <p class="messageTXT">{{mess['message']}}</p>
            </div>
          </div>
        </div>
        <textarea name="" id="chatTextArea" v-model="messageBody"></textarea>
        <input type="button" id="sendMessageBTN" value="Send" @click="sendMessageToUser" />
      </div>
    </span>
  </div>
</template>
<script>
import { thisExpression } from '@babel/types';
import swal from 'sweetalert';

export default {
  props: ['userid'],
  data() {
    return {
      showUsersList: true,
      MessageLists : [],
      MessageListID:0,
      Messages:[],
      userID : 0,
      messageBody:"",
      notid: '',
      showLoadingScreen:false,
      haveMessages : false
    };
  },
  mounted(){
    $(document).ready(()=>{
      this.showLoadingScreen = true;
    
   const firebaseConfig = {
        apiKey: "AIzaSyDKHWpHcTE56fGAt1Tz808zwqzDVh8IEck",
        authDomain: "prss-2586c.firebaseapp.com",
        projectId: "prss-2586c",
        storageBucket: "prss-2586c.appspot.com",
        messagingSenderId: "436957358320",
        appId: "1:436957358320:web:684422e815acde42ac3a35",
        measurementId: "G-0N9TWKTNVB"
      };
  //  Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    let db = firebase.firestore();
          db.collection('messageList').where('sender', '==', this.userid).onSnapshot((querySnapshot)=>{
              var MessageLists = [];
              var i = 0;
              querySnapshot.forEach(doc => {
                MessageLists.push(doc.data());
                MessageLists[i]['messageID'] = doc.id;
               
                i++;
              });
              console.log(MessageLists);
              if(MessageLists.length >0){
        this.  haveMessages =true;
        //    this.sendMessageListToController(MessageLists);
          this.showUsersListMethod(MessageLists[0]['messageID'], this.userid,)
              }else{
               this.   haveMessages = false;
              }
            
          })
            this.showLoadingScreen = false;
        });
  },
  methods: {
    showUsersListMethod(MessageListID,userID) {
     
        this.MessageListID = MessageListID;
        this.userID = userID;
        //  this.notid = notid;
        this.getMessages();

        return;
    },
    // sendNotification(){
    //   axios.post('/api/sendNotification',[this.notid,"الدعم الفني ","رساله جديده من الدعم الفني"]).then((response)=>{
      
    //   })
    // },

    getMessages(){
      this.showLoadingScreen = true
       let db = firebase.firestore();
          db.collection('messages').where('messageListID', '==' , this.MessageListID).orderBy('time', 'asc').onSnapshot((querySnapshot)=>{
              var Messages = [];
              querySnapshot.forEach(doc => {
                Messages.push(doc.data());
              });
              this.Messages = Messages;
           const cityRef = db.collection('messageList').doc(this.MessageListID);

          const res = cityRef.update({sRead: 0});

            
             setTimeout(
      function() 
      {
        $(".messagesUContainer").scrollTop($(".messagesUContainer").height()+10000000);
       
      }, 100);
          })
         this.showLoadingScreen = false
    },
    sendMessageToUser(){
       if($.trim(this.messageBody) !== ""){
       if(!this.haveMessages){
        var date = new Date();
          var now_utc = Date.UTC(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(),
            date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());

          let db = firebase.firestore();
        
         const dataMessageList = {

            rRead: 1,
            sRead: 0,
            sender: this.userid,

            time: date
          };


          db.collection('messageList').add(dataMessageList).then((messageData) => {
           
            const data = {

              message: this.messageBody,
              messageListID: messageData.id,
              read: 1,
              sender: 1,
              time: date
            };
             db.collection('messages').add(data).then(() => {
              this.messageBody = ''
            });
          });

     
      }else{
         
   var date = new Date();
          var now_utc = Date.UTC(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(),
            date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());

          let db = firebase.firestore();
          const data = {

            message: this.messageBody,
            messageListID: this.MessageListID,
            read: 1,
            sender: 1,
            time: date
          };

          db.collection('messages').add(data).then(() => {
            this.messageBody = ''
          });
          const cityRef = db.collection('messageList').doc(this.MessageListID);

          const res = cityRef.update({sRead:0, rRead: 1, time: date });
          // this.sendNotification();
         
         
      }
              }else{
                 swal("", "An empty message ", "error");
              }
    },
  //   sendMessageListToController(MessageLists){
  //      axios
  //       .post("/api/usendMessageListToController", MessageLists)
  //       .then((response) => {
  //         console.log(response.data)
  //         if (response.data['status'] ) {
  //           this.MessageLists = response.data['data'];
  //           console.log(this.MessageLists.length);
  //     this.showUsersListMethod(MessageLists[0]['messageID'], MessageLists[0]['userTwo'],)
  //         } else {
  //          // swal("", "", "success");
  //         }
  //       })
  //       .catch((error) => {
  //       //  swal("", "حدث خطأ غير متوقع بالرجاء المحاوله مره اخري", "success");
  //         console.log(error);
  //       });
      
  // this.showLoadingScreen = false
            
  //       }
  },
};
</script>
