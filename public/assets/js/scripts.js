let changeEvent = new Event('change');

$(function () {
    let lang = window.Laravel.lang;

    /*Header dropdown*/
    $('.header-dropdown__title').click(function () {
        let dropdownWrapper = $(this).closest('.header-dropdown');
        if (!dropdownWrapper.hasClass('opened')) {
            $('.header-dropdown.opened').removeClass('opened');
            dropdownWrapper.addClass('opened');
        } else {
            dropdownWrapper.removeClass('opened');
        }
    });
    $(document).click(function (event) {
        //Close profile context menu if anything except itself or profile link is clicked
        if (!$(event.target).closest('.header-dropdown').length) {
            if ($('.header-dropdown').hasClass('opened')) {
                $('.header-dropdown').removeClass('opened');
            }
        }
    });
    /**/

    /*Mobile overlay close button*/
    $('.mob-overlay-btn').click(function () {
        let id = $(this).data('target');
        $('#' + id).addClass('opened');
        bodyUnscrollable();
    });
    /**/

    $('.mob-overlay__close').click(function (e) {
        e.preventDefault();
        $(this).closest('.mob-overlay').removeClass('opened');
        bodyScrollable();
    });

    /*Fancybox behaviour*/
    $('[data-fancybox]').click(function (e) {
        if ($(this).attr('href').charAt(0) === '#') {
            e.preventDefault();
            let target = $(this).attr('href');
            let additionalOptions = $(this).data('options');

            if ($.fancybox.getInstance()) {
                $.fancybox.getInstance('close');
                setTimeout(function () {
                    $.fancybox.open({
                        src: target,
                        touch: false,
                        ...additionalOptions
                    });
                }, 380);
            } else {
                $.fancybox.open({
                    src: target,
                    touch: false,
                    ...additionalOptions
                });
            }
        }
    });
    /**/

    /*Tabs*/
    $('.tabs-links a').click(function () {
        let li = $(this).parent(),
            wrapper = $(this).closest('.tabs'),
            content = wrapper.find('.tabs-contents>div').eq(li.index());
        wrapper.find('.tabs-contents>div.active').removeClass('active');
        content.addClass('active');
        li.addClass('active').siblings('.active').removeClass('active');

        if ($(window).width() < 1025) {
            let dropdown = $(this).closest('.mobile-dropdown'),
                dropdownTitle = $('.mobile-dropdown__title', dropdown);
            dropdown.removeClass('opened');

            if (dropdownTitle.hasClass('dynamic')) {
                dropdownTitle.html($(this).html());
            }
        }
    });
    /**/

    /*Dropzone init*/
    document.querySelectorAll('.dropzone-multiple').forEach(function (item) {
        let url = item.dataset.url,
            maxFiles = Number(item.dataset.maxfiles),
            maxSize = Number(item.dataset.maxsize),
            required = item.dataset.required,
            acceptedFiles = item.dataset.acceptedfiles;

        let createDropzone = new CustomDropzone(item, url, maxFiles, maxSize, acceptedFiles, acceptedFiles === 'image/*', required);
    });

    /*Avatar dropzone init*/
    document.querySelectorAll('.dropzone-avatar').forEach(function (item) {
        let id = '#' + item.id,
            url = item.dataset.url,
            maxSize = Number(item.dataset.maxsize),
            acceptedFiles = item.dataset.acceptedfiles;
        let avatar = new AvatarDropzone(id, url, maxSize, acceptedFiles);
    });

    /*Mobile dropdown*/
    $('.mobile-dropdown__title').click(function () {
        let dropdownWrapper = $(this).closest('.mobile-dropdown');
        if (!dropdownWrapper.hasClass('opened')) {
            $('.mobile-dropdown.opened').removeClass('opened');
            dropdownWrapper.addClass('opened');
        } else {
            dropdownWrapper.removeClass('opened');
        }
    });
    $(document).click(function (event) {
        //Close profile context menu if anything except itself or profile link is clicked
        if (!$(event.target).closest('.mobile-dropdown').length) {
            if ($('.mobile-dropdown').hasClass('opened')) {
                $('.mobile-dropdown').removeClass('opened');
            }
        }
    });

    /*Range slider init*/
    document.querySelectorAll('.single-range-slider').forEach(function (item) {
        let slider = item.parentElement,
            min = Number(item.min),
            max = Number(item.max),
            step = Number(item.step),
            value = Number(item.value),
            decimals = item.dataset.decimals ? Number(item.dataset.decimals) : 0;

        noUiSlider.create(slider, {
            start: value,
            connect: 'lower',
            tooltips: true,
            step: step,
            range: {
                'min': min,
                'max': max
            },
            format: {
                to: function (value) {
                    return Number(value).toFixed(decimals);
                },
                from: function (value) {
                    return Number(value).toFixed(decimals);
                }
            }
        });

        slider.noUiSlider.on('change', function () {
            item.value = slider.noUiSlider.get();
            item.dispatchEvent(changeEvent)
        });
    });
    /**/

    /*Duplicate items btn*/
    document.querySelectorAll('[data-duplicate]').forEach(function (el) {
        let copyEl = document.querySelector('#' + el.dataset.duplicate),
            duplicatesContainer = el.closest('.pull-up').previousElementSibling;

        if (!copyEl) return;

        let cloneTpl = copyEl.cloneNode(true);
        cloneTpl.value = '';

        el.addEventListener('click', function (e) {
            e.preventDefault();

            let clone = cloneTpl.cloneNode(true);

            clone.removeAttribute('required');
            clone.removeAttribute('disabled');
            clone.removeAttribute('id');

            let removeBtn = document.createElement('div');
            removeBtn.className = 'btn-icon small icon-close';

            let newItem = document.createElement('div');
            newItem.className = 'form-group';
            newItem.innerHTML = `<div class="input-addon">

                                <div class="addon"></div>
                            </div>
      `;

            let addon = newItem.querySelector('.addon');

            addon.before(clone);
            addon.append(removeBtn);

            removeBtn.addEventListener('click', function () {
                newItem.remove();
                el.style.display = 'inline-flex';
            });

            duplicatesContainer.append(newItem);

            if (clone.classList.contains('selectize-regular')) {
                if (!clone.hasAttribute('multiple')) {
                    $(`[name="${clone.name}"]`).selectize(selectizeSingleOptions);
                } else {
                    $(`[name="${clone.name}"]`).selectize(selectizeMultipleOptions);
                }
            }

            if (clone.hasAttribute('data-method')) {
                let newAjaxSelect = new ajaxSelect($(`[name="${clone.name}"]`).not('.selectized'));
                newAjaxSelect.clear();
            }

            if (el.dataset.maxcount && duplicatesContainer.children.length >= el.dataset.maxcount) {
                hideEl(el);
            }
        });
    });
    /**/

    selectizeRegularInit();

    /*init Tinymce*/
    if (document.querySelector('.tinymce-here')) {
        TinyMceInit('.tinymce-here');
    }
    if (document.querySelector('.tinymce-text-here')) {
        TinyMceInit('.tinymce-text-here', true);
    }
    /**/

    /* Datepicker init */
    $('.custom-datepicker').each(function () {
        let picker = $(this).datepicker({
            language: lang,
            autoClose: true
        });

        if ($(this).val()) {
            picker.data('datepicker').selectDate(moment($(this).val(), 'DD.MM.YYYY').toDate());
        }
    });
    /**/

    document.querySelectorAll('[data-toggle]').forEach(function (el) {
        let targetSelectors = el.dataset.toggle.split(',');
        el.addEventListener('change', function (e) {
            targetSelectors.forEach(function (selector) {
                toggleEl(document.querySelector('#' + selector));
                document.querySelector('#' + selector).querySelectorAll('[required]').forEach(function (required) {
                    required.toggleAttribute('disabled')
                });
            });
        });
    });

    document.querySelectorAll('.topic.spoiler').forEach(function (el) {
        let topicTitle = el.querySelector('.topic__header');

        topicTitle.addEventListener('click', function () {
            el.classList.toggle('collapsed');
        });
    });

    document.querySelectorAll('.removable-items').forEach(function (collection) {
        htmlCollectionToArray(collection.children).forEach(function (item) {
            let removeBtn = item.querySelector('.icon-close');

            if (removeBtn) {
                removeBtn.addEventListener('click', function () {
                    item.remove();
                });
            }
        });
    });

    document.querySelectorAll('.dz-stored').forEach(function (el) {
        let removeBtn = el.querySelector('.link.red'),
            recoverBtn = el.querySelector('.link.green'),
            input = el.querySelector('input');

        removeBtn.addEventListener('click', function () {
            input.setAttribute('disabled', 'disabled');
            hideEl(removeBtn);
            showEl(recoverBtn);
            checkRequiredDropzone($(el).parent().parent())
        });

        recoverBtn.addEventListener('click', function () {
            input.removeAttribute('disabled');
            hideEl(recoverBtn);
            showEl(removeBtn);
            checkRequiredDropzone($(el).parent().parent())
        })
    });

    document.querySelectorAll('.accordion').forEach(function (el) {
        let parent = el.closest('.accordion-group'),
            isDependent = !parent.classList.contains('independent');

        let accHeader = el.querySelector('.accordion__header'),
            panel = accHeader.nextElementSibling;

        accHeader.addEventListener('click', function () {
            if (isDependent && !el.classList.contains('active') && parent.querySelector('.accordion.active')) {
                let activeAcc = parent.querySelector('.accordion.active'),
                    activeAccPanel = activeAcc.querySelector('.accordion__header').nextElementSibling;

                activeAcc.classList.remove('active');
                activeAccPanel.style.maxHeight = null;
                activeAccPanel.style.opacity = 0;

                el.classList.add('active');
                panel.style.maxHeight = panel.scrollHeight + "px";
                panel.style.opacity = 1;
            } else {
                el.classList.toggle('active');
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    panel.style.opacity = 0;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                    panel.style.opacity = 1;
                }
            }
        });
    });

    document.querySelectorAll('.extendable').forEach(function (extendableBlock) {
        let btn = extendableBlock.nextElementSibling,
            title = btn.title,
            alternativeTitle = btn.dataset.alternativetitle,
            maxHeight = btn.dataset.maxheight;

        if (extendableBlock.offsetHeight > Number(maxHeight)) {
            showEl(btn);
            extendableBlock.style.maxHeight = maxHeight + 'px';
            extendableBlock.classList.add('extended');

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (!extendableBlock.classList.contains('opened')) {
                    btn.innerHTML = alternativeTitle;
                    btn.title = alternativeTitle;
                    extendableBlock.classList.add('opened');
                } else {
                    btn.innerHTML = title;
                    btn.title = title;
                    extendableBlock.classList.remove('opened');
                }
            });
        }
    });

    document.querySelectorAll('[data-enable]').forEach(function (el) {
        el.addEventListener('change', function () {
            let target = document.querySelector(el.dataset.enable);
            if (el.checked) {
                target.removeAttribute('disabled')
            } else {
                target.setAttribute('disabled', 'disabled')
            }
        });
    })

    if (window.innerWidth < 1024) {
        document.querySelectorAll('[data-toggle-title]').forEach(function (el) {
            let toggleBtn = document.createElement('div');
            toggleBtn.className = 'toggle-link';
            toggleBtn.innerHTML = el.getAttribute('data-toggle-title');

            toggleBtn.addEventListener('click', function () {
                toggleEl(el);
            });

            el.before(toggleBtn);
        });
    }

    $.bvi({
        bvi_target: ".bvi-open",
        bvi_theme: "white",
        bvi_font: "arial",
        bvi_font_size: 16,
        bvi_letter_spacing: "normal",
        bvi_line_height: "normal",
        bvi_images: true,
        bvi_reload: false,
        bvi_fixed: false,
        bvi_tts: false,
        bvi_flash_iframe: true,
        bvi_hide: false
    });

    $('.plain-text table').each(function () {
        $(this).wrap('<div class="scroll-x"><div class="scroll-x__inner"></div></div>')
    });

    if (document.querySelector('.notifications [data-unread]')) {
        document.querySelector('.notifications [data-unread]').addEventListener('click', function (e) {
            let btn = e.target,
                parent = btn.closest('.notifications'),
                dataService = new DataService(),
                ids = [];

            if (!btn.classList.contains('updated')) {
                parent.querySelectorAll('[data-id]').forEach(function (el) {
                    ids.push(Number(el.dataset.id));
                });

                dataService.updateUnreadMessages(ids).then(function (response) {
                    btn.dataset.unread = response;
                    btn.classList.add('updated');
                }).catch(function (status) {
                    console.log(`Error ${status}`);
                });
            }
        });
    }
});

