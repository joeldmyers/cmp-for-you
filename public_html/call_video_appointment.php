<?php require_once("includes/top.php");
 //require_once("includes/authentication.php");
 $today_date=date("Y-m-d");
 extract($_REQUEST);
 $booking =  $db->Execute("select", "SELECT *,DATE_FORMAT(start_time,'%Y-%m-%d') as cur_datetime,DATE_FORMAT(start_time,'%m/%d/%Y %H:%i') as starttime,DATE_FORMAT(end_time,'%m/%d/%Y %H:%i') as endtime FROM ".BOOKING."  where status=2 AND booking_patient_id = '".$_SESSION['emp_id']."' AND booking_id='$bookingid'");
 function patient_name(){
	 $doctordata =mysql_query("SELECT   patient_email FROM " . PATIENTS . "  where patient_id = '" . $_SESSION['emp_id'] . "'");
	 $doctordata=mysql_fetch_assoc($doctordata);
	 $patient_email = $doctordata['patient_email'];
	 return $patient_email;
 }
$emapname="'".$_SESSION["emp_name"]."'";
$email=patient_name();
$email='"'.$email.'"'.',"'."rasdev@itsapp2you.com".'",'.'"'."itapp2u@gmail.com".'"';
$key=$booking[0]['api_key'];
$token=$booking[0]['token'];
$sid=$booking[0]['sid'];

 ?>
<html lang="en"><head>
  <title>Calling App</title>
  <meta charset="UTF-8">
<meta name="description" content=""> 
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<link href="https://fonts.googleapis.com/css?family=Muli:300,400" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//tokbox.com/css/shared/fonts.css">
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="//code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="js/bootstrap.js"></script>


<link type="text/css" media="screen" rel="stylesheet" href="http://static.opentok.com/webrtc/v2.2.9.6/css/ot.min.css"></head>
<body>

<div class="container">
<div  align="center"><img src='http://cmpforyou.com/includes/images/logo3.png' /></div>
  <div id="streams_container">
    <div class="streamContainer" id="myPublisherContainer" style="position: absolute; left: 0px; top: 100px; width: 1366px; height: 339px;">
      <div id="myPublisher" class="OT_mirrored OT_root OT_publisher OT_audio-only" style="width: 100%; height: 100%; overflow: hidden;"><div class="OT_video-container" style="width: 100%; height: 302.212389380531%; left: 0px; top: -101.106194690265%;"><div class="OT_video-loading"></div><div class="OT_video-poster" style="display: block;"></div><video autoplay="" src="blob:http%3A//opentokrtc.com/f0d3628c-93b2-4afe-a37d-742bae0738cf">Sorry, Web RTC is not available in your browser</video></div><div class="OT_bar OT_edge-bar-item OT_mode-auto"></div><h1 class="OT_name OT_edge-bar-item OT_mode-off"></h1><button class="OT_edge-bar-item OT_mute OT_mode-auto">Mute</button><h1 class="OT_archiving OT_edge-bar-item OT_edge-bottom OT_archiving-off OT_mode-off"><div class="OT_archiving-light-box"><div class="OT_archiving-light"></div></div><div class="OT_archiving-status OT_mode-on OT_edge-bar-item OT_edge-bottom"></div></h1><div class="OT_audio-level-meter OT_mode-auto"><div class="OT_audio-level-meter__bar"></div><div class="OT_audio-level-meter__audio-only-img"></div><div class="OT_audio-level-meter__value" style="height: 0%; width: 0%; right: 0%; top: 0%;"></div></div></div>
    </div>
  
  
