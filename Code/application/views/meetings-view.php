<?php $this->load->view('includes/head'); ?>
</head>
<body class="w-100 h-100" id="app">
  <section class="section w-100 h-100" id="meetting">
  </section>
  <section class="section w-100 h-100 d-none" id="meetting_over">
    <div class="jumbotron w-100 h-100 text-center align-middle">
      <h2 class="text-danger"><?=$this->lang->line('meeting_over')?$this->lang->line('meeting_over'):'Meeting Over'?></h2>
      <a href="<?=base_url('meetings')?>"><p class="lead text-info"><?=$this->lang->line('go_back')?$this->lang->line('go_back'):'Go Back'?></p></a>
    </div>
  </section>
  <section class="section d-none" id="leave">
    <button type="button" class="btn btn-danger btn-block">
      <?=$this->lang->line('leave')?$this->lang->line('leave'):'Leave'?>
    </button>
  </section>

<?php $this->load->view('includes/js'); 

if($this->ion_auth->is_admin() || $meeting[0]['created_by'] == $this->session->userdata('user_id')){
  $isadmin = 'true';
  $disableRemoteMute = 'false';
  $disableKick = 'false';
  $SETTINGS_SECTIONS = "'devices', 'language', 'moderator', 'profile'";
  $TOOLBAR_BUTTONS = "'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
  'fodeviceselection', 'profile', 'chat',
  'etherpad', 'sharedvideo', 'settings', 'raisehand',
  'videoquality', 'filmstrip', 'stats', 'shortcuts',
  'tileview', 'select-background', 'download', 'mute-everyone', 'mute-video-everyone', 'security'";
}else{
  $isadmin = 'false';
  $disableRemoteMute = 'true';
  $disableKick = 'true';
  $SETTINGS_SECTIONS = "'language', 'profile'";
  $TOOLBAR_BUTTONS = "'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
  'fodeviceselection', 'profile', 'chat', 'etherpad', 'sharedvideo', 'raisehand',
  'videoquality', 'filmstrip', 'stats', 'shortcuts',
  'tileview', 'select-background', 'download'";
}
?>

<script src='https://meet.jit.si/external_api.js'></script>
<script>
var isadmin = <?php echo $isadmin; ?>;
var disable_Remote_Mute = <?php echo $disableRemoteMute; ?>;
var disable_Kick = <?php echo $disableKick; ?>;
var meetings_id = <?php echo $meeting[0]['id']; ?>;
var liveRoomName = "<?php echo $meeting[0]['title'].' - '.substr(hash('md5', $meeting[0]['id']), 0, 6); ?>";
var liveemail = "<?php echo $current_user->email; ?>";
var liveDisplayName = "<?php echo $current_user->first_name.' '.$current_user->last_name; ?>";
const domain = 'meet.jit.si';
const options = {
    roomName: liveRoomName,
    configOverwrite: { prejoinPageEnabled: false, remoteVideoMenu: { disableKick: disable_Kick, }, disableRemoteMute: disable_Remote_Mute, disableProfile: true, disableInviteFunctions: true, enableWelcomePage: false, enableClosePage: false },
    interfaceConfigOverwrite: { CLOSE_PAGE_GUEST_HINT: false, SHOW_PROMOTIONAL_CLOSE_PAGE: false, SHOW_CHROME_EXTENSION_BANNER: false, HIDE_INVITE_MORE_HEADER: true, SETTINGS_SECTIONS: [ <?php echo $SETTINGS_SECTIONS; ?> ], SHARING_FEATURES: [], TOOLBAR_BUTTONS: [ <?php echo $TOOLBAR_BUTTONS; ?> ] },
    userInfo: {
        email: liveemail,
        displayName: liveDisplayName
    },
    parentNode: document.querySelector('#meetting')
};
const api = new JitsiMeetExternalAPI(domain, options);

api.addEventListener('videoConferenceJoined', function () {
  $('#leave').removeClass("d-none");
});

$(document).on('click', '#leave', function(e){
	e.preventDefault();
  if(isadmin){
    var participants = api.getParticipantsInfo();
    $(participants).each(function(key, value){
      api.executeCommand('sendEndpointTextMessage', value.participantId, 'leave');
    });
  }else{
    window.location.replace(base_url+"meetings");
  }
  $('#leave').addClass("d-none");
  $('#meetting').addClass("d-none");
  $('#meetting_over').removeClass("d-none");
  
  $.ajax({
    url: base_url+'meetings/end/'+meetings_id       
  });
});

api.addListener('endpointTextMessageReceived', function(event) {
  if(event.data.eventData.text == 'leave'){
    api.dispose();
    $('#leave').addClass("d-none");
    $('#meetting').addClass("d-none");
    $('#meetting_over').removeClass("d-none");
  }
});

</script>

</body>
</html>
