let requestRunning = false,
    wasOffline = false,
    reloadTmr = null,
    reloadTimeSeconds = statusReloadTime,
    navNewsNumberEl = null,
    navBoardNumberEl = null,
    navFileNumberEl = null,
    navKnowledgeNumberEl = null,
    navUserNumberEl = null,
    navHomeEl = null,
    navChatEl = null,
    lastId = null,
    currentUser = null,
    chatMsg = null,
    msgList = null,
    lastChatMessageId = 0,
    isChatPage = (currentPage === 'chat'),
    isLoggedIn = false;

function htmlDecode(value) {
    return $('<div/>').html(value).text();
}

function md5(d) {
    return rstr2hex(binl2rstr(binl_md5(rstr2binl(d), 8 * d.length)))
}

function rstr2hex(d) {
    for (var _, m = "0123456789ABCDEF", f = "", r = 0; r < d.length; r++) _ = d.charCodeAt(r), f += m.charAt(_ >>> 4 & 15) + m.charAt(15 & _);
    return f
}

function rstr2binl(d) {
    for (var _ = Array(d.length >> 2), m = 0; m < _.length; m++) _[m] = 0;
    for (m = 0; m < 8 * d.length; m += 8) _[m >> 5] |= (255 & d.charCodeAt(m / 8)) << m % 32;
    return _
}

function binl2rstr(d) {
    for (var _ = "", m = 0; m < 32 * d.length; m += 8) _ += String.fromCharCode(d[m >> 5] >>> m % 32 & 255);
    return _
}

function binl_md5(d, _) {
    d[_ >> 5] |= 128 << _ % 32, d[14 + (_ + 64 >>> 9 << 4)] = _;
    for (var m = 1732584193, f = -271733879, r = -1732584194, i = 271733878, n = 0; n < d.length; n += 16) {
        var h = m, t = f, g = r, e = i;
        f = md5_ii(f = md5_ii(f = md5_ii(f = md5_ii(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_ff(f = md5_ff(f = md5_ff(f = md5_ff(f, r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 0], 7, -680876936), f, r, d[n + 1], 12, -389564586), m, f, d[n + 2], 17, 606105819), i, m, d[n + 3], 22, -1044525330), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 4], 7, -176418897), f, r, d[n + 5], 12, 1200080426), m, f, d[n + 6], 17, -1473231341), i, m, d[n + 7], 22, -45705983), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 8], 7, 1770035416), f, r, d[n + 9], 12, -1958414417), m, f, d[n + 10], 17, -42063), i, m, d[n + 11], 22, -1990404162), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 12], 7, 1804603682), f, r, d[n + 13], 12, -40341101), m, f, d[n + 14], 17, -1502002290), i, m, d[n + 15], 22, 1236535329), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 1], 5, -165796510), f, r, d[n + 6], 9, -1069501632), m, f, d[n + 11], 14, 643717713), i, m, d[n + 0], 20, -373897302), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 5], 5, -701558691), f, r, d[n + 10], 9, 38016083), m, f, d[n + 15], 14, -660478335), i, m, d[n + 4], 20, -405537848), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 9], 5, 568446438), f, r, d[n + 14], 9, -1019803690), m, f, d[n + 3], 14, -187363961), i, m, d[n + 8], 20, 1163531501), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 13], 5, -1444681467), f, r, d[n + 2], 9, -51403784), m, f, d[n + 7], 14, 1735328473), i, m, d[n + 12], 20, -1926607734), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 5], 4, -378558), f, r, d[n + 8], 11, -2022574463), m, f, d[n + 11], 16, 1839030562), i, m, d[n + 14], 23, -35309556), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 1], 4, -1530992060), f, r, d[n + 4], 11, 1272893353), m, f, d[n + 7], 16, -155497632), i, m, d[n + 10], 23, -1094730640), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 13], 4, 681279174), f, r, d[n + 0], 11, -358537222), m, f, d[n + 3], 16, -722521979), i, m, d[n + 6], 23, 76029189), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 9], 4, -640364487), f, r, d[n + 12], 11, -421815835), m, f, d[n + 15], 16, 530742520), i, m, d[n + 2], 23, -995338651), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 0], 6, -198630844), f, r, d[n + 7], 10, 1126891415), m, f, d[n + 14], 15, -1416354905), i, m, d[n + 5], 21, -57434055), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 12], 6, 1700485571), f, r, d[n + 3], 10, -1894986606), m, f, d[n + 10], 15, -1051523), i, m, d[n + 1], 21, -2054922799), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 8], 6, 1873313359), f, r, d[n + 15], 10, -30611744), m, f, d[n + 6], 15, -1560198380), i, m, d[n + 13], 21, 1309151649), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 4], 6, -145523070), f, r, d[n + 11], 10, -1120210379), m, f, d[n + 2], 15, 718787259), i, m, d[n + 9], 21, -343485551), m = safe_add(m, h), f = safe_add(f, t), r = safe_add(r, g), i = safe_add(i, e)
    }
    return Array(m, f, r, i)
}

