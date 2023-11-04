let requestRunning = false,
    wasOffline = false,
    reloadTmr = null,
    reloadTimeSeconds = statusReloadTime;

function htmlDecode(value){
    return $('<div/>').html(value).text();
}

function pullLiveStatus() {
    if (!requestRunning) {
        requestRunning = true;
        $.ajax({
            url: "/status.php?since=" + tsGenerated,
            success: function (data, text) {
                if (reloadTmr) {
                    clearInterval(reloadTmr);
                    reloadTimeSeconds = statusReloadTime;
                }
                requestRunning = false;
                wasOffline = false;
                $.unblockUI();
                if (data.success) {
                    if (data.isTestMode) {
                        $('body').addClass('mode_test');
                    } else {
                        $('body').removeClass('mode_test');
                    }
                } else {
                    console.error("Requesting status failed!"); // TODO: Show error message
                }
            },
            error: function (request, status, error) {
                if (reloadTmr) {
                    clearInterval(reloadTmr);
                    reloadTimeSeconds = statusReloadTime;
                }
                requestRunning = false;
                if (!wasOffline) {
                    wasOffline = true;
                    $.blockUI({
                        message: eval('`'+connectErrorMessage+'`') , //'<h1>⚠ Offline!</h1>Automatischer Verbindungsversuch in <span id="reconnectTimer">'+statusReloadTime+'</span> Sekunden',
                        css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            borderRadius: '10px',
                            opacity: .5,
                            color: '#fff'
                        }
                    });
                }
                reloadTmr = setInterval(function () {
                    reloadTimeSeconds--;
                    if (reloadTimeSeconds < 0) {
                        reloadTimeSeconds = 0;
                    }
                    $('#reconnectTimer').html(reloadTimeSeconds);
                }, 1000);

            }
        });
    }
}

$(document).ready(function () {
    setInterval(pullLiveStatus, 10000);
    pullLiveStatus();
});


// mode_test