//Fixed header
$(window).scroll(function () {
    if ($(window).scrollTop() > $('.main-wrapper').offset().top) {
        $('.header').addClass('fixed');
    } else {
        $('.header').removeClass('fixed');
    }
});

//Get scrollbar width
function getScrollbarWidth() {
    var outer = document.createElement("div");
    outer.style.visibility = "hidden";
    outer.style.width = "100px";
    outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps

    document.body.appendChild(outer);

    var widthNoScroll = outer.offsetWidth;
    // force scrollbars
    outer.style.overflow = "scroll";

    // add innerdiv
    var inner = document.createElement("div");
    inner.style.width = "100%";
    outer.appendChild(inner);

    var widthWithScroll = inner.offsetWidth;

    // remove divs
    outer.parentNode.removeChild(outer);

    return widthNoScroll - widthWithScroll;
}

//Make body unscrollable
function bodyUnscrollable() {
    if (!$('body').hasClass('no-scroll')) {
        let scrollTop = $(window).scrollTop();
        //Detect all fixed elements on the page
        let find = $('*').filter(function () {
            return $(this).css('position') == 'fixed';
        });
        $('.main-wrapper').css('margin-top', -scrollTop);
        $('body').addClass('no-scroll').css('margin-right', getScrollbarWidth());
        find.css('margin-right', getScrollbarWidth());
    }
}

