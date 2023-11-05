let requestRunning = false,
    wasOffline = false,
    reloadTmr = null,
    reloadTimeSeconds = statusReloadTime,
    navNewsNumberEl = null,
    navBoardNumberEl = null,
    navFileNumberEl = null,
    navKnowledgeNumberEl = null,
    navUserNumberEl = null;

function htmlDecode(value) {
    return $('<div/>').html(value).text();
}

function pullLiveStatus() {
    if (!requestRunning) {
        requestRunning = true;
        $.ajax({
            url: "/status.php?since=" + tsGenerated, // TODO: Since news, since ...
            success: function (data, text) {
                if (reloadTmr) {
                    clearInterval(reloadTmr);
                    reloadTimeSeconds = statusReloadTime;
                }
                requestRunning = false;
                wasOffline = false;
                $.unblockUI();
                if (data.success) {
                    const body = $('body');

                    // Test mode
                    if (data.isTestMode) {
                        if (!body.hasClass('mode_test')) {
                            body.addClass('mode_test');
                        }
                    } else {
                        body.removeClass('mode_test');
                    }

                    // Updates since the user last visited
                    if (data.updateCounts) {
                        if (data.updateCounts.news) {
                            navNewsNumberEl.html(data.updateCounts.news).addClass('visible');
                        } else {
                            navNewsNumberEl.removeClass('visible');
                        }
                        if (data.updateCounts.board) {
                            navBoardNumberEl.html(data.updateCounts.board).addClass('visible');
                        } else {
                            navBoardNumberEl.removeClass('visible');
                        }
                        if (data.updateCounts.files) {
                            navFileNumberEl.html(data.updateCounts.files).addClass('visible');
                        } else {
                            navFileNumberEl.removeClass('visible');
                        }
                        if (data.updateCounts.knowledge) {
                            navKnowledgeNumberEl.html(data.updateCounts.knowledge).addClass('visible');
                        } else {
                            navKnowledgeNumberEl.removeClass('visible');
                        }
                        if (data.updateCounts.users) {
                            navUserNumberEl.html(data.updateCounts.news).addClass('visible');
                        } else {
                            navUserNumberEl.removeClass('visible');
                        }
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
                        message: eval('`' + connectErrorMessage + '`'), //'<h1>⚠ Offline!</h1>Automatischer Verbindungsversuch in <span id="reconnectTimer">'+statusReloadTime+'</span> Sekunden',
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

    // Get elements
    navNewsNumberEl = $('#nfpUpdateCount_news');
    navBoardNumberEl = $('#nfpUpdateCount_board');
    navFileNumberEl = $('#nfpUpdateCount_files');
    navKnowledgeNumberEl = $('#nfpUpdateCount_knowledge');
    navUserNumberEl = $('#nfpUpdateCount_user');

    // Update live status
    setInterval(pullLiveStatus, statusReloadTime * 1000);
    pullLiveStatus();

    // Convert relative dates
    moment.locale("de");
    const tsElements = $('.nfp_ts');
    if (tsElements.length) {
        for (let i = 0; i < tsElements.length; i++) {
            const el = $(tsElements[i]);
            const ts = el.data('ts');
            if (ts) {
                const relativeText = moment.unix(ts).fromNow();
                el.html(relativeText);
            }
        }
    }

});


// mode_test
