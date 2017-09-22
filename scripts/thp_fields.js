jQuery(document).ready(function ($) {

    $('#thp_badges_save_submit').on('click', function (event) {
        event.preventDefault();
        var badgeSize = $('#thp_badge_save_sizes').val();

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
        var badgeList = response['badges_to_save'];
        console.log("Saving " + badgeList.length + " badges");
        var noSaved = 0;
        var badgePercent = 0;
        var badgeSize = $('#thp_badge_save_sizes').val();
        var numberToSave = badgeList.length;
        var totalBadgesNo = response['total_badges'];
        $('.thp-badges-size').html(badgeSize + "px");
        $('#badgeFileList #progress').show();
        badgeList.slice(0, numberToSave).forEach(function (badge) {
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
                    $('.thp-saved-badges-message').html("You currently have <span class='thp-saved-badges-no'></span> out of <span class='thp-total-badges-no'>" + totalBadgesNo + "</span> badges saved to your filesystem at a size of <span class='thp-badges-size'>" + badgeSize + "px</span>");
                    $('.thp-saved-badges-message .thp-saved-badges-no').html(totalBadgesNo - (badgeList.length - noSaved));
                    // Test
                    if (noSaved === numberToSave) {
                        //$('#thp-badge-save-section').css('display', 'none');
                        $('#thp-badge-save-complete-section').css('display', 'block');
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("Badge Status: " + textStatus);
                    alert("Badge Error: " + errorThrown);
                }
            });
        });
    }
});


