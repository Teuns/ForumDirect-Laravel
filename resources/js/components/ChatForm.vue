<template>
    <div class="chat-actions" ref="form">
        <input id="chat-input" type="text" name="message" class="form-control" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">
    </div>
</template>
<script>
    export default {
        props: ['user'],

        data() {
            return {
                newMessage: '',
                disableInputBool: false
            }
        },

        watch: {
            disableInputBool: function(e) {
                var n = document.getElementById("chat-input");
                if(e == true){
                    n.disabled = true;
                    n.focus();
                }else{
                    n.disabled = false;
                    n.focus();
                }
            }
        },

        methods: {
            sendMessage(e) {
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage
                });
                if(this.newMessage && this.newMessage && this.newMessage.trim().length){
                    this.disableInputBool = true;
                }
            }
        }    
    }
</script>