//Make body scrollable
function bodyScrollable() {
    if ($('body').hasClass('no-scroll')) {
        let scrollTop = $('.main-wrapper').css('margin-top').slice(0, -2);
        //Detect all fixed elements on the page
        let find = $('*').filter(function () {
            return $(this).css('position') == 'fixed';
        });
        $('body').removeClass('no-scroll').css('margin-right', 0);
        find.css('margin-right', 0);
        $('.main-wrapper').css('margin-top', 0);
        $(window).scrollTop(-scrollTop);
    }
}

//Scroll el to bottom
function scrollToBottom(el) {
    let container = document.querySelector(el);
    container.scrollTop = container.scrollHeight;
}

//Render author's stats chart
function renderAuthorStats(container) {
    const url = container.dataset.url;

    am4core.ready(function () {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        let chart = am4core.create(container, am4charts.XYChart);
        const lang = window.Laravel.lang;

        //Set chart lang
        if (lang === 'ru') {
            chart.language.locale = am4lang_ru_RU;
        } else if (lang === 'kk') {
            chart.language.locale = am4lang_ko_KR;
        }

        // Datepicker
        let defaultDatepickerOptions = {
            language: lang,
            autoClose: true,
            maxDate: new Date(),
            onSelect: function (response) {
                container.classList.add('preloader');
                if (response) {
                    updateData(pickerFrom.el.value, pickerTo.el.value);
                } else {
                    setDefaultData();
                }
            }
        };

        let pickerFrom = $('[name="dateFrom"]').datepicker(defaultDatepickerOptions).data('datepicker'),
            pickerTo = $('[name="dateTo"]').datepicker(defaultDatepickerOptions).data('datepicker'),
            clearBtn = document.querySelector('#clear');

        let dateFrom = pickerFrom.el.value ? moment(pickerFrom.el.value, 'DD.MM.YYYY').format('YYYY-MM-DD') : '',
            dateTo = pickerTo.el.value ? moment(pickerTo.el.value, 'DD.MM.YYYY').format('YYYY-MM-DD') : '';

        // Add initial data
        $.ajax({
            url: url + '?date_from=' + dateFrom + '&date_to=' + dateTo,
            success: function (response) {
                const color1 = response.color1,
                    color2 = response.color2;

                chart.data = response.data;

                // Create axes
                let dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.renderer.minGridDistance = 50;

                let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

                // Create series
                let series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value1";
                series.stroke = color1;
                series.tooltipText = "{value1}";
                setSeriesDefaultOptions(series);

                // Create series
                let series2 = chart.series.push(new am4charts.LineSeries());
                series2.dataFields.valueY = "value2";
                series2.stroke = color2;
                series2.tooltipText = "{value2}";
                setSeriesDefaultOptions(series2);

                // Add cursor
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.xAxis = dateAxis;
            }
        });

        clearBtn.addEventListener('click', function (e) {
            e.preventDefault();
            pickerFrom.clear();
            pickerTo.clear();
        });

        function updateData(from, to) {
            $.ajax({
                url: url + '?date_from=' + from + '&date_to=' + to,
                success: function (response) {
                    chart.data = response.data;
                    container.classList.remove('preloader');
                }
            });
        }

        function setDefaultData() {
            $.ajax({
                url: url,
                success: function (response) {
                    chart.data = response.data;
                    container.classList.remove('preloader');
                }
            })
        }

        function setSeriesDefaultOptions(series) {
            series.dataFields.dateX = "date";
            series.strokeWidth = 3;
            series.minBulletDistance = 10;
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.background.cornerRadius = 20;
            series.tooltip.background.strokeOpacity = 0;
            series.tooltip.label.minWidth = 40;
            series.tooltip.label.minHeight = 40;
            series.tooltip.label.textAlign = "middle";
            series.tooltip.label.textValign = "middle";
            series.tooltip.getFillFromObject = false;
            series.tooltip.background.fill = am4core.color("#f5f5f5");
            series.tooltip.autoTextColor = false;
            series.tooltip.label.fill = am4core.color("#333");
        }
    });
}