</div>

  <div id="controlsContainer">
    <div class="controlsBody">
      <div id="controlOptions" class="optionContainer">
       <!-- <a href="http://tokbox.com/?utm_source=opentokrtc&amp;utm_medium=web&amp;utm_campaign=webrtc" id="opentokLogo" target="_blank">
      <img src="/img/img-opentok-logo.png" alt="opentok transparent logo">
    </a>-->
    <div id="controlOptionsRight">
     <!-- <a href="mailto:song@tokbox.com?subject=OpenTokRTC%20Feedback" id="mailButton" data-tooltip-title="Send Feedback!" class="controlOption option" data-original-title="" title="">
      </a>-->
    <!--  <div id="filtersButton" data-tooltip-title="Filters" class="controlOption option popover-dismiss" data-toggle="popover" data-placement="top" data-html="true" title="" data-content="<ul id='filtersList'><li><button data-value='Sepia' type='button'>Sepia</button></li><li><button data-value='Invert' type='button'>Inverted</button></li><li><button data-value='Grayscale' type='button'>Grayscale</button></li><li><button data-value='Blur' type='button'>Blur</button></li><li><button data-value='None' type='button'>None</button></li></ul>" data-original-title="Filters"></div>
      <div id="roomLinkButton" data-tooltip-title="Share Room Link!" title="" class="controlOption option popover-dismiss" data-placement="top" data-activity="roomLink" data-original-title="Share Room Link!">
      </div>
      <div id="recordButton" data-tooltip-title="Start Recording" class="controlOption option" data-activity="record" data-original-title="" title="">
      </div>
      <div id="chatButton" data-tooltip-title="Text Chat" class="controlOption option no-after" data-activity="chat" data-original-title="" title="">
      </div>-->
      </div>
    </div>
      </div>
    </div>


  </div>


<script id="chattrBaseTpl" type="text/x-handlebars-template">
  <div id = 'chattr'> 
    <div id ='relative_wrapper'>
      <div id='chat_header' class='chat-header' > 
        <div><span></span><span></span><span></span></div> 
        <h4 id='roomId'></h4> 
      </div> 
      <div class='inner-chat'> 
        <p id = 'displayHelp'> Type /help for a list of commands 
        <ul id='messages'> 
        </ul> 
      </div> 
      <div class='chat-input-wrapper'> 
        <input type='text' id='chatInput' placeholder= 'Write here...'  /> 
      </div> 
    </div>
  </div>
</script>
<script id="chattrChatTpl" type="text/x-handlebars-template">
  <li class="chat {{cls}}">
  <label>{{nickname}}</label>
  <p data-date="{{date}}" title="{{time}}">{{message}}</p>
</script>
<script id="chattrStatusTpl" type="text/x-handlebars-template">
  <li class = 'status'>
    <p><span class='oldName'>{{oldName}}</span> is now known as <span class='newName'>{{newName}}</span></p>
  </li>
</script>
<script id="chattrNewUserTpl" type="text/x-handlebars-template">
  <li class= "status newUser">
    <p><span>{{nickname}}</span> has joined the room</p>
  </li>
</script>
<script id="chattrUserLeaveTpl" type="text/x-handlebars-template">
  <li class= "status newUser">
    <p><span>{{nickname}}</span> has left the room</p>
  </li>
</script>
<script id="chattrUpdateTpl" type="text/x-handlebars-template">
  <li class="status {{cls}}">
  {{{text}}}
  </li>
