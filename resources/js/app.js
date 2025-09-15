import './bootstrap'
import emojione from 'emojione'
import linkifyHtml from 'linkify-html'
import ChatMessagesComponent from './components/ChatMessages.vue'
import ChatFormComponent from './components/ChatForm.vue'
import LatteUI from '@bybas/latte-ui'

Vue.use(LatteUI)

Vue.component('chat-messages', ChatMessagesComponent)
Vue.component('chat-form', ChatFormComponent)

window.app = new Vue({
    el: '#app',

    data: function () {
        return {
            messages: []
        }
    },

    created () {
        Echo.private('chat')
            .listen('MessageSent', (e) => {
                const message = typeof (e.message) === "string" ? JSON.parse(e.message) : e.message;
                this.messages.push({
                    message: message.message,
                    created_at: message.created_at,
                    type: message.type,
                    to_uid: message.to_uid,
                    from_uid: message.from_uid,
                    user: e.user
                })
            })
    },

    methods: {
        addMessage (message) {
            if (message.message && message.message.trim().length) {
                const startDate = moment()

                axios.get('/messages').then(response => {
                    let l = response.data.length

                    while (l--) {
                        if (response.data[l].user_id == message.user.id) {
                            break
                        }
                    }

                    if (response.data[l] && startDate.diff(moment(response.data[l].created_at), 'seconds') >= 3 || !response.data[l]) {
                        if (message.message.includes('/whisper')) {
                            Vue.set(message, 'type', 'whisper')
                            Vue.set(message, 'created_at', new Date())
                            Vue.set(message, 'from_uid', parseInt(message.user.id))

                            const username = message.message.split('/whisper')[1].split(' ')[1]

                            const realUsername = request('GET', '/api/v1/users/name/' + username)

                            message.message = message.message.replace(username, realUsername.getBody('utf8'))

                            const res = request('GET', '/api/v1/users/id/' + username)
                            if (res.statusCode == 200) {
                                const userId = res.getBody('utf8')

                                Vue.set(message, 'to_uid', parseInt(userId))

                                this.messages.push(message)

                                window.app.$children[window.app.$children.length - 1].$refs.form.__vue__.disableInputBool = false

                                window.app.$children[window.app.$children.length - 1].$refs.form.__vue__.newMessage = ''

                                axios.post('/messages', message)
                            }
                        } else {
                            Vue.set(message, 'type', 'post')
                            Vue.set(message, 'created_at', new Date())

                            this.messages.push(message)

                            window.app.$children[window.app.$children.length - 1].$refs.form.__vue__.disableInputBool = false

                            window.app.$children[window.app.$children.length - 1].$refs.form.__vue__.newMessage = ''

                            axios.post('/messages', message)
                        }
                    } else {
                        window.app.$children[window.app.$children.length - 1].$refs.form.__vue__.disableInputBool = false
                    }
                })
            }
        }
    },

    updated () {
        const container = document.getElementById('container')
        container.scrollTop = container.scrollHeight
    },

    ready () {
        const container = document.getElementById('container')
        container.scrollTop = container.scrollHeight
    }
})

var request = window.request

function replaceArray (str, find, replace) {
    let replaceString = str
    let regex
    for (let i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], 'g')
        replaceString = replaceString.replace(regex, replace[i])
    }
    return replaceString
};

function getUserMentions (str) {
    const qualityRegex = /\@([^ ]\w*)/g
    let matches
    const qualities = []
    const replace = []

    while (matches = qualityRegex.exec(str)) {
        const res = request('GET', '/api/v1/users/name/' + matches[1])
        const res2 = request('GET', '/api/v1/users/roles/' + matches[1])
        if (res.statusCode == 200 && res2.statusCode == 200) {
            qualities.push('@' + matches[1])
            replace.push("@<a href='/users/" + res.getBody('utf8') + "' class='" + res2.getBody('utf8').toLowerCase() + "'>" + res.getBody('utf8') + '</a>')
        }
    }

    return replaceArray(str, qualities, replace)
}

function getWhispers (str) {
    if (str.includes('/whisper')) {
        const username = str.split('/whisper')[1].split(' ')[1]
        const message = str.split('/whisper ' + username)[1]
        if (message && message.trim().length) {
            str = '<b>Whisper to ' + username + ': </b>' + message
        }
    }

    return str
}

function renderAuthor (str) {
    const primary_role = str.primary_role
    const roles = str.roles.map(function (obj) {
        return obj.id
    })

    str = "<span class='" + str.roles[roles.indexOf(parseInt(primary_role))].name.toLowerCase() + "'>" + str.name + '</span>'

    return str
}

emojione.ascii = true

Vue.directive('message-render', {
    inserted: function inserted (el, binding) {
        el.innerHTML = getUserMentions(linkifyHtml(emojione.toImage(getWhispers(binding.value))))
    }
})

Vue.directive('message-author-render', {
    inserted: function inserted (el, binding) {
        el.innerHTML = renderAuthor(binding.value)
    }
})

Vue.filter('messageAuthor', function (value) {
    if (value) {
        return renderAuthor(value)
    }
})

Vue.filter('formatDate', function (value) {
    if (value) {
        return moment(String(value)).format('HH:mm')
    }
})

Vue.filter('message', function (value) {
    if (value) {
        return getWhispers(getUserMentions(linkifyHtml(emojione.toImage(value))))
    }
})
