<template>
    <ul class="chat">
        <div v-if="loading">Loading...</div>
        <li v-for="message in filteredMessages" :key="message.id">
            <div class="chat-body">
                <div class="header">
                    <a v-bind:href="'/users/'+ message.user.name"><img v-bind:src=message.user.user_avatar style="max-width: 20px; vertical-align: middle; margin-right: 8px;"></a>
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

    computed: {
        filteredMessages: function() {
            return this.messages.filter((message) =>
                message.type !== 'whisper' || message.type === 'whisper'
                && parseInt(message.to_uid) === this.user.id
                || message.type === 'whisper' && parseInt(message.from_uid) === this.user.id);
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
                window.app.messages = response.data;
                this.loading = false;
            });
        }
    }
}
</script>
