  jQuery(document).ready(function () {
            alert(\'yes\');
            var posX = "";
        var posY = "";
        $(document).mousemove(function (event) {
            posX = event.pageX;
            posY = event.pageY;
        });
        ZeroClipboard.setDefaults({moviePath: BASE_URL + \'/themes/cisco/js/zeroclipboard/ZeroClipboard.swf\'});
        var clip = new ZeroClipboard($(\'#nip_variables_id li a\'));   
        clip.on("complete", function (client, args, event) {
            posX += 30;
            posY -= 20;
            var spn = "<span class=\'notify212\'  style=\'position:absolute; top:" + posY + "px; left:" + posX + "px; padding:5px 10px; background:#F6FF00; z-index:99999999;\'>Copied to clipboard</span>";
            $(\'body\').append(spn);
            $(".notify212").fadeOut(2500, function () {
                $(this).remove();
            });
        });
		});