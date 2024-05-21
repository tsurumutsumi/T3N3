<?php
$(document).on('click','.follow_btn',function(e){
    e.stopPropagation();
    var $this = $(this),
    current_user_id = $('.current_user_id').val();
    user_id = $this.prev().val();
    $.ajax({
        type: 'POST',
        url: '../ajax_follow_process.php',
        dataType: 'json',
        data: { current_user_id: current_user_id,
                user_id: user_id }
    }).done(function(data){
      location.reload();
    }).fail(function(){
      location.reload();
    });
  });
  ?>