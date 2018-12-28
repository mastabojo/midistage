// first things first
$( "document" ).ready(function() {
	$('form input:first').focus();
});

// autosubmit for select element
$('select.autosubmit').on('change', function() {
    this.form.submit();
});

// PERFORMANCE: clicking on a play char selects the row and sends ajax for patch changes
$('.send-patch').on("click", function() {

	// remove active class from active row and play char
	$('.table').find('tr.song-data-active').each(function() {
		$(this).removeClass("song-data-active").addClass("song-data");
	});
	$('.table').find('td.play-char-active').each(function() {
		$(this).removeClass('play-char-active').addClass("play-char");
	});

	// add active class to the clicked row
	$(this).parent().parent().addClass("song-data-active");
	// add active class to the clicked play char
	$(this).parent().addClass("play-char-active");
	
	var song_id = $(this).parent().parent().attr("id");
	$.post("send_midi_data.php",
	{song_id: song_id},
	// on success
	function() {
		// do whatever
	});
});

// MANAGE: clicking on item table row - edit TD (edit item)
$(".item-table td.edit-entity").on("click", function() {
    window.location.href = "edit_" + $(this).parent().data("entity") + ".php?id=" + $(this).parent().data("id");
})

// clicking on item table row - manage TD (manage item - for songlists only)
$(".item-table td.manage-entity").on("click", function() {
    window.location.href = "manage_" + $(this).parent().data("entity") + ".php?id=" + $(this).parent().data("id");
    
})

// clicking on remove glyph in the table row
$(".item-table td span.glyphicon-remove").on("click", function() {
	
	// skip songlist_songs table since it has compound key
	if($(this).parent().parent().parent().parent().attr('id') != 'songlist_songs') {
		var row = $(this).parent().parent();
		if(confirm('Delete ' + row.data("entity") + '?')) {
		    $.post("remove_item.php",
		    {entity: row.data("entity"), id: row.data("id")},
		    // on success remove table row
		    function(data, status) {
		    	row.remove();
		    });
		}
	}
});

// clicking on songlist_song table - remove button
$("#songlist_songs td span.glyphicon-remove").on("click", function() {
	var songlist_id = $(this).data('songlist-id');
	var song_id = $(this).data('song-id');
	var row = $(this).parent().parent();

	if(confirm('Remove song from list?')) {
	    $.post("remove_item.php",
	    {entity: 'songlist_song', songlist_id: songlist_id, id: song_id},
	    // on success remove table row
	    function(data, status) {
	    	row.remove();
	    });
	}
});

// cancel button
$('button.btn-cancel').on('click', function() {
	window.history.back();
});

// select for adding songs to songlist
$('#select-song').on("change", function() {
	data = {song: $(this).val(), songlist: $(this).data("songlist")};
	$.post(
		'add_2_songlist.php', 
		data, 
		// on success
		function() {
			location.reload(true);
	});
});

// Manage devices - activate /deactivate device (and toggle between minus and tick)
$(".item-activate").on("click", function() {
	// alert($(this).parent().parent().data("id"));
	var glyphSpan = $(this);
	var device_id = glyphSpan.parent().parent().data("id");
	var active = glyphSpan.hasClass("glyphicon-ok") ? 0 : 1;

    $.post("activate_item.php",
	    {id: device_id, act: active},
	    // on success change glyph
	    function() {
	    	if(glyphSpan.hasClass("glyphicon-ok")) {
	    		glyphSpan.removeClass("glyphicon-ok").addClass("glyphicon-minus");
	    	} else {
	    		glyphSpan.removeClass("glyphicon-minus").addClass("glyphicon-ok");    		
	    	}  	
    });
});