//ToArray
function toArray(str) {
    return Array.isArray(str) ? str : Array(str)
}

function htmlCollectionToArray(collection) {
    return Array.prototype.slice.call(collection)
}

function hideAllChildren(el) {
    Array.prototype.slice.call(el.children).forEach(function (item) {
        item.style.display = 'none';
    });
}

function showEl(el) {
    el.style.display = 'block';
}

function hideEl(el) {
    el.style.display = 'none';
}

function toggleEl(el) {
    if (el.style.display === "none" || window.getComputedStyle(el, null).getPropertyValue("display") === 'none') {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}

function returnCheckedRadio(radioCollection) {
    return Array.prototype.slice.call(radioCollection).find(function (item) {
        return item.checked === true
    })
}

let AvatarDropzone = function (id, url, maxSize, acceptedFiles) {
    const parent = document.querySelector(id),
        lang = window.Laravel.lang,
        previewsContainer = document.querySelector(id).querySelector('.previews-container'),
        defaultPreviewUrl = parent.querySelector('.avatar-preview').dataset.defaultsrc,
        previewTemplate = parent.querySelector('.avatar-preview-template'),
        avatarPreview = parent.querySelector('.avatar-preview'),
        avatarPick = parent.querySelector('.avatar-pick'),
        avatarPath = parent.querySelector('.avatar-path');
    let localeOptions = null;

    /*Check if lang is not eng*/
    if (lang === 'ru') {
        localeOptions = dropzoneRU;
    } else if (lang === 'kk') {
        localeOptions = dropzoneKK
    }

    let newDropzone = new Dropzone(id, {
        url: url,
        previewTemplate: previewTemplate.innerHTML,
        maxFilesize: maxSize,
        acceptedFiles: acceptedFiles,
        timeout: 180000,
        lastFile: null,
        previewsContainer: previewsContainer,
        clickable: [avatarPick, avatarPreview],
        init: function () {
            this.on('success', function (file, response) {
                avatarPreview.src = file.dataURL;
                avatarPath.value = response.location;
            });

            this.on('removedfile', function () {
                avatarPreview.src = defaultPreviewUrl;
                avatarPath.value = '';
            });

            this.on('addedfile', function (file) {
                if (this.lastFile) {
                    this.removeFile(this.lastFile)
                }
                this.lastFile = file;
            });
        },
        ...localeOptions
    });

    if (previewsContainer.innerHTML !== '') {
        let item = previewsContainer.querySelector('.dz-preview'),
            filename = item.querySelector('.dz-filename') ? item.querySelector('.dz-filename').textContent : '',
            size = item.querySelector('.dz-size strong') ? item.querySelector('.dz-size strong').textContent : '';
        let mockFile = {name: filename, size: Number(size) * 1000};

        previewsContainer.innerHTML = '';
        newDropzone.displayExistingFile(mockFile, avatarPath.value);
    }
};

let CustomDropzone = function (el, url, maxFiles, maxSize, acceptedFiles, showThumbnails = false, required = false) {
    const previewsContainer = el.querySelector('.previews-container'),
        clickable = el.querySelector('.dropzone-default__link'),
        filesPath = el.querySelector('input'),
        lang = window.Laravel.lang;
    let filenames = [],
        localeOptions = null,
        removeText = 'Remove';

    /*Check if lang is not eng*/
    if (lang === 'ru') {
        localeOptions = dropzoneRU;
        removeText = 'Удалить'
    } else if (lang === 'kk') {
        localeOptions = dropzoneKK;
        removeText = 'Жою'
    }

    let newDropzone = new Dropzone(el, {
        url: url,
        paramName: "files[]",
        clickable: clickable,
        maxFiles: maxFiles,
        maxFilesize: maxSize,
        required: required,
        acceptedFiles: acceptedFiles,
        thumbnailHeight: null,
        previewsContainer: previewsContainer,
        previewTemplate: `<div class="dz-preview dz-file-preview">
                            <div class="dz-details">
                                ${showThumbnails ? '<img data-dz-thumbnail />' : ''}
                                <div class="dz-filename"><span data-dz-name></span></div>
                                <div class="dz-size" data-dz-size></div>
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                            </div>
                            <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                            <a href="javascript:undefined;" title="${removeText}" class="link red" data-dz-remove>${removeText}</a>
                        </div>`,
        init: function () {

            this.on('success', (files, response) => {
                filenames.push(String(response.filenames));
                filesPath.value = JSON.stringify(filenames);
                if (required) checkRequiredDropzone(el);
            });
            this.on('removedfile', (files) => {
                if (files.xhr) {
                    let removedValue = String(JSON.parse(files.xhr.response).filenames);
                    filenames = filenames.filter(function (item) {
                        if (required) checkRequiredDropzone(el);
                        return item !== removedValue
                    });
                }
                filesPath.value = JSON.stringify(filenames);
                if (required) checkRequiredDropzone(el);
            })


        },
        ...localeOptions
    });


    if (required) checkRequiredDropzone(el)

    if (maxFiles === 1 && previewsContainer.innerHTML !== '') {
        let item = previewsContainer.querySelector('.dz-preview'),
            filename = item.querySelector('.dz-filename') ? item.querySelector('.dz-filename').textContent : '',
            size = item.querySelector('.dz-size strong') ? item.querySelector('.dz-size strong').textContent : '';
        let mockFile = {name: filename, size: Number(size) * 1000};

        previewsContainer.innerHTML = '';
        newDropzone.displayExistingFile(mockFile, filesPath.value);
    }
};

// Required
// $(document).on('input[type="checkbox"]', 'change', function() {
//     if ($('.dz-preview').length) {
//
//     }
// });

function checkRequiredDropzone(el) {
    // if ($(el).parent().parent().parent().attr('style') === 'display: none') return;

    let count = $(el).find('.link.red:not([style="display: none;"])').length;

    // console.log(count);

    // if ($(el).find('input.req').length === 0)
    //     $(el).append('<input name="2" type="text" class="req" required disabled>');

    // console.log($(el).parent().find('input.req'))

    if (count > 0) {
        $(el).find('input.req')[0].value = 1;
    } else {
        $(el).find('input.req')[0].value = '';
    }
}

function TinyMceInit(selector, textOnly = false) {
    let lang = window.Laravel.lang,
        // baseUrl = 'https://dev3.panama.kz',
        baseUrl = '',
        // method = "/ajaxUploadImageTest",
        method = "/ajax_upload_lesson_another_file?_token=" + window.Laravel.csrfToken,
        additionalTools = '', input, progressModal, progressBar, cancelUploadBtn, progressMsgEl,
        vocabulary = {
            en: {
                uploadTitle: 'File upload',
                cancel: 'Cancel',
                fail: 'Failed to load file'
            },
            kk: {
                uploadTitle: 'Файл жүктеу',
                cancel: 'Жою',
                fail: 'Файл жүктелмеді'
            },
            ru: {
                uploadTitle: 'Загрузка файла',
                cancel: 'Отмена',
                fail: 'Не удалось загрузить файл'
            }
        },
        progressModalContent = `<div class="text-center">
                                    <h4 class="title-primary">${vocabulary[lang].uploadTitle}</h4>
                                    <div class="progress-bar"><span></span></div>
                                    <div class="plain-text gray"></div>
                                    <a href="javascript:;" title="Отмена" class="btn">${vocabulary[lang].cancel}</a>
                                </div>`;

    if (!textOnly) {
        additionalTools = 'image media';
    }

    if (!document.querySelector('#filePicker')) {
        input = document.createElement('input');
        input.type = 'file';
        input.id = 'filePicker';
        input.style.cssText = 'position: fixed; top: -9999px; left: -9999px; z-index: -1';
        document.querySelector('body').append(input);
    } else {
        input = document.querySelector('#filePicker');
    }

    if (!document.querySelector('#progressModal')) {
        progressModal = document.createElement('div');
        progressModal.id = 'progressModal';
        progressModal.innerHTML = progressModalContent;
        progressModal.style.display = 'none';
        document.querySelector('body').append(progressModal);
    } else {
        progressModal = document.querySelector('#progressModal');
    }

    progressBar = progressModal.querySelector('.progress-bar span');
    cancelUploadBtn = progressModal.querySelector('.btn');
    progressMsgEl = progressModal.querySelector('.plain-text');
    progressMsgEl.style.display = 'none';

    tinymce.init({
        selector: selector,
        menubar: false,
        plugins: [
            'lists link ' + additionalTools + ' table paste code wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic link ' + additionalTools + ' | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist | ' +
            'removeformat | help',
        images_upload_url: method,
        files_upload_url: method,
        file_picker_types: 'file image media',
        relative_urls: false,
        language: lang,
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'file') {
                input.accept = '.pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg, .rar, .zip, .7z, .mp3, .mp4, .avi, .mov';
                pickerCallback(callback);
            }

            // Provide image and alt text for the image dialog
            if (meta.filetype === 'image') {
                input.accept = '.png, .jpg, .jpeg, .gif';
                pickerCallback(callback, 'image');
            }

            // Provide alternative source and posted for the media dialog
            if (meta.filetype === 'media') {
                input.accept = '.mp4, .avi, .mov';
                pickerCallback(callback, 'video');
            }
        },
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
            editor.on('Undo', function () {
                editor.save();
            });
            editor.on('Redo', function () {
                editor.save();
            });
        }
    });

    function pickerCallback(callback, fileType = null) {
        input.click();
        input.onchange = function () {
            let fd = new FormData();
            let file = input.files[0];
            fd.append('file', file);
            let ajaxUpload = $.ajax({
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            let percentComplete = ((evt.loaded / evt.total) * 100);
                            progressBar.style.width = percentComplete + '%';
                        }
                    }, false);
                    return xhr;
                },
                url: baseUrl + method,
                type: 'POST',
                processData: false,
                contentType: false,
                data: fd,
                beforeSend: function () {
                    progressMsgEl.style.display = 'none';
                    progressBar.style.width = 0;
                    cancelUploadBtn.addEventListener('click', abortUpload);
                    $.fancybox.open({
                        src: '#' + progressModal.id,
                        touch: false,
                        smallBtn: false,
                        buttons: [],
                        clickSlide: false,
                        clickOutside: false
                    });
                },
                error: function (response) {
                    console.log(response);
                    input.value = '';
                    progressBar.style.width = 0;
                    progressMsgEl.style.display = 'block';
                    progressMsgEl.innerHTML = vocabulary[lang].fail;
                },
                success: function (response) {
                    switch (fileType) {
                        case 'video':
                            callback(baseUrl + response.location, {width: '100%', height: 'auto'});
                            break;
                        case 'image':
                            callback(baseUrl + response.location, {});
                            break;
                        default:
                            callback(baseUrl + response.location, {});
                            break;
                    }
                    input.value = '';
                    parent.jQuery.fancybox.getInstance().close();
                    cancelUploadBtn.removeEventListener('click', abortUpload);
                }
            });

            function abortUpload() {
                ajaxUpload.abort();
                input.value = '';
                $.fancybox.close();
                progressBar.style.width = 0;
                cancelUploadBtn.removeEventListener('click', abortUpload);
            }
        };
    }
}

