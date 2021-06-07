//Ajax select constructor
let ajaxSelect = function (el, parentEl = null, multiLang = true, maxItems = null) {
    let self = this;

    this.el = el;
    this.parentEl = parentEl;

    if (parentEl) {
        this.parentElName = parentEl.attr('name');
    }

    this.multiLang = multiLang;

    this.method = this.el.data('method');
    // this.domainName = 'https://dev3.panama.kz';
    this.domainName = document.location.origin;
    this.lang = window.Laravel.lang;
    this.url = this.domainName + '/' + this.lang + '/' + this.method;
    this.pageCounter = 1;
    this.lastPage = false;
    this.loadEnable = true;

    //init Selectize
    this.select = this.el.selectize({
        plugins: this.el[0].hasAttribute('multiple') ? ['remove_button', 'silent_remove', 'stop_backspace_delete'] : null,
        allowEmptyOption: true,
        maxOptions: 10000,
        maxItems: maxItems ? maxItems : el.attr('multiple') ? null : 1,
        onInitialize: function () {
            let additionalData;
            if (self.parentEl && self.parentEl.val()) {
                additionalData = {};
                additionalData[self.parentElName] = toArray(self.parentEl.val());
            }
            sendRequest(additionalData);
        },
        onChange: function () {
            this.$input[0].dispatchEvent(changeEvent);
        }
    });
    //fetch the instance
    this.controls = this.select[0].selectize;
    this.dropdownContent = this.controls.$dropdown_content;
    this.noResultsDefaultMsg = {
        en: 'No results',
        ru: 'Ничего не найдено',
        kk: 'Ештене табылган жок'
    };
    this.noResultsMsg = this.controls.$input[0].dataset.noresults ? this.controls.$input[0].dataset.noresults : this.noResultsDefaultMsg[this.lang];

    //On type event
    this.controls.on('type', delay(function (val) {
        let additionalData;
        if (self.parentEl && self.parentEl.val()) {
            additionalData = {};
            additionalData[self.parentElName] = toArray(self.parentEl.val());
        }
        sendRequest(additionalData, val, true);
    }, 1000));

    this.controls.on('change', function (e) {
        self.controls.$control_input[0].placeholder = self.el[0].attributes.placeholder.value;
        self.loadEnable = false;
        setTimeout(function () {
            self.loadEnable = true;
        }, 150);
    });

    this.controls.on('blur', function () {
        self.controls.$control[0].classList.remove('no-results');
        self.removeMessage();
    });

    this.controls.on('focus', function () {
        if (!self.el[0].hasAttribute('multiple')) {
            self.controls.clear();
        }
    });

    this.dropdownContent.on('scroll', function () {
        let additionalData;
        if (self.parentEl && self.parentEl.val()) {
            additionalData = {};
            additionalData[self.parentElName] = toArray(self.parentEl.val());
        }
        let pxToBottom = self.dropdownContent[0].scrollHeight - self.dropdownContent[0].scrollTop - self.dropdownContent[0].clientHeight;
        if (pxToBottom < 1000 && self.loadEnable && !self.lastPage) {
            self.loadEnable = false;
            self.controls.$dropdown[0].classList.add('loading');

            let scrollTop = self.dropdownContent[0].scrollTop;

            self.getNextPage(additionalData, scrollTop);
        }
    });

    this.renderNewOptions = function(data) {
        let newOptions = [];
        data.forEach(function (item) {
            newOptions.push({
                value: item.id,
                text: item["name" + (self.multiLang ? '_' + self.lang : '')]
            });
        });
        self.controls.addOption(newOptions);
    };

    this.saveOldOptions = function() {
        let selectedValues = [];
        Object.values(self.controls.options).forEach((item) => {
            selectedValues.push(item.value);
        });
        self.controls.setValue(selectedValues, true);
    };

    this.addMessage = function() {
        let message = document.createElement('div');
        message.className = 'noresults-message';
        message.innerHTML = self.noResultsMsg;
        self.controls.$control[0].parentElement.append(message);
    };

    this.removeMessage = function() {
        let message = self.controls.$control[0].parentElement.querySelector('.noresults-message');
        if (message) {
            message.remove();
        }
    };

    this.focusOnSearchField = function(val) {
        self.controls.setTextboxValue(val);
        self.controls.focus();
    };

    this.addDefaultOption = function() {
        self.controls.addOption({
            $order: 0,
            value: '',
            text: self.el.data('default')
        });
    };

    this.getNextPage = function(additionalData, scrollTop) {
        $.ajax({
            type: 'GET',
            url: self.url,
            data: {
                ...additionalData,
                "name": self.controls.lastQuery,
                "page": self.pageCounter + 1
            },
            success: function (response) {
                self.renderNewOptions(response.data);
                self.controls.refreshOptions();
                self.loadEnable = true;
                self.pageCounter++;

                setTimeout(function () {
                    self.controls.$dropdown[0].classList.remove('loading');
                }, 150);

                self.dropdownContent[0].scrollTop = scrollTop;

                if (response.current_page === response.last_page) {
                    self.lastPage = true;
                }
            },
            error: function (data) {
                console.log(data);
            }
        })
    };

    function sendRequest(additionalData = null, searchValue = '', focus = false, clearOptions = true) {
        $.ajax({
            type: 'GET',
            url: self.url,
            data: {
                ...additionalData,
                "name": searchValue
            },
            success: function (response) {
                if (clearOptions) {
                    self.controls.clearOptions(true);
                }

                if (response.data.length !== 0) {

                    self.saveOldOptions();

                    if (self.el.data('default')) {
                        self.addDefaultOption();
                    }

                    self.renderNewOptions(response.data);

                    if (focus) {
                        self.focusOnSearchField(searchValue);
                    }

                    self.removeMessage();
                } else {
                    self.controls.$control[0].classList.add('no-results');
                    self.addMessage();
                }

                self.pageCounter = 1;
                self.lastPage = (response.current_page === response.last_page);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    this.update = function (additionalData) {
        sendRequest(additionalData);
    };

    this.clear = function () {
        self.controls.clear(true);
    };

    this.clearOptions = function () {
        this.controls.clearOptions(true);
    };
};

let ajaxSelect2 = function (el, multiLang = true, skillId = null, maxItems = null) {
    let self = this;

    this.el = el;
    this.multiLang = multiLang;
    this.skillId = skillId;

    this.method = this.el.data('method');
    // this.domainName = 'https://dev3.panama.kz';
    this.domainName = document.location.origin;
    this.lang = window.Laravel.lang;
    this.url = this.domainName + '/' + this.lang + '/' + this.method;
    this.pageCounter = 1;
    this.lastPage = false;
    this.loadEnable = true;

    //init Selectize
    this.select = this.el.selectize({
        plugins: this.el[0].hasAttribute('multiple') ? ['remove_button', 'silent_remove', 'stop_backspace_delete'] : null,
        allowEmptyOption: true,
        maxOptions: 10000,
        maxItems: maxItems,
        onInitialize: function () {
            sendRequest(self.skillId);
        },
        onChange: function () {
            this.$input[0].dispatchEvent(changeEvent);
        }
    });
    //fetch the instance
    this.controls = this.select[0].selectize;
    this.dropdownContent = this.controls.$dropdown_content;
    this.noResultsDefaultMsg = {
        en: 'No results',
        ru: 'Ничего не найдено',
        kk: 'Ештене табылган жок'
    };
    this.noResultsMsg = this.controls.$input[0].dataset.noresults ? this.controls.$input[0].dataset.noresults : this.noResultsDefaultMsg[this.lang];

    //On type event
    this.controls.on('type', delay(function (val) {
        sendRequest(self.skillId, val, true);
    }, 1000));

    this.controls.on('change', function () {
        self.controls.$control_input[0].placeholder = self.el[0].attributes.placeholder.value;
        self.loadEnable = false;
        setTimeout(function () {
            self.loadEnable = true;
        }, 150);
    });

    this.controls.on('blur', function () {
        self.controls.$control[0].classList.remove('no-results');
        self.removeMessage();
    });

    this.controls.on('focus', function () {
        if (!self.el[0].hasAttribute('multiple')) {
            self.controls.clear();
        }
    });

    this.dropdownContent.on('scroll', function () {
        let additionalData = self.parentEl ? (self.parentEl.val() ? {'professions': toArray(self.parentEl.val())} : null) : null;
        let pxToBottom = self.dropdownContent[0].scrollHeight - self.dropdownContent[0].scrollTop - self.dropdownContent[0].clientHeight;
        if (pxToBottom < 1000 && self.loadEnable && !self.lastPage) {
            self.loadEnable = false;
            self.controls.$dropdown[0].classList.add('loading');

            let scrollTop = self.dropdownContent[0].scrollTop;

            self.getNextPage(self.skillId, scrollTop);
        }
    });

    this.renderNewOptions = function(data) {
        let newOptions = [];
        data.forEach(function (item) {
            newOptions.push({
                value: item.id,
                text: item["name" + (self.multiLang ? '_' + self.lang : '')]
            });
        });
        self.controls.addOption(newOptions);
    };

    this.saveOldOptions = function() {
        let selectedValues = [];
        Object.values(self.controls.options).forEach((item) => {
            selectedValues.push(item.value);
        });
        self.controls.setValue(selectedValues);
    };

    this.addMessage = function() {
        let message = document.createElement('div');
        message.className = 'noresults-message';
        message.innerHTML = self.noResultsMsg;
        self.controls.$control[0].parentElement.append(message);
        setTimeout(function () {
            self.removeMessage();
        }, 3000);
    };

    this.removeMessage = function() {
        let message = self.controls.$control[0].parentElement.querySelector('.noresults-message');
        if (message) {
            message.remove();
        }
    };

    this.focusOnSearchField = function(val) {
        self.controls.setTextboxValue(val);
        self.controls.focus();
    };

    this.addDefaultOption = function() {
        self.controls.addOption({
            $order: 0,
            value: '',
            text: self.el.data('default')
        });
    };

    this.getNextPage = function(skillId, scrollTop) {
        $.ajax({
            type: 'GET',
            url: self.url + '/' + skillId,
            data: {
                "name": self.controls.lastQuery,
                "page": self.pageCounter + 1
            },
            success: function (response) {
                self.renderNewOptions(response.data);
                self.controls.refreshOptions();
                self.loadEnable = true;
                self.pageCounter++;

                setTimeout(function () {
                    self.controls.$dropdown[0].classList.remove('loading');
                }, 150);

                self.dropdownContent[0].scrollTop = scrollTop;

                if (response.current_page === response.last_page) {
                    self.lastPage = true;
                }
            },
            error: function (data) {
                console.log(data);
            }
        })
    };

    function sendRequest(skillId, searchValue = '', focus = false, clearOptions = true) {
        if (skillId !== -1) {
            $.ajax({
                type: 'GET',
                url: self.url + '/' + skillId,
                data: {
                    "name": searchValue
                },
                success: function (response) {
                    if (clearOptions) {
                        self.controls.clearOptions(true);
                    }

                    if (response.data.length !== 0) {

                        self.saveOldOptions();

                        if (self.el.data('default')) {
                            self.addDefaultOption();
                        }

                        self.renderNewOptions(response.data);

                        if (focus) {
                            self.focusOnSearchField(searchValue);
                        }

                        self.removeMessage();
                    } else {
                        self.controls.$control[0].classList.add('no-results');
                        self.addMessage();
                    }

                    self.pageCounter = 1;
                    self.lastPage = (response.current_page === response.last_page);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    this.update = function (param) {
        sendRequest(param);
    };

    this.clear = function () {
        this.controls.clear(true);
    };

    this.clearOptions = function () {
        this.controls.clearOptions(true);
    }

    // this.removeMessage = function () {
    //     removeMessage();
    // }
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

Selectize.define('silent_remove', function (options) {
    let self = this;

    // defang the internal search method when remove has been clicked
    this.on('item_remove', function () {
        this.plugin_silent_remove_in_remove = true;
    });

    this.search = (function () {
        let original = self.search;
        return function () {
            if (typeof (this.plugin_silent_remove_in_remove) != "undefined") {
                // re-enable normal searching
                delete this.plugin_silent_remove_in_remove;
                return {
                    items: {},
                    query: [],
                    tokens: []
                };
            } else {
                return original.apply(this, arguments);
            }
        };
    })();
});

Selectize.define("stop_backspace_delete", function (options) {
    let self = this;

    this.deleteSelection = (function () {
        let original = self.deleteSelection;

        return function (e) {
            if (!e || e.keyCode !== 8) {
                return original.apply(this, arguments);
            }

            return false;
        };
    })();
});
