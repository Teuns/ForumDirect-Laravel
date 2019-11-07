require('./bootstrap');

Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);

window.app = new Vue({
    el: 'main#app',

    data: {
        messages: [],
    },

    created() {
        Echo.private('chat')
            .listen('MessageSent', (e) => {
                this.messages.push({
                    message: e.message.message,
                    created_at: e.message.created_at,
                    type: e.message.type,
                    to_uid: e.message.to_uid,
                    from_uid: e.message.from_uid,
                    user: e.user,
                });
             });
    },
    
    methods: {
        addMessage(message) {
            if (message.message && message.message.trim().length){
                var startDate = moment();

                axios.get('/messages').then(response => {  
                    var l = response.data.length;

                    while (l--) {
                        if(response.data[l].user_id == message.user.id){
                            break;
                        }
                    }

                    if (response.data[l] && startDate.diff(moment(response.data[l].created_at), 'seconds') >= 3 || !response.data[l]){
                        if (message.message.includes('/whisper')){
                            Vue.set(message, 'type', 'whisper');
                            Vue.set(message, 'created_at', new Date());
                            Vue.set(message, 'from_uid', parseInt(message.user.id));

                            var username = message.message.split('/whisper')[1].split(' ')[1];

                            var realUsername = request('GET', '/api/v1/users/name/' + username);

                            message.message = message.message.replace(username, realUsername.getBody('utf8'));

                            var res = request('GET', '/api/v1/users/id/' + username);
                            if (res.statusCode == 200){
                                var userId = res.getBody('utf8');

                                Vue.set(message, 'to_uid', parseInt(userId));

                                this.messages.push(message);

                                window.app.$children[window.app.$children.length -1].$refs.form.__vue__.disableInputBool = false;

                                window.app.$children[window.app.$children.length -1].$refs.form.__vue__.newMessage = '';

                                axios.post('/messages', message).then(response => { });
                            }
                        } else { 
                            Vue.set(message, 'type', 'post');
                            Vue.set(message, 'created_at', new Date());

                            this.messages.push(message);

                            window.app.$children[window.app.$children.length -1].$refs.form.__vue__.disableInputBool = false;

                            window.app.$children[window.app.$children.length -1].$refs.form.__vue__.newMessage = '';

                            axios.post('/messages', message).then(response => { console.log(response); });
                        }
                    } else {
                        window.app.$children[window.app.$children.length -1].$refs.form.__vue__.disableInputBool = false;
                    }
                });
            }
        },
    },

    updated(){
        var container = document.getElementById('container');
        container.scrollTop = container.scrollHeight;
    },

    ready(){
        var container = document.getElementById('container');
        container.scrollTop = container.scrollHeight;
    },
});

var request = require('sync-request');

function replaceArray(str, find, replace) {
    var replaceString = str;
    var regex; 
    for (var i = 0; i < find.length; i++) {
      regex = new RegExp(find[i], "g");
      replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};

function getUserMentions(str){
    var qualityRegex = /\@([^ ]\w*)/g,
    matches,
    qualities = [],
    replace = [];

    while (matches = qualityRegex.exec(str)) {
        var res = request('GET', '/api/v1/users/name/' + matches[1]);
        var res2 = request('GET', '/api/v1/users/roles/' + matches[1]);
        if(res.statusCode == 200 && res2.statusCode == 200){
            qualities.push('@' + matches[1]); 
            replace.push("@<a href='/users/" + res.getBody('utf8') + "' class='" + res2.getBody('utf8').toLowerCase() + "'>" + res.getBody('utf8') + "</a>");
        }
    }

    return replaceArray(str, qualities, replace);
}

function getWhispers(str){
    if(str.includes("/whisper")){
        var username = str.split('/whisper')[1].split(' ')[1];
        var message = str.split('/whisper ' + username)[1];
            if(message && message.trim().length){
                str = "<b>Whisper to " + username + ": </b>" + message;
            }
    }

    return str;
}

function renderAuthor(str){
    var primary_role = str.primary_role;
    var roles = str.roles.map(function(obj) {
        return obj.id;
    });

    str = "<span class='" + str.roles[roles.indexOf(parseInt(primary_role))].name.toLowerCase() + "'>" + str.name + "</span>";

    return str;
}

var emojione = require('emojione');
var linkifyHtml = require('linkifyjs/html');

emojione.ascii = true;

Vue.directive('message-render', {
    inserted: function inserted(el, binding) {
        el.innerHTML = getUserMentions(linkifyHtml(emojione.toImage(getWhispers(binding.value))));
    }
});

Vue.directive('message-author-render', {
    inserted: function inserted(el, binding) {
        el.innerHTML = renderAuthor(binding.value);
    }
});

Vue.filter('messageAuthor', function(value) {
  if (value) {
    return renderAuthor(value);
  }
});

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format('HH:mm');
  }
});

Vue.filter('message', function(value) {
  if (value) {
    return getWhispers(getUserMentions(linkifyHtml(emojione.toImage(value))));
  }
});