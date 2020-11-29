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
    $('[data-fancybox]').click(function(e) {
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
    document.querySelectorAll('.dropzone-multiple').forEach(function(item) {
        let url = item.dataset.url,
            maxFiles = Number(item.dataset.maxfiles),
            maxSize = Number(item.dataset.maxsize),
            acceptedFiles = item.dataset.acceptedfiles;
        let createDropzone = new CustomDropzone(item, url, maxFiles, maxSize, acceptedFiles, acceptedFiles === 'image/*');
    });

    /*Avatar dropzone init*/
    document.querySelectorAll('.dropzone-avatar').forEach(function(item) {
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
    document.querySelectorAll('.single-range-slider').forEach(function(item) {
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

    document.querySelectorAll('[data-duplicate]').forEach(function (el) {
        let copyEl = document.querySelector('#' + el.dataset.duplicate),
            duplicatesContainer = el.closest('.pull-up').previousElementSibling;

        let cloneTpl = copyEl.cloneNode(true);
        cloneTpl.value = '';

        el.addEventListener('click', function (e) {
            e.preventDefault();

            let clone = cloneTpl.cloneNode(true);

            clone.removeAttribute('required');
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

    selectizeRegularInit();

    /*init Tinymce*/
    TinyMceInit('.tinymce-here');

    TinyMceInit('.tinymce-text-here', true);

    document.querySelectorAll('[data-toggle]').forEach(function (el) {
        let targetSelectors = el.dataset.toggle.split(',');
        el.addEventListener('change', function (e) {
            targetSelectors.forEach(function (selector) {
                toggleEl(document.querySelector('#' + selector));
            });
        });
    });

    document.querySelectorAll('.topic.spoiler').forEach(function (el) {
        let topicTitle = el.querySelector('.topic__header');

        topicTitle.addEventListener('click', function () {
            el.classList.toggle('collapsed');
        });
    });

    $('.custom-datepicker').datepicker({
        language: lang,
        autoClose: true
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
        });

        recoverBtn.addEventListener('click', function () {
            input.removeAttribute('disabled');
            hideEl(recoverBtn);
            showEl(removeBtn);
        })
    });
});

//Fixed header
$(window).scroll(function () {
    if ($(window).scrollTop() > 0) {
        $('.header').addClass('fixed');
    } else {
        $('.header').removeClass('fixed');
    }
});

//Chosen-select init
function chosenInit() {
    if ($('.chosen').length) {
        let lang = document.getElementById('lang').value,
            resultMessage;
        if (lang == 'en') {
            resultMessage = 'No results found for'
        } else if (lang == 'kk') {
            resultMessage = 'Нәтижелер жоқ'
        } else {
            resultMessage = 'Ничего не найдено по запросу';
        }

        $(".chosen").chosen({
            search_contains: true,
            no_results_text: resultMessage
        });
    }
}

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
        $('header').css('margin-top', scrollTop);
        $('body').addClass('no-scroll').css('margin-right', getScrollbarWidth());
        find.css('margin-right', getScrollbarWidth());
    }
}

//Make body scrollable
function bodyScrollable() {
    if ($('body').hasClass('no-scroll')) {
        let scrollTop = $('.main-wrapper').css('margin-top').slice(0, -2);
        $('header').css('margin-top', 0);
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
            chart.language.locale = am4lang_kk_KK;
        }

        // Add data
        $.ajax({
            url: url,
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
    if (el.style.display === "none") {
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
        let mockFile = { name: filename, size: Number(size)*1000};

        previewsContainer.innerHTML = '';
        newDropzone.displayExistingFile(mockFile, avatarPath.value);
    }
};

let CustomDropzone = function (el, url, maxFiles, maxSize, acceptedFiles, showThumbnails = false) {
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
                filesPath.value = JSON.stringify(filenames)
            });
            this.on('removedfile', (files) => {
                if (files.xhr) {
                    let removedValue = String(JSON.parse(files.xhr.response).filenames);
                    filenames = filenames.filter(function (item) {
                        return item !== removedValue
                    });
                }
                filesPath.value = JSON.stringify(filenames);
            })
        },
        ...localeOptions
    });

    if (maxFiles === 1 && previewsContainer.innerHTML !== '') {
        let item = previewsContainer.querySelector('.dz-preview'),
            filename = item.querySelector('.dz-filename') ? item.querySelector('.dz-filename').textContent : '',
            size = item.querySelector('.dz-size strong') ? item.querySelector('.dz-size strong').textContent : '';
        let mockFile = { name: filename, size: Number(size)*1000};

        previewsContainer.innerHTML = '';
        newDropzone.displayExistingFile(mockFile, filesPath.value);
    }
};

function TinyMceInit (selector, textOnly = false) {
    let lang = window.Laravel.lang;
    let additionalTools = '';

    if (!textOnly) {
        additionalTools = 'image media';
    }

    tinymce.init({
        selector: selector,
        menubar: false,
        plugins: [
            'pageembed  lists link ' + additionalTools,
            'table paste code wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic link ' + additionalTools + ' | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist | ' +
            'removeformat | help',
        images_upload_url: "/ajax_upload_image?_token="+window.Laravel.csrfToken,
        files_upload_url: "/ajax_upload_file?_token="+window.Laravel.csrfToken,
        file_picker_types: 'file image media',
        language: lang,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
};

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