const selectizeSingleOptions = {
    allowEmptyOption: true,
    onChange: function () {
        this.$input[0].dispatchEvent(changeEvent);
    }
};
const selectizeMultipleOptions = {
    allowEmptyOption: true,
    plugins: ['remove_button', 'silent_remove', 'stop_backspace_delete'],
    onChange: function () {
        if (this.$input[0].attributes.placeholder) {
            this.$control_input[0].placeholder = this.$input[0].attributes.placeholder.value
        }
        this.$input[0].dispatchEvent(changeEvent);
    }
};

function selectizeRegularInit() {
    $('.selectize-regular:not([multiple]):not(.selectized)').selectize(selectizeSingleOptions);

    $('.selectize-regular[multiple]:not(.selectized)').selectize(selectizeMultipleOptions);
};

//Datepicker settings
$.fn.datepicker.language['en'] = {
    days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    today: 'Today',
    clear: 'Clear',
    dateFormat: 'dd.mm.yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 0
};
$.fn.datepicker.language['kk'] = {
    days: ['Жексенбі', 'Дүйсенбі', 'Сейсенбі', 'Сәрсенбі', 'Бейсенбі', 'Жұма', 'Сенбі'],
    daysShort: ['Жек', 'Дүй', 'Сей', 'Сәр', 'Бей', 'Жұм', 'Сен'],
    daysMin: ['Же', 'Дү', 'Се', 'Сә', 'Бе', 'Жұ', 'Се'],
    months: ['Қаңтар', 'Ақпан', 'Наурыз', 'Сәуір', 'Мамыр', 'Маусым', 'Шілде', 'Тамыз', 'Қыркүйек', 'Қазан', 'Қараша', 'Желтоқсан'],
    monthsShort: ['Қаң', 'Ақп', 'Нау', 'Сәу', 'Мам', 'Мау', 'Шіл', 'Там', 'Қыр', 'Қаз', 'Қар', 'Жел'],
    today: 'Бүгін',
    clear: 'Тазалау',
    dateFormat: 'dd.mm.yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 0
};
$.fn.datepicker.language['ru'] = {
    days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
    daysShort: ['Вос', 'Пон', 'Вто', 'Сре', 'Чет', 'Пят', 'Суб'],
    daysMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    monthsShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
    today: 'Сегодня',
    clear: 'Очистить',
    dateFormat: 'dd.mm.yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 0
};
