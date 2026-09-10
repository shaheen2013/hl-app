var PrintComments = function () {
    sessionStorage.removeItem('comments-selected');

    var hoteId;
    var commentsSelected = {
        lang: null,
        comments: [],
    };

    var init = function (id) {
        cleanSessionOnLogOut();
        getFromSession();
        commentsSelected.lang = sessionStorage.getItem('translate') || null;
        buttonEvents();
    };

    var buttonEvents = function () {
        $('.select-comment').on('click', function () {
            var $self = $(this);
            var satisfactionId = $self.data('satisfaction-id');

            $self.toggleClass('active');
            if ($self.hasClass('active')) {
                setToPrint(satisfactionId);
            } else {
                $self.blur();
                unsetToPrint(satisfactionId);
            }

            saveToSession();
        });

        $('#print-selected').on('click', function (e) {
            e.preventDefault();

            if (!printSelected()) {
                alert('Nothing to print!');
            }

            $("#print-selected").removeClass("active");
            e.stopPropagation()
        });
        
        $('.translate-selected').on('click', function (e) {
            e.preventDefault();

            commentsSelected.lang = $(this).data('value');

            for (var i = 0; i < commentsSelected.comments.length; i ++) {
                if (!commentsSelected.lang) {
                    commentsSelected.comments[i].comment = commentsSelected.comments[i].original;
                } else {
                    translateSessionItem(i);
                }
            }

            saveToSession();
        });
    };

    var translateSessionItem = function (item) {
        var translateInfo = {
            current: commentsSelected.comments[item].original,
            text: ""
        };

        TranslateComments.getDetectLanguage(translateInfo, function(translateInfo, lang) {
            TranslateComments.translateText(lang, commentsSelected.lang, translateInfo, function (translateInfo) {
                commentsSelected.comments[item].translated = translateInfo.text;
                $('#satisfaction-id-' + commentsSelected.comments[item].id).find('span.original-language').hide();
                $('#satisfaction-id-' + commentsSelected.comments[item].id).find('span.translated').text(translateInfo.text);
                $('#satisfaction-id-' + commentsSelected.comments[item].id).find('span.translated').show();

                saveToSession();
            });
        });
    };

    var setToPrint = function (satisfactionId) {
        var $row = $('#satisfaction-id-' + satisfactionId);

        var satisfactionObject = {
            id: satisfactionId,
            author: $row.find('td.satisfaction-author').text().replace(/[\n|\r|\t]/gi, ''),
            comment: (commentsSelected.lang) ? $row.find('span.translated').text() : $row.find('span.original-language').text(),
            translated: (commentsSelected.lang) ? $row.find('span.translated').text() : '',
            score:  $row.find('span.survey-score').text() ,
            room:  $row.find('span.survey-room').text() ,
            date:  $row.find('span.survey-answer-date').text() ,
            original: $row.find('span.original-language').text()
        };

        if (searchComment(satisfactionObject.id) == - 1) {
            commentsSelected.comments.push(satisfactionObject);
        }
    };

    var unsetToPrint = function (satisfactionId) {
        var index = searchComment(satisfactionId);

        if (index >= 0) {
            commentsSelected.comments.splice(index, 1);
        }
    };

    var saveToSession = function () {
        try {
            sessionStorage.setItem('comments-selected', JSON.stringify(commentsSelected));
        } catch (e) {
            console.warn(e.message);
        }
    };

    var getFromSession = function () {
        try {
            commentsSelected = JSON.parse(sessionStorage.getItem('comments-selected')) || commentsSelected;
        } catch (e) {
            console.warn(e.message);
        }
    };

    var searchComment = function (satisfactionId) {
        for (var i = 0; i < commentsSelected.comments.length; i ++) {
            if (commentsSelected.comments[i].id === satisfactionId) {
                return i;
            }
        }

        return -1;
    };

    var printSelected = function () {
        var content = $('<div></div>').append($('#user-wrapper').clone()).html();
        var commentType = commentsSelected.lang ? 'translated' : 'comment';
        var comment;

        for (var i = 0; i < commentsSelected.comments.length; i ++) {
            comment = commentsSelected.comments[i][commentType] || commentsSelected.comments[i].comment;
            content += '<div style="border: 2px solid grey; padding:1rem 2rem; border-radius: 3px; margin-bottom: 1rem">' +
                '<div style="width: calc(100% - 60px); display: inline-block">' +
                '<p style="margin:0;font-size: 10pt;font-family: Lato,Arial,Helvetica,sans-serif;"><span style="font-weight: 700;color: rgba(0,0,0,.6);text-transform: uppercase;">Author: </span> ' + commentsSelected.comments[i].author + '</p><br/>' +
                '<p style="margin:0;font-size: 10pt;font-family: Lato,Arial,Helvetica,sans-serif;"><span style="font-weight: 700;color: rgba(0,0,0,.6);text-transform: uppercase;">Date: </span> ' + commentsSelected.comments[i].date + '</p><br/>' +
                '<p style="margin:0;font-size: 10pt;font-family: Lato,Arial,Helvetica,sans-serif;"><span style="font-weight: 700;color: rgba(0,0,0,.6);text-transform: uppercase;">Room: </span> ' + commentsSelected.comments[i].room + '</p><br/>' +
                '<p style="margin:0;font-size: 10pt;font-family: Lato,Arial,Helvetica,sans-serif;"><span style="font-weight: 700;color: rgba(0,0,0,.6);text-transform: uppercase;">Comment: </span> ' + comment + '</p>' +
                '</div>' +
                '<div style="width: 60px; display: inline-block;vertical-align: top; text-align: center">' +
                '<p style="margin:0;">Score</p><br/>' +
                '<p style="margin:0;font-size: 50px;font-family: Lato,Arial,Helvetica,sans-serif;color: #715aff;">' +
                commentsSelected.comments[i].score + '</p>' +
                '</div>' +
                '</div>';
        }

        if (!content) {
            return false;
        }

        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.getElementsByTagName('body')[0].appendChild(iframe);

        var ifr = iframe.contentWindow || iframe.contentDocument.document || iframe.contentDocument;

        ifr.document.open();
        ifr.document.write(content);
        ifr.document.close();

        var head = ifr.document.getElementsByTagName("head")[0];
        var link = ifr.document.createElement("link");
        link.setAttribute("rel", "stylesheet");
        link.setAttribute("type", "text/css");
        link.setAttribute("media", "print");
        link.setAttribute("href", "/public/css/print-comments.css");
        head.appendChild(link);

        var result = iframe.contentWindow.document.execCommand('print', false, null);

        if (!result) {
            iframe.contentWindow.print();
        }

        setTimeout(function () {
            iframe.parentNode.removeChild(iframe);
        }, 1000);

        return true;
    };

    var cleanSessionOnLogOut = function () {
        $('a.logout-option').on('click', function () {
            sessionStorage.removeItem('comments-selected');
        });
    };

    return {
        init: init,
    };

}();
