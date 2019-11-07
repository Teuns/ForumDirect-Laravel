$(document).ready(function() {
  var simplemde = new SimpleMDE({ element: document.getElementById("body") });

  function init() {
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],

        [{ 'header': 1 }, { 'header': 2 }],  
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'direction': 'rtl' }],

        ['clean'],
        ['image']
    ];

    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: imageHandler
                }
            }
        },
    });

    function imageHandler() {
        var range = this.quill.getSelection();
        var value = prompt('What is the image URL?');
        if(value){
            this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
        }
    }

    var md = window.markdownit();
    md.set({
      html: true
    });

    var html = quill.container.firstChild.innerHTML;
    $("#text-body").text(toMarkdown(html));

    quill.on("text-change", function(delta, source) {
      var html = quill.container.firstChild.innerHTML;
      var markdown = toMarkdown(html);
      $("#text-body").text(markdown);
    });
  }

  init();

  $("#quickReplyForm").fadeIn(500);
});

var auto_refresh_recent = setInterval(function () {
  $('#recent').animate({ opacity: 0.3 }, function() {
      $(this).load('/api/v1/posts/recent/', function() {
          $(this).css("opacity", "");
      });
  });
}, 30000);

$(document).on("click", "#recentButton", function(){
  $("#recent").load('/api/v1/posts/recent/');  
}); 

function report() {
	if(document.getElementById("reason").value && document.getElementById("reason").value.trim().length){
		var http = new XMLHttpRequest();
		var url = '/api/v1/report';
		var params = 'type=' + document.getElementById("modal-report_type").value + '&_id=' + document.getElementById("modal-report_id").value + '&reason=' + document.getElementById("reason").value;
		http.open('POST', url, true);
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		http.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
		http.onreadystatechange = function() {
			if (http.readyState == 4 && http.status == 200) {
				document.getElementById("modal-body").innerHTML = '<div class="notice notice-success" role="alert">Successfully reported!</div>';
			} else if (http.readyState == 4 && http.status == 500) {
				alert("Reporting failed, please send the http.responeText from the console to the forum administrator.")
				console.log(http.responseText);
			}
		}

		http.send(params);
	}
}

function flag(event){
	document.getElementById("modal-body").innerHTML += "<input class='input' id='modal-report_type' value='" + event.getAttribute("data-type") + "' style='display: none;'>";
	document.getElementById("modal-body").innerHTML += "<input class='input' id='modal-report_id' value='" + event.getAttribute("data-id") + "' style='display: none;'>";
}

$(document).on('hidden.bs.modal','#reportModal', function () {
  if(document.getElementById("modal-report_type")) document.getElementById("modal-report_type").outerHTML = "";
  if(document.getElementById("modal-report_id")) document.getElementById("modal-report_id").outerHTML = "";
  document.getElementById("modal-body").innerHTML = '<form><input class="form-control" id="reason" placeholder="Enter your reason for your report here..."></form>';
});

$(document).ready(function() {
  var elements = document.getElementsByClassName('like__btn');
  for(var i = 0; i < elements.length; i++) {
      var element = elements[i];
      console.log(element);
      element.onclick = function() {
        if (!this.classList.contains('like__btn--disabled')) {
          console.log(true);
        	var http = new XMLHttpRequest();
        	var url = "/api/v1/like";
        	var id = this.dataset.pid;
        	var tid = this.dataset.tid;
        	var params = JSON.stringify({ pid: id, tid: tid });
        	http.open("POST", url, true);
        	http.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        	http.setRequestHeader("Content-type", "application/json; charset=utf-8");
        	    http.onreadystatechange = function() {
        	        if(http.readyState == 4 && http.status == 200) {
        	      		console.log(http.responseText);
        	      	} else {
                    console.log(http.responseText);
                  }
        	    }
        	http.send(params);
        	updated_likes = parseInt(this.querySelectorAll("span")[0].innerHTML) + 1;
      this.querySelectorAll("span")[0].innerHTML = updated_likes;
        }
        this.setAttribute('disabled', true);
        this.classList.add('tada');
      }
  }
});

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkTheme() {
  var theme = getCookie("theme");
  console.log(theme);
  if (theme == "default") {
    $("html").attr('data-theme', 'default');
  } else if(theme == "dark") {
     $("html").attr('data-theme', 'dark');
  }
} 

checkTheme();