// Manage devices - Assign patches - update bank/patch select elements on change
$(".device-selector, .bank-selector").on("change", function() {
	// get row id (to return new select options)
	var rowId = $(this).parent().parent().parent().attr("id");
	// get device ID (we need it in either case)
	var deviceId = $(this).parent().parent().parent().find("select.device-selector").val();
	// get changed element ID/type (device or bank selector)
	el = $(this).attr("id");
	// if clicked on device selector - targets are bank selector and patch selector
	// we need device ID to get banks (in addition ajax script selects 1st bank to get its patches )
	if(el.indexOf("device") > -1) {
		dataOut = {element: "device-selector", device: deviceId};
	// if clicked on bank selector - target is patch selector
	// (we need device ID, bank0 and bank32 values to select patches)
	} else {
		bank_0_32_value = ($(this).val()).split("-");
		dataOut = {element: "bank-selector", device: deviceId, value0: bank_0_32_value[0], value32: bank_0_32_value[1]};
	}
	// who is the caller (index.php or patch_assign.php)
	var ajxScript = rowId.indexOf("custom") > -1 ? 'manage/get_patch_options.php' : 'get_patch_options.php';
	
	// shoot ajax
	$.post(
		ajxScript, 
		dataOut, 
		// on success
		function(dataStr) {
			data = JSON.parse(dataStr);
			// when device was changed, change bank selector  first
			if(dataOut.element == 'device-selector') {
				var sel = $("#" + rowId).find(".bank-selector");
				sel.empty();
				$.each(data['bank'], function(key,value) {
					sel.append($("<option></option>")
					.attr("value", key).text(value));
				});
			}
			// change patc selector
			var sel = $("#" + rowId).find(".patch-selector");
			sel.empty();
			$.each(data['patch'], function(key,value) {
				sel.append($("<option></option>")
				.attr("value", key).text(value));
			});
		});
});

// Manage - Assign patches: Save (update/add) patch
$(".song-patch-save").on("click", function() {
	var rowId = $(this).parent().parent().attr("id");
	var song = $(this).parent().parent().data("song-id");
	var song_patch = $(this).parent().parent().data("song-patch-id");
	var device = $(this).parent().parent().parent().find("select.device-selector").val();
	var channel = $(this).parent().parent().parent().find("select.channel-selector").val();
	var bank_0_32_value = $(this).parent().parent().parent().find("select.bank-selector").val().split("-");
	var bank_0 = bank_0_32_value[0];
	var bank_32 = bank_0_32_value[1];
	var patch = $(this).parent().parent().parent().find("select.patch-selector").val();
	var volume = $(this).parent().parent().parent().find("input.volume-input").val();
	var expression = $(this).parent().parent().parent().find("input.expression-input").val();
	var custom_cc = $(this).parent().parent().next().find(".txt-midi-custom").val();
	// console.log(custom_cc);
	dataOut = {"song": song, "action": "save", "songpatch": song_patch, "device": device, "channel": channel, "bank0": bank_0, "bank32": bank_32, "patch": patch};
	// add volume and expression if valid values
	if(typeof volume != 'undefined' && volume >= 0 && volume <= 127) {
		dataOut.volume = volume;
	}
	if(typeof expression != 'undefined' && expression >= 0 && expression <= 127) {
		dataOut.expression = expression;
	}
	if(typeof custom_cc != 'undefined' && custom_cc.length > 0) {
		dataOut.custom_cc = custom_cc;
	}

        console.log(dataOut);

	$.post("song_patch_update.php",
		dataOut,
		// on success 
		function() {
			// nothing to do
		});
});

// Manage - Assign patches: Remove patch
$(".song-patch-remove").on("click", function() {
	var rowId = $(this).parent().parent().attr("id");
	var song_patch_id = $(this).parent().parent().data("song-patch-id");
	dataOut = {"songpatch": song_patch_id, "action": "remove"};
	$.post("song_patch_update.php",
		dataOut,
		// on success 
		function() {
			// nothing to do
	});
});

// Manage - Assign patches: Test patch
$("button.song-patch-test").on("click", function() {
    var deviceId = $("#device").val();
    var channel = $("#channel").val();
    var bank_0_32_value = $(this).parent().parent().find("select.bank-selector").val().split("-");
    var patch = $("#patch").val();
    var bank_0 = bank_0_32_value[0];
    var bank_32 = bank_0_32_value[1];
    var volume = $(this).parent().parent().parent().find("input.volume-input").val();
    var expression = $(this).parent().parent().parent().find("input.expression-input").val();
    var custom_cc = $(this).parent().parent().next().find(".txt-midi-custom").val();
    dataOut = {"device": deviceId, "channel": channel, "bank0": bank_0, "bank32": bank_32, "patch": patch};
    // add volume and expression if valid values
    if(typeof volume != 'undefined' && volume >= 0 && volume <= 127) {
        dataOut.volume = volume;
    }
    if(typeof expression != 'undefined' && expression >= 0 && expression <= 127) {
        dataOut.expression = expression;
    }
    if(typeof custom_cc != 'undefined' && custom_cc.length > 0) {
        dataOut.custom_cc = custom_cc;
    }
    console.log(dataOut);
    $.post("song_patch_test.php",
        dataOut,
        // on success
        function() {
            // nothing to do
    });
});

