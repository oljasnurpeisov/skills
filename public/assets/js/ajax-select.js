//Ajax select constructor
let ajaxSelect = function (el, parentEl = null, multiLang = true) {
    const domainName = 'https://dev7.panama.kz/',
        method = el.data('method'),
        lang = window.Laravel.lang,
        url = domainName + lang + '/' + method;

    let pageCounter = 1,
        lastPage = false,
        loadEnable = true;

    //init Selectize
    let select = el.selectize({
        plugins: el[0].hasAttribute('multiple') ? ['remove_button', 'silent_remove', 'stop_backspace_delete'] : null,
        allowEmptyOption: true,
        maxOptions: 10000,
        // sortField: [
        //   {
        //     field: 'text',
        //     direction: 'asc'
        //   }
        // ],
        onInitialize: function () {
            let additionalData = parentEl ? (parentEl.val() ? {'professions': toArray(parentEl.val())} : null) : null;
            sendRequest(additionalData);
        }
    });
    //fetch the instance
    let controls = select[0].selectize,
        dropdownContent = controls.$dropdown_content;

    //On type event
    controls.on('type', delay(function (val) {
        let additionalData = parentEl ? (parentEl.val() ? {'professions': toArray(parentEl.val())} : null) : null;
        sendRequest(additionalData, val, true);
    }, 1000));

    controls.on('change', function () {
        controls.$control_input[0].placeholder = el[0].attributes.placeholder.value
    });

    dropdownContent.on('scroll', function () {
        let additionalData = parentEl ? (parentEl.val() ? {'professions': toArray(parentEl.val())} : null) : null;
        let pxToBottom = dropdownContent[0].scrollHeight - dropdownContent[0].scrollTop - dropdownContent[0].clientHeight;
        if (pxToBottom < 1000 && loadEnable && !lastPage) {
            loadEnable = false;
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    ...additionalData,
                    "name": controls.lastQuery,
                    "page": pageCounter + 1
                },
                success: function (response) {
                    renderNewOptions(response.data);
                    controls.refreshOptions();
                    loadEnable = true;
                    pageCounter++;

                    if (response.current_page === response.last_page) {
                        lastPage = true;
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    });

    if (!el[0].hasAttribute('multiple')) {
        controls.on('focus', function () {
            controls.clear();
        });
    }

    function renderNewOptions(data) {
        let newOptions = [];
        data.forEach(function (item) {
            newOptions.push({
                value: item.id,
                text: item["name" + (multiLang ? '_' + lang : '')]
            });
        });
        controls.addOption(newOptions);
    }

    function saveOldOptions() {
        let selectedValues = [];
        Object.values(controls.options).forEach((item) => {
            selectedValues.push(item.value);
        });
        controls.setValue(selectedValues);
    }

    function focusOnSearchField(val) {
        controls.setTextboxValue(val);
        controls.focus();
    }

    function addDefaultOption() {
        controls.addOption({
            $order: 0,
            value: '',
            text: el.data('default')
        });
    }

    function sendRequest(additionalData = null, searchValue = '', focus = false, clearOptions = true) {
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                ...additionalData,
                "name": searchValue
            },
            success: function (response) {
                console.log(response.data)
                if (clearOptions) {
                    controls.clearOptions();
                }

                saveOldOptions();

                if (el.data('default')) {
                    addDefaultOption();
                }

                renderNewOptions(response.data);

                if (focus) {
                    focusOnSearchField(searchValue);
                }

                pageCounter = 1;
                lastPage = false;
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    this.update = function (additionalData) {
        sendRequest(additionalData);
    };
};

//Delay function
function delay(callback, ms) {
    let timer = 0;
    return function () {
        let context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

Selectize.define('silent_remove', function(options){
    let self = this;

    // defang the internal search method when remove has been clicked
    this.on('item_remove', function(){
        this.plugin_silent_remove_in_remove = true;
    });

    this.search = (function() {
        let original = self.search;
        return function() {
            if (typeof(this.plugin_silent_remove_in_remove) != "undefined") {
                // re-enable normal searching
                delete this.plugin_silent_remove_in_remove;
                return {
                    items: {},
                    query: [],
                    tokens: []
                };
            }
            else {
                return original.apply(this, arguments);
            }
        };
    })();
});

Selectize.define("stop_backspace_delete", function (options) {
    let self = this;

    this.deleteSelection = (function() {
        let original = self.deleteSelection;

        return function (e) {
            if (!e || e.keyCode !== 8) {
                return original.apply(this, arguments);
            }

            return false;
        };
    })();
});
