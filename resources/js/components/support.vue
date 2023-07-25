<template>
  <div>
    <div class="blackScreen" v-if="showLoadingScreen">
      <div class="panel">
        <img src="/images/loader.gif" alt="" class="loadingIMG">
        <p class="loadingTXT">Please Wait</p>
      </div>
    </div>
    <span v-show="showUsersList">
      <!-- this section for users list -->
      <!-- <div class="userSupportSearch">
        <input type="Text" id="userSupportSearch" placeholder="بحث" />

        <input type="button" id="userSupportSearchBTN" value="بحث" />
        <input type="button" id="userSupportSearchBTN" value="الغاء" />
      </div> -->
      <div class="usersSupportsContainer">
        <div class="userList" @click="showUsersListMethod(MList['messageID'],MList['userTwo'],MList['notid'])" v-for="(MList, index) in MessageLists"
                    :key="index">
          <div class="supportUserDetail">
            <img
              :src="MList['image']"
              class="userSupportImg"
            />

            <p class="userSupportName">{{ MList['name'] }}</p>
          </div>
          <div class="leftSideListSupport">
            <label class="redDotSupport" v-show="MList['aread'] == 1"></label>
 
            <img src="/images/left-arrow.png" class="leftArrowSupport" />
             {{ MList['rRead'] }}
          </div>

          <hr />
        </div>
    
      </div>
      <div class="usersContainerSupport">
        <div class="ifHavenotSupports">
          <p class="emptySupportTXT"
          >
          There are no customers asking for support

          </p>
        </div>
      </div>
      <!-- this section for users list -->
    </span>
    <span v-show="!showUsersList">
      <div class="backLink">
        <img
          class="backLinkIMG"
          src="/images/right-arrow.png"
          @click="showUsersListMethod"
          alt=""
        />
        back
      </div>
      <div class="chatContainer">
        <div class="messagesContainer" id="messagesContainer">
          <div class="messageDirectionLeft" v-bind:class="[mess['sender'] == 1 ? 'messageDirectionLeft' :'messageDirectionRight' ]" v-for="(mess, index) in Messages"
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
import swal from 'sweetalert';

export default {
  // props: ["request"],
  data() {
    return {
      showUsersList: true,
      MessageLists : [],
      MessageListID:0,
      Messages:[],
      userID : 0,
      messageBody:"",
      notid: '',
      showLoadingScreen:false
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
          db.collection('messageList').orderBy('time', 'desc').onSnapshot((querySnapshot)=>{
              var MessageLists = [];
              var i = 0;
              querySnapshot.forEach(doc => {
                MessageLists.push(doc.data());
                MessageLists[i]['messageID'] = doc.id;
               
                i++;
              });
              console.log(MessageLists);
            this.sendMessageListToController(MessageLists);
          })
        });
  },
  methods: {
    showUsersListMethod(MessageListID,userID,notid) {
      this.showUsersList = !this.showUsersList;
      if(!this.showUsersList)
      {this.MessageListID = MessageListID;
      this.userID = userID;
      this.notid = notid;
      this.getMessages();
      
      return ;
      }
      this.MessageListID = 0
      this.Messages = []
    },
    // sendNotification(){
    //   axios.post('/api/sendNotification',[this.notid,"الدعم الفني ","رساله جديده من الدعم الفني"]).then((response)=>{
      
    //   })
    // },

    getMessages(){
      this.showLoadingScreen = true
       let db = firebase.firestore();
       let dbU = firebase.firestore();
          db.collection('messages').where('messageListID', '==' , this.MessageListID).orderBy('time', 'asc').onSnapshot((querySnapshot)=>{
              var Messages = [];
              querySnapshot.forEach(doc => {
                Messages.push(doc.data());
              });
              this.Messages = Messages;
              console.log(this.MessageListID);
           const cityRef = dbU.collection('messageList').doc(this.MessageListID);

          const res = cityRef.update({ rRead: 0});

            
             setTimeout(
      function() 
      {
        $(".messagesContainer").scrollTop($(".messagesContainer").height()+10000000);
       
      }, 100);
          })
          this.showLoadingScreen = false
    },
    sendMessageToUser(){
       if($.trim(this.messageBody) !== ""){
 
      var date = new Date(); 
var now_utc =  Date.UTC(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(),
 date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());

       let db = firebase.firestore();
      const data = {
      
        message: this.messageBody,
        messageListID: this.MessageListID,
        read: 1,
        sender:0,
        time: date
      };
      
       db.collection('messages').add(data).then(()=>{
         this.messageBody = ''
       });
        const cityRef = db.collection('messageList').doc(this.MessageListID);

          const res = cityRef.update({rRead: 0,sRead:1,time:date});
        // this.sendNotification();
              }else{
                 swal("", "An empty message cannot be sent", "error");
              }
    },
    
    sendMessageListToController(MessageLists){
       axios
        .post("/api/sendMessageListToController", MessageLists)
        .then((response) => {
          console.log(response.data)
          if (response.data['status'] ) {
            this.MessageLists = response.data['data'];
            console.log(this.MessageLists.length);
     
          } else {
           // swal("", "", "success");
          }
        })
        .catch((error) => {
        //  swal("", "حدث خطأ غير متوقع بالرجاء المحاوله مره اخري", "success");
          console.log(error);
        });
      
  this.showLoadingScreen = false
            
        }
  },
};
</script>
