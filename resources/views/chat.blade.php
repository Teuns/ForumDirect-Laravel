<?php
use App\User;
if(Auth::user()){
    $query = 
        User::with(['roles' => function($q) {
            $q->where('role_id', '=', Auth::user()->primary_role);
        }])
        ->where('id', Auth::user()->id)->first();
}else{
    $query = null;
}
?>
<div id="chat-app">
  <section class="chat-app">
        <div class="panel">
            <div class="panel-body" id="container">
                <div class="panel-title">Chats</div>
                <chat-messages :messages="messages" :user="{{ $query }}"></chat-messages>
            </div>
            <div class="panel-footer">
                <chat-form
                    v-on:messagesent="addMessage"
                    :user="{{ $query }}"
                ></chat-form>
            </div>
        </div>
  </section>
</div>
<br>