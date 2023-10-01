'use strict';

document.addEventListener('fullscreenchange', exitHandler);
document.addEventListener('webkitfullscreenchange', exitHandler);
document.addEventListener('mozfullscreenchange', exitHandler);
document.addEventListener('MSFullscreenChange', exitHandler);

function exitHandler() {
    if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        localStorage.setItem("fullscreenmodeflag", null);
    }
} 

$(function(){
  $('#sidebar__menuWrapper').slimScroll({
    height: 'calc(100vh - 86.75px)'
  });
});

$(function(){
  $('.dropdown-menu__body').slimScroll({
    height: '270px'
  });
});

// modal-dialog-scrollable
$(function(){
  $('.modal-dialog-scrollable .modal-body').slimScroll({
    height: '100%'
  });
});

// activity-list 
$(function(){
  $('.activity-list').slimScroll({
    height: '385px'
  });
});

// recent ticket list 
$(function(){
  $('.recent-ticket-list__body').slimScroll({
    height: '295px'
  });
});


$('.countdown').each(function(){
    var date = $(this).data('date');
    $(this).countdown({
        date: date,
        offset: +6,
        day: 'Day',
        days: 'Days'
    });
});


$('#navbar-search__field').on('input', function () {
    var search = $(this).val().toLowerCase();


    var search_result_pane = $('.navbar_search_result');
    $(search_result_pane).html('');
    if (search.length == 0) {
        return;
    }

    // search
    var match = $('.sidebar__menu-wrapper .nav-link').filter(function (idx, elem) {
        return $(elem).text().trim().toLowerCase().indexOf(search) >= 0 ? elem : null;
    }).sort();




    // show search result
    // search not found
    if (match.length == 0) {
        $(search_result_pane).append('<li class="text-muted pl-5">No search result found.</li>');
        return;
    }
    // search found
    match.each(function (idx, elem) {
        var item_url = $(elem).attr('href') || $(elem).data('default-url');
        var item_text = $(elem).text().replace(/(\d+)/g, '').trim();
        $(search_result_pane).append(`<li><a href="${item_url}">${item_text}</a></li>`);
    });


});

let img = $('.bg_img');
img.css('background-image', function () {
  let bg = ('url(' + $(this).data('background') + ')');
  return bg;
});

  const navTgg = $('.navbar__expand');
  navTgg.on('click', function(){
    $(this).toggleClass('active');
    $('.sidebar').toggleClass('active');
    $('.navbar-wrapper').toggleClass('active');
    $('.body-wrapper').toggleClass('active');
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

  // navbar-search 
  $('.navbar-search__btn-open').on('click', function (){
    $('.navbar-search').addClass('active');
  }); 

  $('.navbar-search__close').on('click', function (){
    $('.navbar-search').removeClass('active');
  }); 

  // responsive sidebar expand js 
  $('.res-sidebar-open-btn').on('click', function (){
    $('.sidebar').addClass('open');
  }); 

  $('.res-sidebar-close-btn').on('click', function (){
    $('.sidebar').removeClass('open');
  }); 

/* Get the documentElement (<html>) to display the page in fullscreen */
let elem = document.documentElement;

/* View in fullscreen */
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
    localStorage.setItem("fullscreenmodeflag", "mode");
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
    localStorage.setItem("fullscreenmodeflag", "mode");
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
    elem.webkitRequestFullscreen();
    localStorage.setItem("fullscreenmodeflag", "mode");
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
    localStorage.setItem("fullscreenmodeflag", "mode");
  }
}

/* Close fullscreen */
function closeFullscreen() {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) { /* Firefox */
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE/Edge */
    document.msExitFullscreen();
  }
}

// $('.fullscreen-btn').on('click', function(){
//   $(this).toggleClass('active');
// }); 

$('.sidebar-dropdown > a').on('click', function () {
  if ($(this).parent().find('.sidebar-submenu').length) {
    if ($(this).parent().find('.sidebar-submenu').first().is(':visible')) {
      $(this).find('.side-menu__sub-icon').removeClass('transform rotate-180');
      $(this).removeClass('side-menu--open');
      $(this).parent().find('.sidebar-submenu').first().slideUp({
        done: function done() {
          $(this).removeClass('sidebar-submenu__open');
        }
      });
    } else {
      $(this).find('.side-menu__sub-icon').addClass('transform rotate-180');
      $(this).addClass('side-menu--open');
      $(this).parent().find('.sidebar-submenu').first().slideDown({
        done: function done() {
          $(this).addClass('sidebar-submenu__open');
        }
      });
    }
  }
});

// select-2 init
$('.select2-basic').select2();
$('.select2-multi-select').select2();
$(".select2-auto-tokenize").select2({
  tags: true,
  tokenSeparators: [',']
});

var imagenum = 1;
if(Number($('#imagenum_hidden').val()) == 0) {
  imagenum = 1;
}
else {
  imagenum = Number($('#imagenum_hidden').val()) + 1;
}

