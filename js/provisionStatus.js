(function ($, Drupal, once) {
  Drupal.behaviors.wcamProvisionStatus = {
    attach: function (context, settings) {
      once('wcamProvisionStatus', 'html', context).forEach(function () {
        // Get the process idle animation.
        let $animation = $('#process-idle-animation');
        $animation.show();  // Show the spinner.
        // Get the aid and initial provision status from the HTML.
        let $rows = $('.wcam--table--row');
        $rows.each(function () {
          let $row = $(this);
          let aid = $row.find('.wcam--row--account-id').text().trim();
          let initialStatus = $row.find('#provision-status--aid-' + aid).text().trim();
          // If the initial provision status is 'ongoing', start checking the provision status.
          if (initialStatus === 'ongoing' || initialStatus === 'unknown') {
            let intervalId = setInterval(function () {
              $.get('/wisski-cloud-account-manager/provision-status/' + aid, function (data) {
                // Update the provision status on the page.
                let $status = $('#provision-status--aid-' + aid);
                $status.text(data.status);
                // If the provision status is not 'ongoing', stop the process idle animation.
                if (data.status !== 'ongoing') {
                  if (data.status !== 'unknown') {
                    $row.find('#provision-status--row--aid-' + aid).text(data.status);
                    $animation.hide();  // Hide the spinner.
                    clearInterval(intervalId);
                    location.reload();
                  }
                }
              });
            }, 3000);
          }
        });
      });
    }
  };
})(jQuery, Drupal, once);