</script>
<script id="chattrUserListTpl" type="text/x-handlebars-template">
  <p class='userList'>Users in this room right now</p>
  {{#users}}
  <p class = "userList {{last}}">- {{name}}</p>
  {{/users}}
</script>
<script id="chattrHelpTpl" type="text/x-handlebars-template">
  <p>Type <span>/name your_name</span> to change your display name</p> 
  <p>Type <span>/list</span> to see a list of users in the room</p> 
  <p>Type <span>/focus</span> to focus the room on yourself</p>
  <p>Type <span>/unfocus</span> to take focus away from any focussed people in the room</p>
  <p class='last'>Type <span>/help</span> to see a list of commands</p>
</script>
<script id="chattrNameExistsTpl" type="text/x-handlebars-template">
  <p>User <span>{{newName}}</span> already exists. Please choose another name.</p>
</script>
<script id="chattrFocusTpl" type="text/x-handlebars-template">
  <li class="status">
    <p>User <span>{{nick}}</span> now has focus.</p>
  </li>
</script>
<script id="chattrUnfocusTpl" type="text/x-handlebars-template">
  <li class="status">
    <p>Focus has been removed.</p>
  </li>
</script>


<script id="userStreamTemplate" type="text/x-handlebars-template">
  <div class="streamContainer {{id}}" data-connectionid="{{connectionId}}">
    <div id="{{id}}"></div>
    <div class="flagUser" data-streamconnection="{{id}}">Remove User</div>
  </div>
</script>

<link href="https://fonts.googleapis.com/css?family=Muli:300,400" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//tokbox.com/css/shared/fonts.css">
<link rel="stylesheet" href="http://opentokrtc.com/vendors/fontAwesome/css/font-awesome.css">
<link rel="stylesheet" href="http://opentokrtc.com/vendors/bootstrap/css/bootstrap.min.css">

<script src="//code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="http://opentokrtc.com/vendors/bootstrap/js/bootstrap.js"></script>


<link rel="stylesheet" href="css/room.css">
<link rel="stylesheet" href="css/OpenTokChattr.css">
<script src="//static.opentok.com/v2.2/js/opentok.min.js"></script>
<script src="js/handlebars.js" type="text/javascript"></script>
<script src="js/layoutContainer.js" type="text/javascript"></script>
<script src="js/jquery.event.ue.js" type="text/javascript"></script>
<script src="js/jquery.udraggable.js" type="text/javascript"></script>
<script src="js/ZeroClipboard.min.js" type="text/javascript"></script>
<script src="js/OpenTokChattr.js" type="text/javascript"></script>

<script src="js/room.js" type="text/javascript"></script>
<script type="text/javascript">
  var session = OT.initSession("<?php echo $key; ?>", "<?php echo $sid; ?>");      var options =
            {
                // subscribeToAudio:true,      // Set Enable disable audio streaming
                // subscribeToVideo:false,     // Set Enable disable Video streaming
                // insertMode: 'append',

            };


        // subscriber.subscribeToAudio(false); // audio off
        // subscriber.subscribeToAudio(true); // audio on
        // subscriber.subscribeToVideo(false); // video off
        // subscriber.subscribeToVideo(true); // video on


        session.on({
            streamCreated: function(event)
            {
                //session.subscribe(event.stream, 'subscribersDiv'); // subscriber = session.subscribe(stream, replacementElementId, options);
            }
        });
        session.connect("<?php echo $token; ?>", function(error) {
            if (error) {
                console.log(error.message);
            } else {
                session.publish('myPublisherContainer', {name: <?php echo $emapname; ?>});
            }
        });
  // var publisher = TB.initPublisher("45299152", 'myPublisherContainer');

  //session.publish( publisher );
  chattr = new OpenTokChattr("body","Nils",session,
 {"draggable": true,
  "startPosition":{
    x: startX,
    y: startY
  },
  "closeable": function(){
    $("#chattr").hide();
    $("#chatButton").removeClass("selected");
  }
 });
  room = new Room("Nils", session, "<?php echo $token; ?>", chattr);
  var startX = $(window).width()-10-$("#chattr").width();
  var startY = $(window).height()-56-$("#chattr").height();
  $('#filtersButton').popover();
  $(".controlOption").each(function(){
    $(this).tooltip({
      title: $(this).data("tooltip-title"),
      placement: "top"
    });
  });
  $('body').on('click', function (e) {
    $('.popover-dismiss').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
            $(this).removeClass('selected');
        }
    });
  });
  $("#roomLinkButton").popover({
    title: $(this).attr("title"),
    content: "<input id='roomLinkInput' type='text' value='"+window.location.origin+"/Nils' ><button id='copyButton'>Copy</button>",
    html: true
  });
  $("#roomLinkButton").on('shown.bs.popover', function(){
    var client = new ZeroClipboard($("#copyButton"));
    client.on("ready", function(readyEvent){
      client.on("copy", function(event){
        var clipboard = event.clipboardData;
        clipboard.setData( "text/plain", window.location.origin+"/Nils" );
      });
      client.on("aftercopy", function(event){
        $("#copyButton").html("Copied!");
      });
    });
  });
  $(".controlOption").click(function(){
    $(this).toggleClass("selected");
  });
</script>
 



</body></html>
