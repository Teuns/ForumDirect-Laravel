<template>
    <ul class="chat">
        <div v-if="loading">Loading...</div>
        <li v-for="message in messages" v-if="message.type !== 'whisper' || message.type == 'whisper' && message.to_uid == user.id || message.type == 'whisper' && message.from_uid == user.id"> 
            <div class="chat-body">
                <div class="header">
                    <a v-bind:href="'/users/'+ message.user.name"><img v-bind:src=message.user.user_avatar style="max-width: 20px; vertical-align: middle;"></a>
                    <span onclick="var command = '/whisper ' + this.innerText + ' '; document.getElementById('chat-input').value = command; document.getElementById('chat-input').focus();" :inner-html.prop="message.user | messageAuthor">{{ message.user | messageAuthor }}</span>
                    <p style="float:right;">{{ message.created_at | formatDate }}</p>
                </div>
                <div :inner-html.prop="message.message | message" style="margin-top: 8px;">{{ message.message | message }}</div>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['messages', 'user'],

        data () {
            return {
                loading: false,
            }
        },

        created () {
            this.fetchData()
        },

         watch: {
            '$route': 'fetchData'
        },

        methods: {
            fetchData () {
                this.loading = true;
                axios.get('/messages').then(response => {
                    console.log(response);
                    window.app.messages = response.data;
                    this.loading = false;
                }); 
            }
        }
    }
</script>