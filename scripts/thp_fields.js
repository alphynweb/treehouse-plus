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
                //saveBadges(response);
                //deleteBadges(response);
                console.log(response);
                saveBadges(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus);
                alert("Error: " + errorThrown);
            }
        });
    });

    function saveBadges(response) {
        // Loop through badges and make ajax call for each one to save.
        //alert("Saving badges");
        var badgeList = response['badges_to_save'];
        console.log("Saving " + badgeList.length + " badges");
        var noSaved = 0;
        var badgePercent = 0;
        var badgeSize = $('#thp_badge_save_sizes').val();
        //var numberToSave = badgeList.length;
        var numberToSave = 5;
        var totalBadgesNo = response['total_badges'];
        $('#thp-badges-size').html(badgeSize + "px");
        badgeList.slice(0, numberToSave).forEach(function (badge) {
            //badgeList.forEach(function (badge) {
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
                    $('#badgeFileList #noSaved').html(noSaved + " badges saved");
                    // Update progress bar
                    // Work out percentage to increase by
                    badgePercent = badgePercent + (100 / numberToSave);
                    console.log(badgePercent);
                    $('#badgeFileList #progress #bar').css('width', badgePercent + "%");
                    // Update badges saved message
                    $('#thp-saved-badges-message #thp-saved-badges-no').html(totalBadgesNo - (badgeList.length - noSaved));
                    // Test
                    if (noSaved === numberToSave) {
                        alert("Done!");
                        //saveBadgeSize();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("Badge Status: " + textStatus);
                    alert("Badge Error: " + errorThrown);
                }
            });
        });
    }

    // TODO - No need for this
//    function saveBadgeSize() {
//        alert("Done! Saving badge size field");
//        var badgeSize = $('#thp_badge_save_sizes').val();
//        $.ajax({
//            type: 'POST',
//            url: ajaxurl,
//            data: {
//                'action': 'thp_save_badge_size',
//                'size': badgeSize
//            },
//            cache: false,
//            success: function (response) {
//                alert(response);
//                $('#thp-badges-size').html(badgeSize + "px");
//            },
//            error: function (XMLHttpRequest, textStatus, errorThrown) {
//                alert("Badge Status: " + textStatus);
//                alert("Badge Error: " + errorThrown);
//            }
//        });
//    }
});