function md5_cmn(d, _, m, f, r, i) {
    return safe_add(bit_rol(safe_add(safe_add(_, d), safe_add(f, i)), r), m)
}

function md5_ff(d, _, m, f, r, i, n) {
    return md5_cmn(_ & m | ~_ & f, d, _, r, i, n)
}

function md5_gg(d, _, m, f, r, i, n) {
    return md5_cmn(_ & f | m & ~f, d, _, r, i, n)
}

function md5_hh(d, _, m, f, r, i, n) {
    return md5_cmn(_ ^ m ^ f, d, _, r, i, n)
}

function md5_ii(d, _, m, f, r, i, n) {
    return md5_cmn(m ^ (_ | ~f), d, _, r, i, n)
}

function safe_add(d, _) {
    var m = (65535 & d) + (65535 & _);
    return (d >> 16) + (_ >> 16) + (m >> 16) << 16 | 65535 & m
}

function bit_rol(d, _) {
    return d << _ | d >>> 32 - _
}

function pullLiveStatus() {
    let chatActive = 0;
    if (isChatPage) {
        chatActive = 1;
    }
    if (!requestRunning) {
        requestRunning = true;
        $.post({
            url: "/ajax/status",
            data: {since: tsGenerated, cmsgid: lastChatMessageId, chat: chatActive}, // TODO: Since news, since ...
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

                    currentUser = data.user.userName;

                    // Test mode
                    if (data.isTestMode) {
                        if (!body.hasClass('mode_test')) {
                            body.addClass('mode_test');
                        }
                    } else {
                        body.removeClass('mode_test');
                    }

                    if (data.onlineUsers.length > 0) {
                        const userEls = $('.nfp_user');
                        for (let j = 0; j < userEls.length; j++) {
                            let el = $(userEls[j]);
                            el = $($(el)[0]);
                            if (el) {
                                const usrNam = el.data('user');
                                let indicator = el.find('.nfp_indicator');
                                if (indicator) {
                                    indicator = $(indicator[0]);
                                    if (data.onlineUsers.indexOf(usrNam) === -1) {
                                        indicator.hide();
                                    } else {
                                        indicator.show();
                                    }
                                }
                            }
                        }
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
                        if (data.updateCounts.home) {
                            navHomeEl.html(data.updateCounts.home).addClass('visible');
                        } else {
                            navHomeEl.removeClass('visible');
                        }
                        if (data.updateCounts.chat) {
                            navChatEl.html(data.updateCounts.chat).addClass('visible');
                        } else {
                            navChatEl.removeClass('visible');
                        }
                    }

                    //console.log(data.chatMessages);
                    if (data.chatMessages && isChatPage && msgList) {
                        data.chatMessages.forEach((m) => {
                            const msgEl = $('#' + m.i);
                            if (!msgEl[0]) {
                                addChatMessageToList(m.i, m.t, m.m, m.u);
                            }
                            lastChatMessageId = m.t;
                        });
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

function uniqueId(length = 16) {
    return parseInt(Math.ceil(Math.random() * Date.now()).toPrecision(length).toString().replace(".", ""))
}

function encodeHTMLEntities(text) {
    return $("<textarea/>").text(text).html();
}

function sendChatMessage(message, messageId, listElEl) {
    $.post({
        // TODO: Limit length of message! Both here and in input field
        url: "/ajax/chat",
        data: {id: messageId, lastId: lastId, msg: message},
        success: function (data, text) {
            //if (data.success) {
            if (listElEl) {
                listElEl.find('.loader').hide();
            }
            //}
        },
        error: function (request, status, error, data) { // TODO: Return 200, but ERROR in the status ! We don't get the message contents on error :(
            console.log(data);
            const el = $('#' + status.id);
            if (el) {
                el.addClass('error');
            }
        }
    });
}

function addChatMessageToList(messageId, ts, msg, usr, loader = false) {
    const tsObj = moment.unix(ts);
    const relTime = tsObj.fromNow();
    const listElEl = msgList.append(
        '<li id="' + messageId + '" class="cm text-muted clearfix" data-ts="' + ts + '">' +
        '<div class="clearfix">' + (loader ? '<img class="loader" src="/img/loader.svg">' : '') +
        '<em data-user="' + usr + '" class="nfp_user"><a href="/users/' + usr + '">' + usr + '</a> <i style="display:none" class="nfp_indicator fa fa-check-circle text-success" title="' + userIsOnlineText + '"></i></em>' +
        '<strong class="small nfp_ts" data-ts="' + tsObj.format('X') + '">' +
        relTime + '</strong> ' +
        '</div><div class="msg">' +
        msg +
        '</div></li>'
    );

    const cb = $('#chatBox');
    cb.scrollTop(cb[0].scrollHeight);

    return $(listElEl[0]);
}

function submitChatMessage() {
    const chatMessage = chatMsg.val().trim();
    if (chatMessage.length === 0) {
        return false;
    }
    chatMsg.val('');
    chatMsg.focus();
    moment.locale("de"); // TODO: Configurable
    const messageId = md5('@@' + uniqueId(32));
    const listElEl = addChatMessageToList(messageId, moment().format('X'), encodeHTMLEntities(chatMessage), currentUser, true);
    sendChatMessage(chatMessage, messageId, listElEl);
    return true;
}

$(document).ready(function () {

    let bodyEl = $('body');

    // Get elements
    navNewsNumberEl = $('#nfpUpdateCount_news');
    navBoardNumberEl = $('#nfpUpdateCount_board');
    navFileNumberEl = $('#nfpUpdateCount_files');
    navKnowledgeNumberEl = $('#nfpUpdateCount_knowledge');
    navUserNumberEl = $('#nfpUpdateCount_user');
    navHomeEl = $('#nfpUpdateCount_home');
    navChatEl = $('#nfpUpdateCount_chat');
    isLoggedIn = bodyEl.hasClass('loggedin');

    // Update live status
    setInterval(pullLiveStatus, statusReloadTime * 1000);

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

    // Chat page
    if (bodyEl.hasClass('page-chat')) {
        const chatBt = $('#chatSendButton');
        chatMsg = $('#chatMessage');
        msgList = $('#chatMsgList');
        let chatBox = $('#chatBox');

        if (!isLoggedIn) {
            chatMsg.prop('disabled', 'disabled');
            chatBt.prop('disabled', 'disabled');
        }

        // Auto-convert relative date every second
        setInterval(function () {
            const allEls = $('#chat .nfp_ts');
            for (let i = 0; i < allEls.length; i++) {
                el = $(allEls[i]);
                const m = moment.unix(el.data('ts'));
                el.html(m.fromNow());
            }
        }, 5000); // TODO: Make configurable

        // Handle key up event
        chatMsg.on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                // Do something
                submitChatMessage();
            }
        });

        // Handle click on send button
        chatBt.on('click', function (e) {
            submitChatMessage();
        });
    }

    pullLiveStatus();

    // BOARD
    if (bodyEl.hasClass('page-board')) {
        const cardTitle = $('#cardTitle'),
            cardMessage = $('#cardMessage'),
            cardCreateBt = $('#cardCreateButton');

        let formEnabled = false,
            titleVal = null,
            messageVal = null;

        function checkEnableBoardForm() {
            formEnabled = (titleVal && messageVal && titleVal.length && messageVal.length);
            if (formEnabled) {
                cardCreateBt.prop('disabled', null);
            } else {
                cardCreateBt.prop('disabled', 'disabled');
            }
        }

        cardTitle.on('keyup', function (e) {
            titleVal = $(this).val().trim();
            checkEnableBoardForm();
        });
        cardMessage.on('keyup', function (e) {
            messageVal = $(this).val().trim();
            checkEnableBoardForm();
        });

        cardCreateBt.on('click', function (e) {
            e.stopPropagation();
            e.preventDefault();

            console.log('QAAAA');
            // TODO: Add AJAX loader stuff!
            cardTitle.val('');
            cardMessage.val('');

            $.post({
                // TODO: Limit length of message! Both here and in input field
                url: "/ajax/board",
                data: {
                    title: titleVal,
                    message: messageVal
                },
                success: function (data, text) {

                },
                error: function (request, status, error, data) { // TODO: Return 200, but ERROR in the status ! We don't get the message contents on error :(
                    console.log(data);
                    const el = $('#' + status.id);
                    if (el) {
                        el.addClass('error');
                    }
                }
            });

            titleVal = '';
            messageVal = '';

        });

        const myModal = document.getElementById('boardDeleteModal');
        console.log(myModal);
        //var myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', function (e) {
            const bt = e.relatedTarget,
                postId = bt.dataset['postid'],
                title = bt.dataset['title'],
                login = bt.dataset['login'];

            const msg = i18n_post_delete_message.replace("${user}", login).replace("${title}", title);
            console.log(msg);
            $('#boardDialogMsg').html(msg);
        });


    }

    // FILES
    if (bodyEl.hasClass('page-files')) {
        $('a.hiddenInfoSwitch').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const el = $(e.currentTarget);
            $('#' + $(el[0]).data('el')).toggle();
        });
    }

    // Popovers everywhere enabled
    /*
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
    new bootstrap.Popover(document.querySelector('.popover-dismiss'), {
        trigger: 'focus'
    });
    */

});