// some global vars
// needed only on the index page (performance)
if($(".tbl-songlist").length) {	
	var currentRowY = $(".tbl-songlist").find("tr.song-data-active").position().top;
}
var rowHeight = 70;
var rowOffset = 2.86 * rowHeight;

// Select next song
$("#next-song-marker").on("click", function() {
	// get current song
	var tr_active_song = $(".tbl-songlist").find("tr.song-data-active");
	var tr_next_song = tr_active_song.closest('tr').next("tr.song-data");
	var active_song_play_char = tr_active_song.find(".play-char-active");
	var next_song_play_char = tr_next_song.find(".play-char");
	// do not go past last table row
	if(tr_next_song.length > 0) {
		tr_active_song.removeClass("song-data-active").addClass("song-data");
		tr_next_song.removeClass("song-data").addClass("song-data-active");
		active_song_play_char.removeClass("play-char-active").addClass("play-char");
		next_song_play_char.removeClass("play-char").addClass("play-char-active");
		var next_song_id = tr_next_song.data("song-id");
		currentRowY = currentRowY + rowHeight;
		$("#songs-div").scrollTop(currentRowY - rowOffset);
		$.post("send_midi_data.php",
			{song_id: next_song_id},
			// on success
			function() {
				// do whatever
			});
	}
});

//Select prev song
$("#prev-song-marker").on("click", function() {
	// get current song
	var tr_active_song = $(".tbl-songlist").find("tr.song-data-active");
	var tr_prev_song = tr_active_song.closest('tr').prev("tr.song-data");
	var active_song_play_char = tr_active_song.find(".play-char-active");
	var prev_song_play_char = tr_prev_song.find(".play-char");
	// do not go past last table row
	if(tr_prev_song.length > 0) {
		tr_active_song.removeClass("song-data-active").addClass("song-data");
		tr_prev_song.removeClass("song-data").addClass("song-data-active");
		active_song_play_char.removeClass("play-char-active").addClass("play-char");
		prev_song_play_char.removeClass("play-char").addClass("play-char-active");
		var prev_song_id = tr_prev_song.data("song-id");
		currentRowY = currentRowY - rowHeight;
		$("#songs-div").scrollTop(currentRowY - rowOffset);
		$.post("send_midi_data.php",
			{song_id: prev_song_id},
			// on success
			function() {
				// do whatever
			});
	}
});

// Resend data for current song
$("#resend-marker").on("click", function() {
	var active_song_id = $(".tbl-songlist").find("tr.song-data-active").data("song-id");
	$.post("send_midi_data.php",
		{song_id: active_song_id},
		// on success
		function() {
			// do whatever
		});
});

// Panic button - sends Midi reset command
$("#btn-panic").on("click", function() {
	// send Midi controller 123 on all channels to all devices
	$.post("all_notes_off.php");
});

// performance mode - send custom patch to selected device on selected channel
$("#send-custom").on("click", function() {
	var container = $(this).parent().parent();
	var deviceId = container.find(".device-selector").val();
	var channel = container.find(".channel-selector").val();
	var bank_0_32 = container.find(".bank-selector").val().split("-");
	var patch = container.find(".patch-selector").val();
	var data = {device_id: deviceId, channel: channel, bank0: bank_0_32[0], bank32: bank_0_32[1], program: patch};
	// console.log(data);
	if(dbg == 1) {
		$(".debug-text").html(format_debug_info(data));
	}
	
	$.post("send_custom_midi_data.php",
		data,
		// on success
		function() {
			// do whatever
		});
});

// performance mode - show devices
$("#btn-show-devices").on("click", function() {
	$(".device-list").toggle();
	$.post(
		"list_devices.php",
		function(data) {
			$(".device-list-text").html(data);
		}).fail(function() {
		    console.log("Error");
		});
});

// performance mode - system stuff
// $(".system-btn").on("click", function() {
$(".control-marker img").on("click", function() {
	// which button was pressed
	var data = {cmd: $(this).attr("id")};
	$.post("system-commands.php",
		data,
		// on success
		function() {
			// do whatever
	});
});

// format_debug info
function format_debug_info(dbgObj) {
	var dbgString = '';
	$.each(dbgObj, function(key, val) {
		dbgString += key + ": <strong>" + val + "</strong><br>";
	});
	return dbgString;
}

//close debug window
$(".close-window").on("click", function() {
	$(this).parent().parent().hide();
	// $.post("set_debug.php",	{debug: 0});
});

