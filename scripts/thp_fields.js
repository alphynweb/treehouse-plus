jQuery(document).ready(function ($) {
    // TODO - establish how many badges there are to be saved.


    // Fields js will go here
    $('#thp_badges_save_submit').on('click', function (event) {
        //alert($('#thp_badge_save_sizes').val());
        event.preventDefault();
        var badgeSize = $('#thp_badge_save_sizes').val();
        if (!confirm('Are you sure you want to save your badges to your filesystem? This may take a couple of minutes')) {
            return false;
        }

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'thp_get_badge_list',
                'size': badgeSize
            },
            dataType: 'json',
            cache: false,
            success: function (response) {
                //alert("Ajax tester successful. Saving badges");
                saveBadges(response);
                //deleteBadges(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus);
                alert("Error: " + errorThrown);
            }
        });
    });

    // TODO - not needed?
//    function deleteBadges(badgeList) {
//        alert("Deleting badges");
//        $.ajax({
//            type: 'POST',
//            url: ajaxurl,
//            data: {
//                'action': 'thp_delete_badges'
//            },
//            dataType: 'json',
//            cache: false,
//            success: function (response) {
//                //alert("Ajax tester successful. Saving badges");
//                saveBadges(response);
//            },
//            error: function (XMLHttpRequest, textStatus, errorThrown) {
//                alert("Status: " + textStatus);
//                alert("Error: " + errorThrown);
//            }
//        });
//    }

    function saveBadges(badgeList) {
        // Loop through badges and make ajax call for each one to save.
        //alert("Saving badges");
        console.log("Saving badges" + badgeList);
        var noSaved = 0;
        var badgeSize = $('#thp_badge_save_sizes').val();
        badgeList.slice(0, 5).forEach(function (badge) {
            //badgeList.forEach(function (badge, index) {
            // Make ajax request to save the badge.
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action': 'thp_save_badge',
                    'icon_url': badge['icon_url'],
                    'size': badgeSize
                },
                cache: false,
                success: function (response) {
                    noSaved++;
                    console.log(response);
                    $('#badgeFileList #progress #noSaved').html(noSaved + " badges saved");
                    // Test
                    if (noSaved === 5) {
                        alert("Done!");
                        saveUserData();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("Badge Status: " + textStatus);
                    alert("Badge Error: " + errorThrown);
                }
            });
        });
    }

    function saveUserData() {
        alert("Done! Saving user data");
        var badgeSize = $('#thp_badge_save_sizes').val();
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'thp_save_user_data',
                'size': badgeSize
            },
            cache: false,
            success: function (response) {
                alert(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Badge Status: " + textStatus);
                alert("Badge Error: " + errorThrown);
            }
        });
    }



//        if (!confirm('Are you sure you want to save your badges to your filesystem? This may take a couple of minutes')) {
//            event.preventDefault();
//            return false;
//        }
//        return true;
    //});
});