async function proStatusUpload(input, stype, wid) {
    if(input.files && input.files[0]) {
        if(Number(input.files[0].size / 1024 / 1024) <= 3) {
            var stoken = $('#status_token_hidden').val();
            var surl = $('#statusurl_hidden').val()
    
            var formData = new FormData();
            formData.append("imagefile", input.files[0]);
            formData.append("_token", stoken);
            formData.append("stype", stype);
            formData.append("id", wid);
            
            await $.ajax({
              method: 'post',
              processData: false,
              contentType: false,
              cache: false,
              data: formData,
              enctype: 'multipart/form-data',
              url: surl,
              success: function (responseURL) {
                iziToast['success']({
                    message: "Upload Success!",
                    position: "topRight"
                });
                location.reload();
              },
              error: function(data){
                return;
                location.reload();
              }
            });
        } else {
            iziToast['error']({
                message: "Size is larger than 3MB!",
                position: "topRight"
            });
        }
    }
}

async function proPicURL(input) {
    if (input.files && input.files[0]) {
        var percent_num = input.files.length;
        var percent_length = 0;
        for(var ii = 0; ii < input.files.length; ii ++)
        {
            if(Number(input.files[ii].size / 1024 / 1024) <= 3) {
                $('#percent_span').text(percent_length + '%');
                $('#bar_div').css('width', percent_length + '%');
                $('.percent_div').css('display', 'flex');
        
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    var preview = $(input).parents('.thumb').find('.profilePicPreview');
                    $(preview).css('background-image', 'url(' + e.target.result + ')');
                    $(preview).addClass('has-image');
                    $(preview).hide();
                    $(preview).fadeIn(650);
                    
                    $('.imagelist_block').append(`<div class="image_data_item" style="text-align: center;position: relative; width: 45px; height: 35px; margin: 2px;display: inline-block;"><input name="imagereplaceinput[`+imagenum+`][url]" id="imagereplaceinput`+imagenum+`" type="hidden" required><img id="image_replace_id` + imagenum + `" src="https://7-bids.com/assets/images/loading.gif" class="replace-image" style="height: 35px; cursor: pointer;" ><div style="position: absolute; right: 0; top: 0; transform: translate(50%,-50%); display: flex; justify-content: center; align-items: center; width: 10px; height: 10px; cursor: pointer; background-color: #ea5455; border-radius: 50%;" class="img_item_remove"><i class="fa fa-times" style="font-size: 8px; color: white;"></i></div></div>`);
                }
                
                reader.readAsDataURL(input.files[ii]);
                
                var token = $('#cs_token_hidden').val();
                var url = $('#url_hidden').val()
        
                var formData = new FormData();
                formData.append("imagefile", input.files[ii]);
                formData.append("_token", token);
                
                await $.ajax({
                  method: 'post',
                  processData: false,
                  contentType: false,
                  cache: false,
                  data: formData,
                  enctype: 'multipart/form-data',
                  url: url,
                  success: function (responseURL) {
                    $('#bar_div').css('width', Number(100 / percent_num * (ii + 1)).toFixed(1) + '%');
                    percent_length = Number(100 / percent_num * (ii + 1)).toFixed(1);
                    $('#percent_span').text(Number(100 / percent_num * (ii + 1)).toFixed(1) + '%');
                    document.getElementById("imagereplaceinput"+imagenum).value = responseURL;
                    document.getElementById("image_replace_id"+imagenum).src = "https://7-bids.com/assets/images/product/" + responseURL;
                    document.getElementById("image_replace_id"+imagenum).style.width = "45px";
                    imagenum ++;
                    if(Number(percent_length) == 100) {
                        $('.progress_exit i').click();
                    }
                  },
                  error: function(data){
                    return;
                  }
                });
            }
            else {
                $('.progress_exit i').click();
                iziToast['error']({
                    message: "Size is larger than 3MB!",
                    position: "topRight"
                });
            }
        }
    }
}

$(".profilePicUpload").on('change', function () {
    proPicURL(this);
});

$(".pendingpicupload").on('change', function() {
    proStatusUpload(this, "pending", $(this).data('winid'));
});

$(".paidpicupload").on('change', function() {
    proStatusUpload(this, "paid", $(this).data('winid'));
});

$(".pickedpicupload").on('change', function() {
    proStatusUpload(this, "picked", $(this).data('winid'));
});

$(".packedpicupload").on('change', function() {
    proStatusUpload(this, "packed", $(this).data('winid'));
});

$(".remove-image").on('click', function () {
    $(this).parents(".profilePicPreview").css('background-image', 'none');
    $(this).parents(".profilePicPreview").removeClass('has-image');
    $(this).parents(".thumb").find('input[type=file]').val('');
});

$("form").on("change", ".file-upload-field", function(){ 
  $(this).parent(".file-upload-wrapper").attr("data-text",$(this).val().replace(/.*(\/|\\)/, '') );
});

//Custom Data Table
$('.custom-data-table').closest('.card').prepend('<div class="card-header" style="border-bottom: none;"><div class="text-right"><div class="form-inline float-sm-right bg--white"><input type="text" name="search_table" class="form-control" placeholder="Search"></div></div></div>');
$('.custom-data-table').closest('.card').find('.card-body').attr('style','padding-top:0px');
var tr_elements = $('.custom-data-table tbody tr');
$(document).on('input','input[name=search_table]',function(){
  var search = $(this).val().toUpperCase();
  var match = tr_elements.filter(function (idx, elem) {
    return $(elem).text().trim().toUpperCase().indexOf(search) >= 0 ? elem : null;
  }).sort();
  var table_content = $('.custom-data-table tbody');
  if (match.length == 0) {
    table_content.html('<tr><td colspan="100%" class="text-center">Data Not Found</td></tr>');
  }else{
    table_content.html(match);
  }
});