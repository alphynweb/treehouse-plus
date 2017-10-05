'use strict';

jQuery(document).ready(function ($) {

    var badgeSize;

    function getBadgeList() {
        console.log("getBadgeList fired");

        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action': 'thp_get_badge_list',
                    'size': badgeSize
                },
                dataType: 'json',
                cache: false
            }).done(function (badgeListData) {
                resolve(badgeListData);
            });
        });
    }

    function saveBadges(badgeInfo) {
        console.log("saveBadges fired");
        console.log(badgeInfo);
        var badgeList = badgeInfo['badges_to_save'];
        console.log("Saving " + badgeList.length + " badges");
        var numberToSave = badgeList.length;
        var noSaved = 0;
        var badgePercent = 0;
        var totalBadgesNo = badgeInfo['total_badges'];
        $('.thp-badges-size').html(badgeSize + "px");
        $('#badgeFileList #progress').show();

        // Loop through badges
        return new Promise(function (resolve, reject) {

            badgeList.slice(0, numberToSave).forEach(function (badge) {
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        'action': 'thp_save_badge',
                        'icon_url': badge['icon_url'],
                        'size': badgeSize
                    },
                    cache: false
                }).done(function (response) {
                    // Update progress bar etc.
                    noSaved++;
                    console.log(response);
                    $('#badgeFileList #noSaved').html(noSaved + " badges saved");
                    // Work out percentage to increase progress bar by
                    badgePercent = badgePercent + 100 / numberToSave;
                    console.log(badgePercent);
                    $('#badgeFileList #progress #bar').css('width', badgePercent + "%");
                    // Update badges saved message
                    $('.thp-saved-badges-message').html("You currently have <span class='thp-saved-badges-no'></span> out of <span class='thp-total-badges-no'>" + totalBadgesNo + "</span> badges saved to your filesystem at a size of <span class='thp-badges-size'>" + badgeSize + "px</span>");
                    $('.thp-saved-badges-message .thp-saved-badges-no').html(totalBadgesNo - (badgeList.length - noSaved));
                    // Finish
                    if (noSaved === numberToSave) {
                        resolve();
                    }
                }).fail(function (e) {});
            });
        });
    }

    function displaySuccess() {
        $('#thp-badge-save-complete-section').css('display', 'block');
        alert("Success");
    }

    $('#thp_badges_save_submit').on('click', function (event) {
        event.preventDefault();
        badgeSize = $('#thp_badge_save_sizes').val();
        getBadgeList().then(saveBadges).then(displaySuccess).catch(function (e) {
            console.log(e);
        });
    });
});