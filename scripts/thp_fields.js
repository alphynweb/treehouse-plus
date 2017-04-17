jQuery(document).ready(function ($) {
    // Fields js will go here
    $('#thp_badges_save_submit').on('click', function (event) {
        if(!confirm('Are you sure you want to save your badges to your filesystem? This may take a couple of minutes')){
            event.preventDefault();
            return false;
        }
        return true;
    });
});


