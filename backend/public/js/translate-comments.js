var TranslateComments = function () {

    var targetLanguage = '';
    var apiKey = '';
    var apiEndPoint = 'https://translation.googleapis.com/language/translate/v2';
    var apiDetect = apiEndPoint + '/detect?key=' + apiKey;
    var apiTranslate = apiEndPoint + '?key=' + apiKey;

    var init = function () {
        targetLanguage = sessionStorage.getItem('translate') || $('#translate-target-language').val();
        selectorsEvent();
    };

    var translateText = function (source, target, translateInfo, callback) {
        var currentText = (typeof translateInfo.current == 'object') ? translateInfo.current.text() : translateInfo.current;
        var api = apiTranslate;
        api += '&source=' + source;
        api += '&target=' + target;
        api += '&q=' + encodeURIComponent(currentText);

        $.get(api).done(function (data) {
            try {
                var translated = data.data.translations[0].translatedText || '';
                if (translated) {
                    translateInfo.text = translated;
                    callback(translateInfo);
                }
            } catch (e) {
                console.warn(e.message);
            }
        });
    };

    var getDetectLanguage = function (translateInfo, callback) {
        var api = apiDetect;
        var currentText = (typeof translateInfo.current == 'object') ? translateInfo.current.text() : translateInfo.current;
        api += '&q=' + encodeURIComponent(currentText);

        $.get(api).done(function (data) {
            try {
                var language = data.data.detections[0][0].language || '';

                if (language) {
                    callback(translateInfo, language);
                }
            } catch (e) {
                console.warn(e.message);
            }
        });
    };

    var selectorsEvent = function () {
        if (targetLanguage) {
            $('#translate-target-language option').each(function () {
                if (this.value == targetLanguage) {
                    $(this).prop('selected', 'selected');
                }
            });
        }

        $('#translate-target-language').on('change', function () {
            targetLanguage = this.value;
            sessionStorage.setItem('translate', targetLanguage);
        });
    };

    var autoTranslate = function () {
        var translateInfo;

        if (targetLanguage) {
            $('td.satisfaction-list-comment').each(function() {
                translateInfo = {
                    current: $(this).find('span.original-language'),
                    target: $(this).find('span.translated'),
                    text: ""
                };

                getDetectLanguage(translateInfo, function(translateInfo, lang) {
                    translateText(lang, targetLanguage, translateInfo, function (translateInfo) {
                        translateInfo.current.hide();
                        translateInfo.target.text(translateInfo.text);
                        translateInfo.target.show();
                    });
                });
            });
        } else {
            $('td.satisfaction-list-comment > span.translated').hide();
            $('td.satisfaction-list-comment > span.original-language').show();
        }
    };

    return {
        init: init,
        getDetectLanguage: getDetectLanguage,
        translateText: translateText
    };

}();

$(document).ready(function() {
    TranslateComments.init();
});
