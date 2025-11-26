let sessionStarted = false;
$(function () {
    clock = $('.clock').FlipClock(0, {
        clockFace: 'MinuteCounter',
        countdown: false,
        autoStart: false,
        callbacks: {
            start: function () {
            },
            stop: function () {
            }
        }
    });
    $('input[class=radioICheck]').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

    if (window.phpScrolLogic == 1) {
        $(window).scroll(function () {
            if ($(".navbar").offset().top > 5) {
                $('.headerlogo').attr('src', window.phpHeaderLogo2); //change src
                $("#mainNav").fadeIn("slow", function () {
                    $("#mainNav").css("opacity", "0.95");
                });
            } else {
                $('.headerlogo').attr('src', window.phpHeaderLogo1);
                $("#mainNav").fadeIn("slow", function () {
                    $("#mainNav").css("opacity", "");
                });
            }
        });
    }

    let isNavigating = false;

    const assessmentForm = $("#assessmentForm").show();

    assessmentForm.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        //transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        forceMoveForward: true,
        enableContentCache: false,
        titleTemplate: "#title#",
        onInit: function (event, currentIndex) {
            sessionStarted = true
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (isNavigating) {
                isNavigating = false; // reset flag immediately
                return true;
            }

            if (currentIndex > newIndex) {
                return false; // Not allows to go back
            }

            //assessmentForm.validate().settings.ignore = ":disabled,:hidden";

            $('.loader').show();

            if (!assessmentForm.valid()) {
                $('.loader').hide();
                return false;
            }

            return saveAnswers(assessmentForm, currentIndex, newIndex);
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            assessmentForm.find(".steps li").eq(priorIndex).addClass("done").removeClass("error");
            $('.loader').hide();
            window.scrollTo(0, 0);
            return true;
        },
        onFinishing: function (event, currentIndex) {
            //assessmentForm.validate().settings.ignore = ":disabled,:hidden";

            return assessmentForm.valid();
        },
        onFinished: function (event, currentIndex) {
            if (saveAnswers(assessmentForm, currentIndex)) {
                end();
                sessionStarted = false;
            }
        }
    }).validate({
        errorElement: "span",
        ignore: ":disabled,:hidden",
        errorPlacement: function (error, element) {
            if (element.closest('.radio-group').length) {
                element.closest('.radio-group').after(error)
            } else {
                element.after(error);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass)
                .closest('.form-group').addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass)
                .closest('.form-group').removeClass(errorClass).addClass(validClass);
        },
        rules: {
            gcse_maths_grade_actual: {
                required: function (element) {
                    return $("#gcse_maths_grade_predicted").val() == ''
                }
            }
        }
    });
});

function _ajaxRequest(action, data, success, error) {
    $.ajax({
        type: 'POST',
        url: 'do.php',
        data: $.extend({
            _action: 'ajax_initial_assessment',
            subaction: action,
        }, data),
        async: false,
        dataType: 'json',
        success: function (data, textStatus, xhr) {
            if (typeof success == 'function') {
                success(data, textStatus, xhr);
            }
        },
        error: function (data, textStatus, xhr) {
            console.log(data, textStatus)
            if (typeof error == 'function') {
                error(data, textStatus, xhr);
            }
        }
    });
}

function doAjax(action, data) {
    data._action = 'ajax_initial_assessment';
    data.subaction = action;
    encodeUrl = function (obj, prefix = '') {
        const pairs = [];
        for (const key in obj) {
            if (obj.hasOwnProperty(key)) {
                const value = obj[key];
                const encodedKey = prefix
                    ? `${prefix}[${encodeURIComponent(key)}]`
                    : encodeURIComponent(key);

                if (value !== null && typeof value === 'object') {
                    pairs.push(encodeUrl(value, encodedKey));
                } else {
                    pairs.push(`${encodedKey}=${encodeURIComponent(value)}`);
                }
            }
        }
        return pairs.join('&').replace(/%20/g, '+');
    }

    var xhr = ajaxRequest('do.php', encodeUrl(data));

    if (xhr && xhr.status >= 200 && xhr.status < 300) {
        try {
            return JSON.parse(xhr.responseText); // manually parse
        } catch (e) {
            console.error("Failed to parse JSON:", e);
            return null;
        }
    } else {
        console.error('Request failed.');
        return null;
    }
}

function saveAnswers(form, currentIndex, newIndex) {
    const currentStep = form.find(`.body:eq(${currentIndex})`);
    const newStep = newIndex ? form.find(`.body:eq(${newIndex})`) : null;
    const currentLevel = currentStep.find('.stage-level.active');
    const level = currentLevel.data('level');
    const stage = currentStep.data('stage');
    const data = {
        _action: 'ajax_initial_assessment',
        subaction: 'saveQuestions',
        as_id: asid,
        stage: stage,
        level: level,
        answers: {},
    };

    currentLevel.find('.question').each(function () {
        const question = $(this);
        const isOptional = question.data('optional');
        const qid = question.data('question');
        let answer = null;

        if (isOptional == 1) {
            question.find('input:checked').each(function () {
                answer = this.value;
            });
        } else {
            const input = question.find('input')[0];
            answer = input ? input.value : null;
        }

        if (answer !== null) {
            data.answers[qid] = answer;
        }
    });

    var response = doAjax('saveQuestions', data);
    if (response && response.success) {
        if (newIndex) {
            const nextLevel = response.level;
            if (nextLevel) {
                $(".stage-level").removeClass('active');
                $("#level_" + nextLevel).addClass('active');
            }
        }
        return true;
    }

    return false;
}

function updateReport(obj) {
    let report = $('.report');

    report.find('.prg-stage').html(obj.stage || '');
    report.find('.prg-statement').html(obj.statement || '');

    report.find('.start-date').html(obj.start_date || '--');
    report.find('.start-time').html(obj.start_time || '--');
    report.find('.end-date').html(obj.end_date || '--');
    report.find('.end-time').html(obj.end_time || '--');
    report.find('.time-diff').html(obj.time_diff || '--');

    var bd = '';

    if (obj.scores) {
        $.each(obj.scores, function (key, item) {
            bd += `<td>
                        <p>${item.topic}</p>
                        <p>${item.given}/${item.total} (${item.percent}%)</p>
                    </td>`;
        });
    }
    report.find(".result-breakdown").html(bd);

    report.show();
}

function toPdf() {
    var element = document.getElementById('pdf');
    console.log(element)

    var opt = {
        margin: 1,
        filename: 'myfile.pdf',
        image: {type: 'jpeg', quality: 1},
        html2canvas: {scale: 1},
        jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
    };

    html2pdf().set(opt).from(element).save();
}


function start() {
    _ajaxRequest('startSession', {
        'tr_id': trid,
        'as_id': asid,
        'subject': subject,
    }, function (response) {
        if (response && response.id) {
            asid = response.id;
            if (asid) {
                $('#landingPage').hide();
                $('#contentForm').show();
                clock.start();
            }
        } else {
            console.error('Failed to start session');
        }
    })
}

function end() {
    _ajaxRequest('endSession', {'as_id': asid}, function (response) {
        if (response && response.end_time) {
            clock.stop();
            updateReport(response);
            $('#contentForm').hide();
            $('#finished').show();
        } else {
            console.error('Failed to end assessment');
        }
    })
}

window.addEventListener("beforeunload", function (event) {
    if (sessionStarted) {
        let msg = "Are you sure you want to leave? Your session will be lost.";
        event.preventDefault();
        event.returnValue = msg;
        return msg;
    }
});


